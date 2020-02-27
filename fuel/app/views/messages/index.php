<h2><?php echo __('Listing') ?> <span class='muted'><?php echo __('Messages') ?></span></h2>
<br>
<?php if($messages): ?>
	
	<ul>
		<?php foreach($messages as $message): ?>
			<li><?php echo $message->name; 	?>
				<ul>
				
					<li><?php echo $message->messages; ?></li>
					<li><?php echo Html::anchor('messages/view/'.$message->id, $comment_links[$message->id]); ?></li>
					
					<?php if ($message->name == Auth::instance()->get_screen_name()) :?>
						<li><?php echo Html::anchor('messages/edit/'.$message->id, __('edit')); 
							$confirminfo = __('Areyousure')?></li>
						<li><?php echo Html::anchor('messages/delete/'.$message->id, __('Delete'), array('onclick' => "return confirm('$confirminfo')")); ?></li>				
					<?php endif;?>

				</ul>
			</li>
		<?php endforeach;  ?>
	</ul>
<?php else: ?>	
	
	<p><?php echo __('NoMessages') ?>

<?php endif; ?>
<p>
	<?php 
		if( !Auth::check())
		{	
			echo Html::anchor("messages/create", __('Logintoaddmessages'), array("class" => "btn btn-success",'disabled' => 'disabled')); 
		}else{
			echo Html::anchor("messages/create", __('AddnewMessage'), array("class" => "btn btn-success")); 
		}
	
	?>
	
</p>

