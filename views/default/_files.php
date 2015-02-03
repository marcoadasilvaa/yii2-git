<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>

<?= GridView::widget([
        'id' => 'files-grid',
        'dataProvider' => $providerFiles,
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'hash_file',
                'value' => function ($data) {
                        return substr($data['hash_file'], 0,7);
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'name',
                //$icon = $list['type']=='blob'?"file":"folder";
                //<i class='git-".$icon."'></i>
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'type',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'mode',
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'size',
                'value' => function ($data) {
                    return $data["size"]!='-'?$data['size']." KB":"-";
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'format' => 'html',
                'attribute' => 'link',
                'value' => function ($data){
                        return implode(" ", $data["link"]);
                },
            ],
        ],
    ]); 
?>