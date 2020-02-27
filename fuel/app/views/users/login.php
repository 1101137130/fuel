<!-- <ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "login" ); ?>'><?php echo Html::anchor('users/login',__('login'));?></li>
	<li class='<?php echo Arr::get($subnav, "logout" ); ?>'><?php if( Auth::check()){echo Html::anchor('users/logout', __('Logout'));}?></li>
	<li class='<?php echo Arr::get($subnav, "register" ); ?>'><?php echo Html::anchor('users/register', __('register'));?></li>
</ul> -->
<?php if (isset($errors)){echo $errors;} ?>
<?php echo $reg;?>
<p><?php echo Html::anchor('messages', __('Back')); ?></p>