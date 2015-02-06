<?php

use yii\helpers\Html;

$this->title = 'Graph';
$this->params['breadcrumbs'][] = ['label' => 'Repositories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $git->repository, 'url' => ['view', 'id' => $git->repository]];
$this->params['breadcrumbs'][] = $this->title;
?>

 
	<h1><?= Html::encode($this->title) ?></h1>

	<pre>
	<?php 
    	if (empty($graph)) {
    		echo 'No posee Commits para graficar';
    	} else {
    		echo $graph;
    	}
	?>
	</pre>