<?php

namespace markmarco16\git\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use markmarco16\git\components\Repository;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $git = new Repository();
        var_dump($git->getRepositoriesList());
    	$provider = new ArrayDataProvider([
		    'allModels' => $git->getRepositoriesList(),
    		'sort' => [
        		'attributes' => ['name', 'author_datetime'],
    		],
    		'pagination' => [
        		'pageSize' => 10,
    		],
		]);
        return $this->render('index',array('git'=>$git,'dataProvider'=>$provider));
    }

    public function actionView($id)
    {
        $git = new Repository($id);
        $providerRevList = new ArrayDataProvider([
            'allModels' => $git->getRevList("--all",0,200),
            'sort' => [
                'attributes' => ['author_name', 'author_datetime'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('view',array('git'=>$git,'providerRevList'=>$providerRevList));
    }

    public function actionGraph($id)
    {
        echo "Graph";
        die();
    }

    public function actionCommit($id,$hash)
    {
        echo "Commit";
        die();
    }
}