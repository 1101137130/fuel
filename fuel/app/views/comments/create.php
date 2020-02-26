
<?php $message = isset($message) ? $message : '';?>
<h2 class="first"><?php echo __('New') ?><?php echo __('Comment') ?> </h2>
<?php echo $form; ?>
<p><?php echo Html::anchor('messages/view/'.$message, __('Back')); ?></p>
