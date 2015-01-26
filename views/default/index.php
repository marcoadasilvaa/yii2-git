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
    ]); ?>


 
<?php 
/*
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'git-grid',
    'dataProvider'=>$DataProvider,
    'columns'=>array(
        array(
                'header'=>'Aplicación',
                'name'=>'name',
            'type'  => 'raw',
            'value' => "CHtml::link(\$data[\"name\"],array('aplicacion/view','id'=>\$data[\"name\"]))",
        ),
        array(
                'header'=>'Descripción',
                'name'=>'description',
            'value'=>'$data["description"]',
        ),
        array(
                'header'=>'Último Cambio',
                'name'=>'author_datetime',
            'value'=>'$data["author_datetime"]',
            'type'=>'datetime',
            'headerHtmlOptions'=>array('width'=>'75px'),
        ),
        array(
            'header'=>'Último Mensaje',
            'name'=>'subject',
            'type'=>'html',
            'value'=>'$data["subject"]',
        ),
        array(
                        'class'=>'CButtonColumn',
                        'template'=>'{view} {summary} {version}',
                        'buttons' => array(
                                'view'=>array(
                                        'label'=>'Ver resumen del repositorio',
                    'url'=>'Yii::app()->createUrl("repositorio/summary",array("id"=>$data["dir"]))',
                                ),
                                'summary'=>array(
                                        'label'=>'Ver grafica del repositorio',
                                'url'=>'Yii::app()->createUrl("repositorio/graph",array("id"=>$data["dir"]))',
                                'imageUrl'=>Yii::app()->theme->baseUrl.'/img/MGit/chart_organisation.png',
                                ),
                'version'=>array(
                    'label'=>'Realizar versión',
                    'url'=>'Yii::app()->createUrl("repositorio/commit",array("id"=>$data["dir"]))',
                    'imageUrl'=>Yii::app()->theme->baseUrl.'/img/MGit/commit_diff.png',
                ),
                        ),
                ),
        ),
));
*/?>
