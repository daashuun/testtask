<?php

namespace app\controllers;

use Yii;
use app\models\ResumeForm;
use app\models\WorkForm;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\helpers\Html;
use app\models\enums\Sity;
use app\models\enums\Specialization;
use app\Models\Search;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $resumes = $this->actionSearch();
        $search = new Search;
        return $this->render('index', compact('resumes', 'search'));
    }

    public function actionSearch() 
    {

        $name = 'Результаты поиска';
        $query = '';
        if (Yii::$app->request->get()) {

            if (Yii::$app->request->get('salary')) {
                $query .= 'salary <= '.$_GET['salary'];
            }
            
            if (Yii::$app->request->get('start')) {

                $year = $_GET['start'];
                $year = date('Y') - $year;
                $year = "'". $year ."-01-01'";
                if (strlen($query)>0){
                    $query .= ' AND birtday <= '.$year;
                } else {
                    $query = 'birtday <= '.$year;
                }
            }

            if (Yii::$app->request->get('end')) {

                $year = $_GET['end'];
                $year = date('Y') - $year;
                $year = "'". $year ."-01-01'";
                if (strlen($query)>0){
                    $query .= ' AND birtday >= '.$year;
                } else {
                    $query = 'birtday >= '.$year;
                }
            }

            if (Yii::$app->request->get('sex')) {
                $sex = Yii::$app->request->get('sex');
                if (Yii::$app->request->get('sex')==2) $sex = 0;
                if (strlen($query)>0){
                    $query .= ' AND sex = '.$sex;
                } else {
                    $query = 'sex = '.$sex;
                }
            }

            if (Yii::$app->request->get('spec')) {
                $name = Specialization::getLabel($_GET['spec']);
                if (strlen($query)>0){
                    $query .= ' AND specialization = '.$_GET['spec'];
                } else {
                    $query = 'specialization = '.$_GET['spec'];
                }
            }

            if (Yii::$app->request->get('sity')) {
                $name .= ', '.Sity::getLabel($_GET['sity']);
                if (strlen($query)>0){
                    $query .= ' AND sity = '.$_GET['sity'];
                } else {
                    $query = 'sity = '.$_GET['sity'];
                }
            }

            if ((Yii::$app->request->get('employment'))||(Yii::$app->request->get('schedule'))) {
                $resumes = ResumeForm::find()->all();
                if (strlen($query)>0){
                    $query .= ' AND ';
                };
                $first = true;
                if (Yii::$app->request->get('employment')) {
                    $employment = $_GET['employment'];
                    foreach ($resumes as $id=>$resume) {
                        $del = true;
                        $emps[$resume['id']] = json_decode($resume['employment']);
                        for ($i = 0; $i < strlen($employment); $i++) {
                            foreach ($emps[$resume['id']] as $emp)
                                if ($employment[$i]==$emp) {
                                    $del = false;
                                }
                        }
                        if ($del) {
                            if ($first) {
                                $first = false;
                                $query .= ' id != '.$resume['id'];
                            } else {
                                $query .= ' AND id != '.$resume['id'];
                            }
                        }
                    }
                }
                
                if (Yii::$app->request->get('schedule')) {
                    $schedule = $_GET['schedule'];
                    foreach ($resumes as $id=>$resume) {
                        $del = true;
                        $emps[$resume['id']] = json_decode($resume['schedule']);
                        for ($i = 0; $i < strlen($schedule); $i++) {
                            foreach ($emps[$resume['id']] as $emp)
                                if ($schedule[$i]==$emp) {
                                    $del = false;
                                }
                        }
                        if ($del) {
                            if ($first) {
                                $first = false;
                                $query .= ' id != '.$resume['id'];
                            } else {
                                $query .= ' AND id != '.$resume['id'];
                            }
                        }
                    }
                }
            }

            if (Yii::$app->request->get('exp')) {
                if (strlen($query)>0){
                    $query .= ' AND ';
                }
                $exps = $_GET['exp'];
                for ($i = 0; $i < strlen($exps); $i++) {
                    $exp = $exps[$i];
                    if ($i>0){
                        $query .= ' OR exp = '.$exp;
                    } else {
                        $query .= '(exp = '.$exp;
                    }
                }
                $query .= ')';
            }

            if (Yii::$app->request->get('text')) {

                $_GET['text'] = Html::encode($_GET['text']);
                $text =  explode(' ', $_GET['text']);
                if (strlen($query)>0) {
                    $query .= " AND ";
                }
                $query .= "(";
                foreach ($text as $key=>$word){
                    if ($key != 0) {
                        $query .= " OR ( about LIKE '%".$word."%' OR name LIKE '%".$word."%' OR surname LIKE '%".$word."%' OR middlename LIKE '%".$word."%' ) ";
                    } else {
                        $query .= " ( about LIKE '%".$word."%' OR name LIKE '%".$word."%' OR surname LIKE '%".$word."%' OR middlename LIKE '%".$word."%' ) ";
                    }

                    $validWorks = [];

                    $works = WorkForm::find()->where(" duties LIKE '%".$word."%' OR company LIKE '%".$word."%' OR position LIKE '%".$word."%' ")->all();
                    if (count($works)>0) {
                        foreach ($works as $work) {
                            if (!in_array($work['resumeId'], $validWorks)) 
                                $validWorks[] = $work['resumeId'];
                        }
                    }
                }

                if (count($validWorks)>0) {

                    if (strlen($query)>0) {
                        $query .= " OR ";
                    }
                    foreach ($validWorks as $key => $id) {
                        if ($key != 0) {
                            $query .= " OR ( id = ".$id." ) ";
                        } else {
                            $query .= " ( id = ".$id." ) ";
                        }
                    }
                }

                $query .= " )";

            }

        } else {
            $query = ['whereAll'=>'1'];
        }
        $sort = new Sort([
            'defaultOrder' => [ 'changed' => SORT_DESC ],
            'attributes' => [
                'changed' => [
                    'asc' => [ 'changed' => SORT_DESC ],
                    'desc' => [ 'changed' => SORT_DESC ],
                    'defaultOrder' => SORT_DESC,
                    'label' => 'По новизне',
                ],
                'salary' => [
                    'asc' => [ 'salary' => SORT_DESC ],
                    'desc' => [ 'salary' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'По убыванию зарплаты',
                ],
                'salaryDown' => [
                    'asc' => [ 'salary' => SORT_ASC ],
                    'desc' => [ 'salary' => SORT_ASC ],
                    'default' => SORT_ASC,
                    'label' => 'По возрастанию зарплаты',
                ],
            ],
        ]);
        $resume = ResumeForm::find()->with('work')->where($query)->orderBy($sort->orders);
        $count = $resume->count();
        $pages = new Pagination(['totalCount' => $resume->count(), 'pageSize' => 5]);
        $resumes = $resume->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        foreach ($resumes as $resume) {
            $works = $resume->work;
            $resume->setResume(true, $works);
            if (strlen($works[0]['resumeId'])>0) {
                $works[0]->setWork();
                $resume['last'] = $works[0]['position'].' в '.$works[0]['company'].', '.$works[0]['startYear'].' - '.$works[0]['endYear'];
            }
        };
        return $this->renderPartial('resume-list', compact('resumes', 'pages', 'count', 'sort', 'name'));
    }

    public function actionWork($form = 0, $works = [])
    {
        if (Yii::$app->request->isAjax) {
            $exp = new WorkForm();
            $id = $_POST['id'];
            return $this->renderPartial('work', compact('exp', 'id'));
        };
        foreach ($works as $id=>$exp){
            echo $this->renderPartial('work', compact('exp', 'id', 'form'));
        };
    }

    public function actionChange()
    {
        $foto = $_FILES;
        $name = ResumeForm::changeImg($foto);
        return $name;
    }

    public function actionDelete() 
    {
        $foto = ResumeForm::find()->select('photo')->where(['id' => Yii::$app->request->post('id')])->one();
        if (file_exists('images/photo/'.$foto['photo']))
            unlink ('images/photo/'.$foto['photo']);
        ResumeForm::deleteAll(['id' => Yii::$app->request->post('id')]);
        return;
    }

    public function actionSave()
    {
        if (Yii::$app->request->post('ResumeForm')['id']) {
            $resume = ResumeForm::findOne(Yii::$app->request->post('ResumeForm')['id']);
        } else {
            $resume = new ResumeForm();
        }
        $resume->load(Yii::$app->request->post('ResumeForm'), '');
        WorkForm::deleteAll(['resumeId'=>$resume['id']]);
        $foto = ResumeForm::find()->select('photo')->where(['id' => $resume['id']])->one();
        if (file_exists('images/photo/'.$foto['photo']))
            unlink ('images/photo/'.$foto['photo']);
        ResumeForm::saveImg($resume['photo']);
        $resume['name'] = $this->mb_ucfirst(mb_strtolower($resume['name'], 'UTF-8'));
        $resume['surname'] = $this->mb_ucfirst(mb_strtolower($resume['surname'], 'UTF-8'));
        $resume['middlename'] = $this->mb_ucfirst(mb_strtolower($resume['middlename'], 'UTF-8'));
        $resume['birtday'] = date("Y.m.d", strtotime($resume['birtday']));
        $resume['changed'] = date("Y.m.d G:i:s");
        $resume['schedule'] = json_encode($resume['schedule']);
        $resume['employment'] = json_encode($resume['employment']);
        $w = Yii::$app->request->post('WorkForm');
        if ($w) {
            foreach ($w as $id=>$work) {
                if ($work) {
                    $works[$id] = new WorkForm();
                    $works[$id]->load($work, '');
                }
            }
            $works = WorkForm::sortWorks($works);
            $year = 0;
            $month = 0;
            foreach ($works as $work) {
                $month = $month + $work['endMonth']+(12-$work['startMonth']);
                $year = $year + ($work['endYear']-$work['startYear']);
                if ($month>=12) {
                    $year++;
                    $month = $month - 12;
                } else {
                    if ($work['endYear']!=$work['startYear'])
                        $year--;
                }
            }
            $resume['exp'] = 3;
            if ($year<6) {
                if (($year>1)&&($year<3)) {
                    $resume['exp'] = 1;
                } else {
                    if ($year<1) {
                        $resume['exp'] = 4;
                    } else {
                        $resume['exp'] = 2;
                    }
                }
            }
        } else $resume['exp'] = 4;
        $resume['about'] = Html::encode($resume['about']);
        $resume->save(false);
        if ($resume['experience']){
            foreach ($works as $id=>$work) {
                if ($work) {
                    $work['resumeId'] = $resume['id'];
                    $year = ($work['endYear']-$work['startYear']) ? ($work['endYear']-$work['startYear'])+2000 : '2000';
                    $month = ($work['endMonth']-$work['startMonth']);
                    if ($month!=0) {
                        if ($month<0) {
                            $year = $year - 1;
                            $month = $month + 12;
                        }
                    } else $month = '00';
                    $work['time'] = $year.'-'.$month.'-01';
                    $work['position'] = $this->mb_ucfirst(mb_strtolower($work['position'], 'UTF-8'));
                    $work['duties'] = Html::encode($work['duties']);
                    $works[$id]->save();
                }
            }
        }
        return $this->redirect('my-resume');
    }

    public function actionMyResume()
    {
        $resumes = ResumeForm::find()->with('work')->all();
        foreach ($resumes as $resume) {
            if (!empty($resume)) {
                $resume->setResume(true, $resume->work);
            }
        }
        return $this->render('my-resume', compact('resumes'));
    }

    public function actionEdit($id)
    {
        $resume = ResumeForm::find()->with('work')->where(['id' => $id])->one();
        $resume['about'] = Html::decode($resume['about']);
        $resume->setResume(false);
        return $this->render('new-resume', compact('resume'));
    }

    public function actionShowResume()
    {
        $back = $_SERVER['HTTP_REFERER'];
        $resume = ResumeForm::find()->where(['id' => $_GET['id']])->one();
        $resume['view'] = $resume['view']+1;
        $resume->save(false);
        $works = WorkForm::find()->where(['resumeId' => $_GET['id']])->all();
        $resume->setResume(true, $works);
        if (!empty($works)) {
            $time = WorkForm::allTime($works);
            foreach ($works as $work) $work->setWork();
        } else $time = 'Нет опыта работы';
        return $this->render('show-resume', compact('resume', 'works', 'time', 'back'));
    }

    public function actionNewResume()
    {
        $resume = new ResumeForm(['scenario' => ResumeForm::SCENARIO_NEW]);
        $resume['sex'] = 1;
        $resume['experience'] = 0;
        $resume['view'] = 0;
        return $this->render('new-resume', compact('resume'));
    }

    function mb_ucfirst($str) 
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

}
