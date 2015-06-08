<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Archivo '.substr($hash_file, 0, 7);
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $git->repository, 'url' => ['view', 'id' => $git->repository]];
$this->params['breadcrumbs'][] = ['label' => 'Commit ' . substr($hash, 0, 7), 'url' => ['commitview', 'id' => $git->repository, 'hash' => $hash]];
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php
        echo yii\base\View::render('_commit', array('commit'=>$commit, 'git'=>$git));
    ?>

    <div class="git-source-view">
    	<div class="git-source-header">
    		<div class="meta"><?php echo $file['name']; ?></div>
    		<div class="btn-group pull-right">Download</div>
    	</div>
    	<div>
            <table>
            	<tbody>
                
                <?php $lineNum = 1;
                    foreach ($file['contents'] as $item) {
                    	echo "<tr><td class='lineNo'>$lineNum</td><td style='width: 100%;'><pre>".htmlspecialchars($item)."</pre></td></tr>"; 
                        $lineNum++;
    				}
    			?>
                
                </tbody>
    		</table>
    	</div>
    </div>