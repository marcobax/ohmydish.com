<?php
declare(strict_types = 1);
ini_set('session.gc_maxlifetime', '604800');
session_start();

// Use X-Forwarded-For HTTP Header to Get Visitor's Real IP Address
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
    $_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
}

$caching_enabled = false;
/**
 * Simple MVC application for our food blog.
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
switch ($_SERVER['HTTP_HOST']) {
    case "ohmydish.com.test":
        $env = 'dev';
        break;
    case "test.ohmydish.com":
        $env = 'test';
        break;
    default:
        //$caching_enabled = true;
        $env = "prod";
        break;
}

define('ENV', $env);

// if (is_array($_SESSION) && array_key_exists('logged_in', $_SESSION) && true === $_SESSION['logged_in']) {
//     $caching_enabled = false;
// }
define('CACHE_DIR', __DIR__ . '/../cache/');

ob_start('ob_gzhandler');

if (false === $caching_enabled) {
    require __DIR__ . '/../vendor/autoload.php';
}

switch(ENV) {
    case 'dev':
        ini_set('display_startup_errors', '1');
        ini_set('display_errors', '1');
        error_reporting(E_ALL);

        define('DOMAIN_NAME', 'http://ohmydish.com.test');
        define('PUBLIC_DIR', 'public');
        define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
        define('ROOT', str_replace(PUBLIC_DIR . '/index.php', '', $_SERVER['SCRIPT_FILENAME']));
        define('ASSETS_DIR', 'assets');
        define('UPLOADS_DIR', PUBLIC_DIR . '/uploads');
        define('DEFAULT_IMAGE', DOMAIN_NAME . '/wp-content/uploads/2019/09/Dutch-plum-pie.jpg');
        define('FORUM_ENABLED', true);
        define('ADMIN_THUMBNAIL_DIR', ROOT . 'public/cache/admin_thumbnails');
        break;
    case 'test':
        define('CACHING_ENABLED', false);
        define('DOMAIN_NAME', 'https://test.ohmydish.com');
        define('PUBLIC_DIR', 'public');
        define('WEBROOT', str_replace(PUBLIC_DIR . '/index.php', '', $_SERVER['SCRIPT_NAME']));
        define('ROOT', str_replace(PUBLIC_DIR . '/index.php', '', $_SERVER['SCRIPT_FILENAME']));
        define('ASSETS_DIR', PUBLIC_DIR . '/assets');
        define('UPLOADS_DIR', PUBLIC_DIR . '/uploads');
        define('DEFAULT_IMAGE', DOMAIN_NAME . '/wp-content/uploads/2019/09/Dutch-plum-pie.jpg');
        define('FORUM_ENABLED', true);
        define('ADMIN_THUMBNAIL_DIR', ROOT . 'public/cache/admin_thumbnails');
        break;
    default:
        define('CACHING_ENABLED', $caching_enabled);
        define('DOMAIN_NAME', 'https://ohmydish.com');
        define('PUBLIC_DIR', 'public');
        define('WEBROOT', str_replace(PUBLIC_DIR . '/index.php', '', '/'));
        define('ROOT', str_replace(PUBLIC_DIR . '/index.php', '', $_SERVER['SCRIPT_FILENAME']));
        define('ASSETS_DIR', '/assets');
        define('UPLOADS_DIR', '/uploads');
        define('DEFAULT_IMAGE', DOMAIN_NAME . '/wp-content/uploads/2019/09/Dutch-plum-pie.jpg');
        define('FORUM_ENABLED', false);
	    define('GTM_KEY', 'GTM-TK4H9W6');
        define('ADMIN_THUMBNAIL_DIR', ROOT . 'public/cache/admin_thumbnails');

        // Headers
        $max_age = 0;
        header('Pragma: public');
        header("Cache-Control: max-age=" . $max_age);
        header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + $max_age));
        // HSTS
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        break;
}

const IMAGE_HANDLERS = [
    IMAGETYPE_JPEG => [
        'load' => 'imagecreatefromjpeg',
        'save' => 'imagejpeg',
        'quality' => 100
    ],
    IMAGETYPE_PNG => [
        'load' => 'imagecreatefrompng',
        'save' => 'imagepng',
        'quality' => 0
    ],
    IMAGETYPE_GIF => [
        'load' => 'imagecreatefromgif',
        'save' => 'imagegif'
    ]
];

require_once(ROOT . 'config/core.php');
require_once(ROOT . 'config/facebook.php');
$dispatcher = new Dispatcher();
