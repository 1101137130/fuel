<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
// MySQL 驅动程序配置
'development' => array(
    'type'           => 'mysqli',
    'connection'     => array(
        'hostname'       => '127.0.0.1',
        'port'           => '3306',
        'database'       => 'fuel_db',
        'username'       => 'root',
        'password'       => 'root',
        'persistent'     => false,
        'compress'       => false,
    ),
    'identifier'     => '`',
    'table_prefix'   => '',
    'charset'        => 'utf8',
    'enable_cache'   => true,
    'profiling'      => false,
    'readonly'       => false,
),

// PDO 驅动程序配置，使用 PostgreSQL
'production' => array(
    'type'           => 'pdo',
    'connection'     => array(
        'dsn'            => 'pgsql:host=127.0.0.1:3306;dbname=fuel_db',
        'username'       => 'root',
        'password'       => 'root',
        'persistent'     => false,
        'compress'       => false,
    ),
    'identifier'     => '"',
    'table_prefix'   => '',
    'charset'        => 'utf8',
    'enable_cache'   => true,
    'profiling'      => false,
    'readonly'       => array('slave1', 'slave2', 'slave3'),
),

'slave1' => array(
    // 第一生產唯讀 slave db 的配置
),

'slave2' => array(
    // 第二生產唯讀 slave db 的配置
),

'slave3' => array(
    // 第三生產唯讀 slave db 的配置
),

);
