<h2><?php echo __('Editing') ?> <span class='muted'><?php echo __('Message') ?></span></h2>
<br>

<?php echo render('messages/_form'); ?>
<p>
	<?php echo Html::anchor('messages/view/'.$message->id, __('View')); ?> |
	<?php echo Html::anchor('messages', __('Back')); ?>
</p>
