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
    	$rest = $git->getProyectList();
    	$provider = new ArrayDataProvider([
		    'allModels' => $rest,
    		'sort' => [
        		'attributes' => ['name', 'author_datetime'],
    		],
    		'pagination' => [
        		'pageSize' => 10,
    		],
		]);
        return $this->render('index',array('git'=>$git,'dataProvider'=>$provider));
    }
}
