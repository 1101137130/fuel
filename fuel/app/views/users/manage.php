
<?php if (isset($errors)) {echo $errors;}?>

<?php
echo __('Username :') . '<pre>' . $username . '</pre>';
echo 'Email : ' . '<pre>' . $email . '</pre>';
echo Html::anchor('users/edit/', __('ChangeEmail')) . '</br> ' . Html::anchor('users/chpass/', __('ChangePassword'));

?>
<p><?php echo Html::anchor('messages', __('Back')); ?></p>