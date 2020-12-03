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
    /**
     * List of my resumes
     */
    public function actionIndex()
    {
        $resumes = Resume::find()->with('work')->all();
        foreach ($resumes as $r) {
            if (!empty($r)) {
                $view[] = new ViewResume($r, $r->work);
            }
        }
        return $this->render('index', compact('view', 'resumes'));
    }

    /**
     * Render work form for 
     * new-resume page
     */
    public function actionWork($form = 0, $works = [])
    {
        if (Yii::$app->request->isAjax) {
            $exp = new Work();
            $id = Yii::$app->request->post('id');
            return $this->renderPartial('work', compact('exp', 'id'));
        }
        foreach ($works as $id => $exp) {
            echo $this->renderPartial('work', compact('exp', 'id', 'form'));
        }
    }

    /**
     * Action for change image for
     * new-resume page
     */
    public function actionChange()
    {
        $foto = $_FILES;
        $name = Helper::changeImg($foto);
        return $name;
    }

    /**
     * Action delete resume and
     * works from databases by 
     * get patameter id
     */
    public function actionDelete() 
    {
        $resume = Resume::find()->where(['id' => Yii::$app->request->post('id')])->one();
        if (file_exists('images/photo/'.$resume->photo)) {
            unlink ('images/photo/'.$resume->photo);
        }
        Resume::deleteAll(['id' => Yii::$app->request->post('id')]);
        return;
    }

    /**
     * Action save resume and works
     * and redirect to my-resume action
     */
    public function actionSave()
    {
        if (Yii::$app->request->post('Resume')['id']) {
            $resume = Resume::find()->where(['id' => Yii::$app->request->post('Resume')['id']])->one();
            $oldPhoto = $resume->photo;
            Work::deleteAll(['resumeId' => $resume->id]);
        } else {
            $resume = new Resume();
        }
        $resume->load(Yii::$app->request->post('Resume'), '');
        Helper::saveImg($resume->photo, $oldPhoto);
        $resume->name = Helper::mb_ucfirst($resume->name);
        $resume->surname = Helper::mb_ucfirst($resume->surname);
        $resume->middlename = Helper::mb_ucfirst($resume->middlename);
        $resume->birtday = date("Y.m.d", strtotime($resume->birtday));
        $resume->changed = date("Y.m.d G:i:s");
        $resume->schedule = json_encode($resume->schedule);
        $resume->employment = json_encode($resume->employment);
        $resume->about = Html::encode($resume->about);
        $resume->save(false);
        $w = Yii::$app->request->post('Work');
        if ($w) {
            foreach ($w as $id => $work) {
                if ($w) {
                    $works[$id] = new Work();
                    $works[$id]->load($work, '');
                    $works[$id]->setWorkingTime();
                    $works[$id]->duties = Html::encode($works[$id]->duties);
                    $works[$id]->resumeId = $resume->id;
                }
            }
            $works = Helper::sortWorks($works);
            $resume->exp = Helper::setExp($works);
            foreach ($works as $work) {
                $work->save(false);
            }
        } else {
            $resume->exp = 4;
        }
        $resume->save(false);
        return $this->redirect('index');
    }

    /**
     * Action for edit resume
     */
    public function actionEdit($id)
    {
        $resume = Resume::find()->with('work')->where(['id' => $id])->one();
        $resume->about = Html::decode($resume->about);
        $resume->employment = json_decode($resume->employment);
        $resume->schedule = json_decode($resume->schedule);
        return $this->render('new', compact('resume'));
    }

    /**
     * Action for create resume
     */
    public function actionNew()
    {
        $resume = new Resume(['scenario' => Resume::SCENARIO_NEW]);
        $resume->sex = 1;
        $resume->experience = 0;
        $resume->view = 0;
        return $this->render('new', compact('resume'));
    }

}
