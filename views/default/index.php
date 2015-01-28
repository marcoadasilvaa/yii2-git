<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Visualización de Repositorios';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('No se que poner', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                        ]);
                    },
                    'summary' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'Summary'),
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url ='git/view?id='.$model["dir"];
                        return $url;
                    }
                    elseif ($action === 'summary') {
                        $url ='git/summary?id='.$model["dir"];
                        return $url;
                    }
                }
            ],
        ],
    ]); 
?>