<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2014 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */
return array(
    // 驅动程序
    'driver' => array('Simpleauth'),

    // 設定为 true 以允許多个登入
    'verify_multiple_logins' => true,

    // 出於安全原因，用你自己的鹽
    'salt' => '0916',
);
/**return array(
	'driver' => 'Simpleauth',
	'verify_multiple_logins' => false,
	'salt' => 'put_your_salt_here',
	'iterations' => 10000,
);*/
