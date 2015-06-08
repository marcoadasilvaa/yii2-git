<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
?>

    <?= DetailView::widget([
    	'model' => $commit,
        'attributes' => [
            [                   
                'label' => 'Commit',
                'attribute' => 'h'
            ],
            [
            	'label' => 'Ref',
                'format' => 'raw',
                'value' => empty($commit["rev"])?"Nothing":$commit["rev"],
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
                'value' => '<p>' . $commit["message"] . '</p>',
            ],
            [                   
                'label' => 'Files',
                'attribute' => 'tree',
                'format' => 'html',
                'value' => Html::a('<span class="">' . $commit["tree"] . '</span>', 
                	['tree', 'id' => $git->repository, "hash" => $commit["h"], "tree" => $commit["tree"]], 
                	['title' => Yii::t('app', 'Files')]),
            ],
            [                   
                'label' => 'Parent(s)',
                'format' => 'html',
                'value' => implode("<br>", $commit["parents"]),
            ],
        ],
    ]);