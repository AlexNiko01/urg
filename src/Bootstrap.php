<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.10.17
 * Time: 23:09
 */
namespace UserRegisterGenerate;

use UserRegisterGenerate\Controllers\AdminPageController;

class Bootstrap
{
    private $ctrl;
    private $cf_handler;
    private $register_r;
    function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        $this->ctrl = new AdminPageController();
        $this->cf_handler = new \CfHandler();
        $this->register_r = new \RegisterReporter();
        add_action( 'admin_enqueue_scripts', [$this,'load_admin_styles'] );

    }
    public function load_admin_styles() {
        wp_enqueue_style( 'admin_css_urg', plugins_url('/assets/css/urg_styles.css', __FILE__), false, '1.0.0' );
    }
    public function run()
    {

        $this->initAdminPages();
        $this->ctrl->createFormShortcode();
        $this->initHooks();
    }


    private function initAdminPages()
    {
            add_action('admin_menu', function () {
            add_menu_page('user register generate', 'user register generate', 'manage_options', 'user_register_generate', array($this->ctrl, 'main'), 'dashicons-tickets', 6);
        });

    }
    public function initHooks(){
    	$this->cf_handler->initHooks();
    }

}