<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = substr($git->repository, -4) == ".git"?substr($git->repository, 0,-4):$git->repository;
$this->params['breadcrumbs'][] = "Repositorios";
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
<span><?= Html::a('Gráfica', ['graph'], ['graph', 'id' => $git->repository],['class' => 'btn btn-success']) ?></span>
<span><?= Html::a('Estadisticas', ['stats'], ['stats', 'id' => $git->repository],['class' => 'btn btn-success']) ?></span>
<span><?= Html::a('Configuración', ['config'], ['config', 'id' => $git->repository],['class' => 'btn btn-success']) ?></span>
</p>
 
<h1><?= Html::encode($this->title) ?></h1>

<h2>Versiones</h2>

    <?= GridView::widget([
        'id' => 'git-grid',
        'dataProvider' => $providerRevList,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'h',
                'format' => 'html',
                'label' => 'Código Versión',
                "value" => function($data){
                    return substr($data['h'], 0, 7).$data['rev'];
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_datetime',
                'format' => ['datetime', 'php:m/d/Y H:i:s'],
                'label' => 'Fecha',

            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_name',
                'label' => 'Autor',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_mail',
                'label' => 'Correo',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'subject',
                'label' => 'Descripción',
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        //return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['commit', 'id' => $git->repository, 'hash' => $model["h"]], ['title' => Yii::t('app', 'Detail')]);
                    },
                ],
            ],
        ],
    ]); 
?>