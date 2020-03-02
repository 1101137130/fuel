<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>

	<select id="language_dropdown" name ='language_dropdown'>
		<option selected="selected">Language</option>
		<option value = 'en-us.yml' >English</option>
		<option value = 'zh-tw.yml'>繁體中文</option>
	</select>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(function(){

			$('#language_dropdown').change(function() {
				if( $(this).val()=='Language'){

				}else{
                    	var val = $(this).val();
                                              $.ajax({
                        type: "POST",
                        url:  "<?php echo Uri::base(false) ?>messages/lang",
						data: { 'val' : val },
                        success: function(response){
							//alert($(this)[0].selectedIndex);
							<?php Lang::load(Session::get('lang'))?>
								location.reload();
                          },
                           error: function(response){
                           alert("There is some problem, please try again later");
                           }

                          });

				}

                    });
					$('#form_username').on('keypress', function(e) {
				if(e.which == 32){
					console.log('pressed space');
					alert('Can not use space!');
					document.getElementById("form_username").value ='';
				}
			});
		});

	</script>
	<?php echo Asset::css('bootstrap.css'); ?>
	<style>
		body { margin: 40px; }
	</style>
</head>
<body>
	<div class="container">
		<div class="col-md-12">
			<h1><?php echo $title; ?></h1>
			<hr>
			<?php
if (isset($user_info)) {
    echo $user_info;
} else {
    if (Auth::instance()->check()) {
        $link = array(__('Loggedinas') . Auth::instance()->get_screen_name(), Html::anchor('users/logout', __('Logout')), Html::anchor('users/manage', __('manage')));
    } else {
        $link = array(Html::anchor('users/login', __('login')), Html::anchor('users/register', __('register')));
    }
    echo Html::ul($link);
}
?>
<?php if (Session::get_flash(__('success'))): ?>
			<div class="alert alert-success">
				<strong><?php echo __('success') ?></strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash(__('success')))); ?>
				</p>
			</div>
<?php endif;?>
<?php if (Session::get_flash(__('error'))): ?>
			<div class="alert alert-danger">
				<strong>Error</strong>
				<p>
				<?php echo implode('</p><p>', e((array) Session::get_flash(__('error')))); ?>
				</p>
			</div>
<?php endif;?>
		</div>
		<div class="col-md-12">
<?php echo $content; ?>
		</div>
		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>
				<a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.<br>
				<small>Version: <?php echo e(Fuel::VERSION); ?></small>
			</p>
		</footer>
	</div>
</body>
</html>
