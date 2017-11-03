<?php
/*
Plugin Name: User Register Generate
Description: User Register Generate
Version:  1.0
Author: Sem
*/
define('UEG_PATH', __DIR__ . '/src');
require UEG_PATH . '/controllers/AdminPageController.php';
require UEG_PATH . '/services/CfHandler.php';
require UEG_PATH . '/services/RegisterReporter.php';
require UEG_PATH . '/Bootstrap.php';
require UEG_PATH . '/libs/phpqrcode/qrlib.php';

$activation = new UserRegisterGenerate\Bootstrap();
$activation->run();
