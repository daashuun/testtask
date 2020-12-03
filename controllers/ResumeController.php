<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Resume;
use app\models\SearchResume;
use app\models\ViewResume;
use yii\data\Pagination;
use yii\data\Sort;

class ResumeController extends Controller
{
    /**
     * Homepage
     */
    public function actionIndex()
    {
        $search = new SearchResume();
        $resumes = $this->actionSearch();
        return $this->render('index', compact('search', 'resumes'));
    }

    /**
     * Return the list of resume
     * after search, sort and filter
     */
    public function actionSearch() 
    {
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
        $search = new SearchResume();
        $resumes = $search->search(Yii::$app->request->get())->query->orderBy($sort->orders);
        $count = $resumes->count();
        $pages = new Pagination(['totalCount' => $resumes->count(), 'pageSize' => 5]);
        $resumes = $resumes->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        foreach ($resumes as $id => $r) {
            $view[$id] = new ViewResume($r, $r->work);
        }
        return $this->renderPartial('resume-list', compact('view', 'resumes', 'pages', 'count', 'sort'));
    }

    /**
     * Return page with resume 
     */
    public function actionShow()
    {
        $back = $_SERVER['HTTP_REFERER'];
        $resume = Resume::find()->where(['id' => Yii::$app->request->get('id')])->with('work')->one();
        $resume->view = $resume->view + 1;
        $resume->save(false);
        $view = new ViewResume($resume, $resume->work);
        return $this->render('show', compact('view', 'resume', 'back'));
    }

}
