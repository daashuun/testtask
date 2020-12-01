<?php

namespace app\controllers;

use Yii;
use app\models\Resume;
use app\models\Work;
use yii\web\Controller;
use yii\helpers\Html;

class MyResumeController extends Controller
{
    public function actionIndex()
    {
        $resumes = Resume::find()->with('work')->all();
        foreach ($resumes as $resume) {
            if (!empty($resume)) {
                $resume->setResume(true, $resume->work);
            }
        }
        return $this->render('index', compact('resumes'));
    }

    public function actionWork($form = 0, $works = [])
    {
        if (Yii::$app->request->isAjax) {
            $exp = new Work();
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
        $name = Resume::changeImg($foto);
        return $name;
    }

    public function actionDelete() 
    {
        $foto = Resume::find()->select('photo')->where(['id' => Yii::$app->request->post('id')])->one();
        if (file_exists('images/photo/'.$foto['photo']))
            unlink ('images/photo/'.$foto['photo']);
        Resume::deleteAll(['id' => Yii::$app->request->post('id')]);
        return;
    }

    public function actionSave()
    {
        if (Yii::$app->request->post('Resume')['id']) {
            $resume = Resume::findOne(Yii::$app->request->post('Resume')['id']);
        } else {
            $resume = new Resume();
        }
        $resume->load(Yii::$app->request->post('Resume'), '');
        Work::deleteAll(['resumeId'=>$resume['id']]);
        $foto = Resume::find()->select('photo')->where(['id' => $resume['id']])->one();
        if (file_exists('images/photo/'.$foto['photo']))
            unlink ('images/photo/'.$foto['photo']);
        Resume::saveImg($resume['photo']);
        $resume['name'] = $this->mb_ucfirst(mb_strtolower($resume['name'], 'UTF-8'));
        $resume['surname'] = $this->mb_ucfirst(mb_strtolower($resume['surname'], 'UTF-8'));
        $resume['middlename'] = $this->mb_ucfirst(mb_strtolower($resume['middlename'], 'UTF-8'));
        $resume['birtday'] = date("Y.m.d", strtotime($resume['birtday']));
        $resume['changed'] = date("Y.m.d G:i:s");
        $resume['schedule'] = json_encode($resume['schedule']);
        $resume['employment'] = json_encode($resume['employment']);
        $w = Yii::$app->request->post('Work');
        if ($w) {
            foreach ($w as $id=>$work) {
                if ($work) {
                    $works[$id] = new Work();
                    $works[$id]->load($work, '');
                }
            }
            $works = Work::sortWorks($works);
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
        return $this->redirect('index');
    }

    public function actionEdit($id)
    {
        $resume = Resume::find()->with('work')->where(['id' => $id])->one();
        $resume['about'] = Html::decode($resume['about']);
        $resume->setResume(false);
        return $this->render('new', compact('resume'));
    }

    public function actionNew()
    {
        $resume = new Resume(['scenario' => Resume::SCENARIO_NEW]);
        $resume['sex'] = 1;
        $resume['experience'] = 0;
        $resume['view'] = 0;
        return $this->render('new', compact('resume'));
    }

    function mb_ucfirst($str) 
    {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

}
