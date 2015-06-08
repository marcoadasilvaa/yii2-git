<?php 

use yii\helpers\Html;
?>

<div class="git-source-view">
	<div class="git-source-header">
		<div class="meta"><?php echo $files_change['name'];?></div>
		<div class="btn-group pull-right">
			<?php 
				echo Html::a('Blade', ['blade', 'id' => ""], ['class' => ""]); 
				echo Html::a('Download', ['download', 'id' => ""], ['class' => ""]);
			?>
		</div>
	</div>
	<div>
		<table>
       		<tbody>
           		<tr>
           			<td colspan='3'><?php echo $files_change['info']; ?></td>
           		</tr>
					<?php 
						if (array_key_exists('contents', $files_change)) {
							foreach ($files_change['contents'] as $item) { 
					?>
								<tr>
               		        		<td class="lineNo"><span><?php echo $item['lineNumOld']; ?></span></td>
               		        		<td class="lineNo"><span><?php echo $item['lineNumNew']; ?></span></td>
               		        		<td style="width: 100%;"><span><?php echo $item['lineCode']; ?></span></td>
               		       		</tr>
              		<?php   
              				} 
						} 
					?>
           	</tbody>
		</table>
	</div>
</div>