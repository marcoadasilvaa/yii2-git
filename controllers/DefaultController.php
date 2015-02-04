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
    	$provider = new ArrayDataProvider([
		    'allModels' => $git->getRepositoriesList(),
    		'sort' => [
        		'attributes' => ['name', 'author_datetime'],
    		],
    		'pagination' => [
        		'pageSize' => 10,
    		],
		]);
        return $this->render('index', array('git'=>$git, 'dataProvider'=>$provider));
    }

    public function actionView($id)
    {
        $git = new Repository($id);
        $providerRevList = new ArrayDataProvider([
            'allModels' => $git->getRevList("--all", 0, 200),
            'sort' => [
                'attributes' => ['author_name', 'author_datetime'],
            ],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        $providerTags = new ArrayDataProvider([
            'allModels' => $git->getTags(),
            'sort' => [
                'attributes' => ['h','datetime'],
            ],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        $providerBranches = new ArrayDataProvider([
            'allModels' => $git->getBranches(),
            'sort' => [
                'attributes' => ['branch', 'active'],
            ],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('view',array('git' => $git, 'providerRevList' => $providerRevList, 'providerTags' => $providerTags, 'providerBranches' => $providerBranches));
    }

    public function actionGraph($id)
    {
        echo "Graph";
        die();
    }

    public function actionCommitview($id, $hash, $hash_file = null)
    {
        $git = new Repository($id);
        $changed = $git->getRevListHashDetail($hash);
        $providerFiles = new ArrayDataProvider([
            'allModels' => $git->getChangedPaths($hash),
            'sort' => [
                'attributes' => ['hash_file', 'name', 'type', 'mode', 'size', 'link'],
            ],
            'pagination' => false,
        ]);
        $files_change = null;
        if (!($hash_file == null)) {
                $files_change = $git->showDiffPath($hash, $hash_file);
        }
        return $this->render('commitview',array('git' => $git, 'hash' => $hash, 'changed' => $changed, 'files_change' => $files_change, 'providerFiles' => $providerFiles));
    }

    public function actionBlob($id,$hash,$hash_file)
    {
        $git = new Repository($id);
        $commit = $git->getRevListHashDetail($hash);
        $file = $git->showBlobFile($hash_file);
        return $this->render('blob', array('git' => $git, 'hash' => $hash, 'hash_file' => $hash_file, 'file' => $file, 'commit' => $commit));
    }

    public function actionTree($id, $hash, $tree)
    {
        $git = new Repository ($id);
        $commit = $git->getRevListHashDetail($hash);
        $providerFiles = new ArrayDataProvider([
            'allModels' => $git->getTree($tree,$hash),
            'sort' => [
                'attributes' => ['hash_file', 'name', 'type', 'mode', 'size', 'link'],
            ],
            'pagination' => false,
        ]);
        return $this->render('tree', array('git' => $git, 'hash' => $hash, 'commit' => $commit, 'providerFiles' => $providerFiles));
    }
}