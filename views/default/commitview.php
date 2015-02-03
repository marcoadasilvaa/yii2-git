<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Versión: '.substr($hash, 0, 7);
$this->params['breadcrumbs'][] = "Repositorios";
$this->params['breadcrumbs'][] = substr($git->repository, -4) == ".git"?substr($git->repository, 0,-4):$git->repository;
$this->params['breadcrumbs'][] = $this->title;
?>