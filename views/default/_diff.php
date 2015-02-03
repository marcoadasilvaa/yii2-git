<div class="source-view">
	<div class="source-header">
		<div class="meta"><?php echo $files_change['name'];?></div>
			<div class="btn-group pull-right"></div>
	</div>
	<div>
		<table>
        	<tbody>
            	<tr>
            		<td colspan='3'><?php echo $files_change['info']; ?></td>
            	</tr>

				<?php if (array_key_exists('contents', $files_change)) {
					foreach ($files_change['contents'] as $item) { ?>
						<tr>
                        	<td class="lineNo"><?php echo $item['lineNumOld']; ?></td>
                        	<td class="lineNo"><?php echo $item['lineNumNew']; ?></td>
                        	<td style="width: 100%;"><?php echo $item['lineCode']; ?></td>
                       	</tr>
               	<?php   } 
				} ?>
				
            </tbody>
		</table>
	</div>
</div>