<?php

namespace app\controllers;

use Yii;
use app\models\Resume;
use app\models\Work;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use app\Models\SearchResume;
use app\Models\ViewResume;

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
        $resume = $resume->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        foreach ($resume as $id=>$r) {
            $resumes[$id] = new ViewResume($r, $r->work);
        };
        return $this->renderPartial('resume-list', compact('resumes', 'pages', 'count', 'sort'));
    }

    public function actionShow()
    {
        $back = $_SERVER['HTTP_REFERER'];
        $r = Resume::find()->where(['id' => $_GET['id']])->with('work')->one();
        $r['view'] = $r['view']+1;
        $r->save();
        $resume = new ViewResume($r, $r->work);
        return $this->render('show', compact('resume', 'back'));
    }

}
