<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Commit ' . substr($hash, 0, 7);
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $git->repository, 'url' => ['view', 'id' => $git->repository]];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    
    echo yii\base\View::render('_commit', array('commit'=>$changed, 'git'=>$git));
    
    echo yii\base\View::render('_files', array('providerFiles'=>$providerFiles));

    if (!empty($files_change)) {
        echo yii\base\View::render('_diff', array('files_change'=>$files_change));
    }
