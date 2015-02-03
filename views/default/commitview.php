<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Commit: '.substr($hash, 0, 7);

$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => substr($git->repository, -4) == ".git"?substr($git->repository, 0,-4):$git->repository, 'url' => ['view', 'id'=>$git->repository]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= DetailView::widget([
	'model' => $changed,
    'attributes' => [
        [                   
            'label' => 'Commit',
            'value' => $changed["h"],
        ],
        [
        	'label'=>'Ref',
            'format'=>'raw',
            'value'=>empty($changed["rev"])?"Nothing":strip_tags($changed["rev"], '<span>'),
        ],
        [                   
            'label' => 'Author Datetime',
            'value' => $changed["author_datetime"],
        ],
        [                   
            'label' => 'Author Name',
            'value' => $changed["author_name"],
        ],
        [                   
            'label' => 'Author Mail',
            'value' => $changed["author_mail"],
        ],
        [                   
            'label' => 'Committer Datetime',
            'value' => $changed["committer_datetime"],
        ],
        [                   
            'label' => 'Committer Name',
            'value' => $changed["committer_name"],
        ],
        [                   
            'label' => 'Committer Mail',
            'value' => $changed["committer_mail"],
        ],
        [                   
            'label'=>'Message',
            'format'=>'html',
            'name'=>'message',
            'value'=>'<p>'.$changed["message"].'</p>',
        ],
        [                   
            'label'=>'Files',
            'name'=>'tree',
            'format'=>'html',
            'value'=> Html::a('<span class="">'.$changed["tree"].'</span>', 
            	['tree', 'id' => $git->repository, "hash"=>$changed["h"], "tree"=>$changed["tree"]], 
            	['title' => Yii::t('app', 'Summary')]),
        ],
        [                   
            'label'=>'Parent(s)',
            'format'=>'html',
            'value'=>implode("<br>", $changed["parents"]),
        ],
    ],
]);

echo yii\base\View::render('_files', array('providerFiles'=>$providerFiles));

if (!empty($files_change)) {
    echo yii\base\View::render('_diff', array('files_change'=>$files_change));
} 
?>