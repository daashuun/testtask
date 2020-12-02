<?php

namespace app\controllers;

use Yii;
use app\models\Resume;
use app\Models\ViewResume;
use app\models\Work;
use yii\helpers\Helper;
use yii\web\Controller;
use yii\helpers\Html;

class MyResumeController extends Controller
{
    public function actionIndex()
    {
        $resume = Resume::find()->with('work')->all();
        foreach ($resume as $r) {
            if (!empty($r)) {
                $resumes[] = new ViewResume($r, $r->work);
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
        $name = Helper::changeImg($foto);
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
            $resume = Resume::find()->where(['id' => Yii::$app->request->post('Resume')['id']])->one();
            $oldPhoto = $resume['photo'];
            Work::deleteAll(['resumeId' => $resume['id']]);
        } else {
            $resume = new Resume();
        }
        $resume->load(Yii::$app->request->post('Resume'), '');
        Helper::saveImg($resume['photo'], $oldPhoto);
        $resume['name'] = $this->mb_ucfirst(mb_strtolower($resume['name'], 'UTF-8'));
        $resume['surname'] = $this->mb_ucfirst(mb_strtolower($resume['surname'], 'UTF-8'));
        $resume['middlename'] = $this->mb_ucfirst(mb_strtolower($resume['middlename'], 'UTF-8'));
        $resume['birtday'] = date("Y.m.d", strtotime($resume['birtday']));
        $resume['changed'] = date("Y.m.d G:i:s");
        $resume['schedule'] = json_encode($resume['schedule']);
        $resume['employment'] = json_encode($resume['employment']);
        $resume['about'] = Html::encode($resume['about']);
        $resume->save(false);
        $works = Yii::$app->request->post('Work');
        if ($works) {
            foreach ($works as $id=>$w) {
                if ($w) {
                    $work = new Work();
                    $work->load($w, '');
                    $work->setWorkingTime();
                    $work['position'] = $this->mb_ucfirst(mb_strtolower($work['position'], 'UTF-8'));
                    $work['duties'] = Html::encode($work['duties']);
                    $work['resumeId'] = $resume['id'];
                    $work->save(false);
                }
            }
            $resume->sortWorks();
            $resume->setExp();
        } else $resume['exp'] = 4;
        return $this->redirect('index');
    }

    public function actionEdit($id)
    {
        $resume = Resume::find()->with('work')->where(['id' => $id])->one();
        $resume['about'] = Html::decode($resume['about']);
        $resume['employment'] = json_decode($resume['employment']);
        $resume['schedule'] = json_decode($resume['schedule']);
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
