<?php

use yii\helpers\Html;
use yii\grid\GridView;
?>

    <?= GridView::widget([
        'id' => 'files-grid',
        'dataProvider' => $providerFiles,
        'columns' => [
            [
                'attribute' => 'hash_file',
                'value' => function ($data) {
                    return substr($data['hash_file'], 0, 7);
                }
            ],
            [
                'attribute' => 'name',
                //$icon = $list['type']=='blob'?"file":"folder";
                //<i class='git-".$icon."'></i>
            ],
            [
                'attribute' => 'type',
            ],
            [
                'attribute' => 'mode',
            ],
            [
                'attribute' => 'size',
                'value' => function ($data) {
                    return $data["size"] != '-'?$data['size'] . " KB":"-";
                }
            ],
            [
                'format' => 'html',
                'attribute' => 'link',
                'value' => function ($data){
                    return implode(" ", $data["link"]);
                }
            ],
        ],
    ]); 
