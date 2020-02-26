<h2><?php echo __('Viewing')?> <span class='muted'>#<?php echo $message->name; ?></span></h2>


	<h3><?php echo __('Owner') ?>:</h3><?php echo $message->name; ?>

<h3><?php echo __('Message') ?>:</h3>
<ul>
	<pre><?php echo $message->messages; ?></pre>
</ul>
<h3><?php echo __('Comments') ?></h3>
<ul>
	<?php foreach ($comments as $comment): ?>
		
		
			<ui>
			<storong><?php echo __('Name') ?> : </storong><?php echo $comment->name;?></br>
			<storong><?php echo __('Comment') ?> :</storong><pre><?php echo $comment->comment;?></pre>
			<?php if ($comment->name == Auth::instance()->get_screen_name()) :?>
				<?php echo Html::anchor('comments/edit/' .$comment->id, __('Edit'));
					$confirminfo = __('Areyousure') ?>
				<?php echo Html::anchor('comments/delete/' .$comment->id, __('Delete'), array('onclick' => "return confirm('$confirminfo')"));?></br>
			<?php endif;?>
			</ui>
	<?php endforeach; ?>
</ul>


<p>
<?php 
	if (Auth::instance()->check())
	{
		echo Html::anchor('comments/create/'.$message->id, __('AddnewComment'), array("class"=>"btn btn-success"));
	}
?>
</p>

<?php 
	if ($message->name == Auth::instance()->get_screen_name())
		{
			echo Html::anchor('messages/edit/'.$message->id, __('Edit'));
			echo ' | ';
		}
	echo Html::anchor('messages', __('Back'));

?>




