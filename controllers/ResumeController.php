<?php

namespace app\controllers;

use Yii;
use app\models\Resume;
use app\models\Work;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use app\Models\SearchResume;

class ResumeController extends Controller
{
    public function actionIndex()
    {
        $search = new SearchResume();
        
        $resumes = $this->actionSearch();
        return $this->render('index', compact('search', 'resumes'));
    }

    public function actionSearch() 
    {
        $search = new SearchResume();
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
        $resume = $search->search(Yii::$app->request->get())->query->orderBy($sort->orders);
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
        return $this->renderPartial('resume-list', compact('resumes', 'pages', 'count', 'sort'));
    }

    public function actionShow()
    {
        $back = $_SERVER['HTTP_REFERER'];
        $resume = Resume::find()->where(['id' => $_GET['id']])->one();
        $resume['view'] = $resume['view']+1;
        $resume->save(false);
        $works = Work::find()->where(['resumeId' => $_GET['id']])->all();
        $resume->setResume(true, $works);
        if (!empty($works)) {
            $time = Work::allTime($works);
            foreach ($works as $work) $work->setWork();
        } else $time = 'Нет опыта работы';
        return $this->render('show', compact('resume', 'works', 'time', 'back'));
    }

}
