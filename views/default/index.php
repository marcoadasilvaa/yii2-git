<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Repositories View';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'id' => 'git-grid',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
            ],
            [
                'attribute' => 'description',
                'format' => 'html',
            ],
            [
                'attribute' => 'author_datetime',
                'format' => ['datetime', 'php:Y-m-d H:M'],
                'label' => 'Datetime',
            ],
            [
                'attribute' => 'subject',
                'format' => 'html',
                'label' => 'Last Message',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {graph}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model["dir"]], ['title' => Yii::t('app', 'Summary')]);
                    },
                    'graph' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['graph', 'id' => $model["dir"]], ['title' => Yii::t('app', 'Graph')]);
                    }
                ],
            ],
        ],
    ]); 
