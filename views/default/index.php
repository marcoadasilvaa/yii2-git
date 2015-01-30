<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Visualización de Repositorios';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'id' => 'git-grid',
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'name',
                'format' => 'raw',
                'label' => 'Nombre',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'description',
                'format' => 'html',
                'label' => 'Descripción',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_datetime',
                'format' => ['datetime', 'php:m/d/Y H:i:s'],
                'label' => 'Fecha',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'subject',
                'format' => 'html',
                'label' => 'Último Mensaje',
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {summary}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model["dir"]], ['title' => Yii::t('app', 'Summary')]);
                    },
                    'summary' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['graph', 'id' => $model["dir"]], ['title' => Yii::t('app', 'Summary')]);
                    }
                ],
            ],
        ],
    ]); 
?>