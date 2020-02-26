
<h2><?php echo __('EditingComment') ?></h2>
<br/>
<?php $message = isset($message) ? $message : ''; ?>
<?php echo $form; ?>
<p><?php echo Html::anchor('messages/view'. $comment->message_id, __('Back'));?></p>