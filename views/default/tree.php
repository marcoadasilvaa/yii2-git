<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Tree';
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => substr($git->repository, -4) == ".git"?substr($git->repository, 0,-4):$git->repository, 'url' => ['view', 'id'=>$git->repository]];
$this->params['breadcrumbs'][] = ['label' => 'Commit: '.substr($hash, 0, 7), 'url' =>['commitview', 'id'=>$git->repository, 'hash'=>$hash]];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $commit,
        'attributes' => [
            [                   
                'label' => 'Commit',
                'value' => $commit["h"],
            ],
            [                   
                'label' => 'Ref',
                'format' => 'html',
                'value'=> empty($commit["rev"])?"Nothing":strip_tags($commit["rev"], '<span>'),
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
                'name' => 'message',
                'value' => '<p>'.$commit["message"].'</p>',
            ],
            [                   
                'label' => 'Files',
                'name' => 'tree',
                'format' => 'html',
                'value' => Html::a('<span class="">'.$commit["tree"].'</span>', 
                    ['tree', 'id' => $git->repository, "hash"=>$commit["h"], "tree"=>$commit["tree"]], 
                    ['title' => Yii::t('app', 'Summary')]),
            ],
            [                   
                'label' => 'Parent(s)',
                'format' => 'html',
                'value' => implode("<br>", $commit["parents"]),
            ],
        ],
    ]);
 
    echo yii\base\View::render('_files', array('providerFiles' => $providerFiles)); 
?>