<?php

//namespace app\modules\git\controllers;
namespace markmarco16\git\controllers;

use Yii;
use app\modules\git\components\Repository;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

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
