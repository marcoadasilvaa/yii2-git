<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Commit ' . substr($hash, 0, 7);
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $git->repository, 'url' => ['view', 'id' => $git->repository]];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
    	'model' => $changed,
        'attributes' => [
            [                   
                'label' => 'Commit',
                'attribute' => 'h'
            ],
            [
            	'label' => 'Ref',
                'format' => 'raw',
                'value' => empty($data["rev"])?"Nothing":strip_tags($data["rev"], '<span>'),
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
                'label' => 'Committer Datetime',
                'attribute' => 'committer_datetime'
            ],
            [                   
                'label' => 'Committer Name',
                'attribute' => 'committer_name'
            ],
            [                   
                'label' => 'Committer Mail',
                'attribute' => 'committer_mail'
            ],
            [                   
                'label' => 'Message',
                'format' => 'html',
                'attribute' => 'message',
                'value' => '<p>' . $data["message"] . '</p>',
            ],
            [                   
                'label' => 'Files',
                'attribute' => 'tree',
                'format' => 'html',
                'value' => Html::a('<span class="">' . $data["tree"] . '</span>', 
                	['tree', 'id' => $git->repository, "hash" => $data["h"], "tree" => $data["tree"]], 
                	['title' => Yii::t('app', 'Files')]),
            ],
            [                   
                'label' => 'Parent(s)',
                'format' => 'html',
                'value' => implode("<br>", $data["parents"]),
            ],
        ],
    ]);

    echo yii\base\View::render('_files', array('providerFiles'=>$providerFiles));

    if (!empty($files_change)) {
        echo yii\base\View::render('_diff', array('files_change'=>$files_change));
    }
