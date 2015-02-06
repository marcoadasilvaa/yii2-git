<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = $git->repository;
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Gráfica', ['graph', 'id' => $git->repository], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Estadisticas', ['stats', 'id' => $git->repository], ['class' => 'btn btn-success']) ?>
    <?= Html::a('Configuración', ['config', 'id' => $git->repository], ['class' => 'btn btn-success']) ?>
</p>
 
<h1><?= Html::encode($this->title) ?></h1>

<h2>Commits</h2>

    <?= GridView::widget([
        'id' => 'commit-grid',
        'dataProvider' => $providerRevList,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'h',
                'format' => 'html',
                'label' => 'Commit',
                'value' => function($data) {
                    return substr($data['h'], 0, 7) . $data['rev'];
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_datetime',
                'format' => ['datetime', 'php:Y-m-d H:M'],
                'label' => 'Datetime',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_name',
                'label' => 'Author Name',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'author_mail',
                'label' => 'Author Mail',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'subject',
                'label' => 'Subject',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) use ($git) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            ['commitview', 'id' => $git->repository, 'hash' => $model["h"]], 
                            ['title' => Yii::t('app', 'View commit ' . substr($model['h'], 0, 7))]);
                    },
                ],
            ],
        ],
    ]); 
?>

<h2>Tags</h2>

    <?= GridView::widget([
        'id' => 'tags-grid',
        'dataProvider' => $providerTags,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'tag',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'h',
                'format' => 'html',
                'label' => 'Commit',
                'value' => function($data){
                    return substr($data['h'], 0, 7);
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'datetime',
                'format' => ['datetime', 'php:Y-m-d H:M'],

            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'message_short',
                'format' => 'html',
                'label' => 'Message',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) use ($git) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            ['tagview', 'id' => $git->repository, 'tag' => $model["tag"]], 
                            ['title' => Yii::t('app', 'Detail of tag ' . $model["tag"])]);
                    },
                ],
            ],
        ],
    ]); 
?>

<h2>Branches</h2>

    <?= GridView::widget([
        'id' => 'branches-grid',
        'dataProvider' => $providerBranches,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'branch',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'active',
                'label' => 'Status',
                'value' => function($data){
                    return $data['active']?"Active":"";
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) use ($git) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            ['shortlog', 'id' => $git->repository, 'branch' => $model["branch"]], 
                            ['title' => Yii::t('app', 'Detail of brach ' . $model["branch"])]);
                    },
                ],
            ],
        ],
    ]); 
