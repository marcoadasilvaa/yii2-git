<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Rama ' . $branch;
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $git->repository, 'url' => ['view', 'id' => $git->repository]];
$this->params['breadcrumbs'][] = $this->title;
?>

	 <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'id' => 'commit-branch-grid',
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
                'label' => 'Author Datetime',
                'attribute' => 'author_datetime'
            ],
            [                   
                'label' => 'Author Name',
                'attribute' => 'author_name'
            ],
            [                   
                'label' => 'Author Mail',
                'attribute' => 'author_mail'
            ],
            [                   
                'label' => 'Message',
                'format' => 'html',
                'attribute' => 'message',
                'value' => function($data) {
                    return '<p>' . $data["message"] . '</p>';
                }
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
