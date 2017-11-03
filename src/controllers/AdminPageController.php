<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.07.17
 * Time: 21:33
 */

namespace UserRegisterGenerate\Controllers;


class AdminPageController
{
    public $admin_url = '';

    function __construct()
    {
        $this->admin_url = admin_url('admin.php?page=user_register_generate', 'admin');
    }

    public function main()
    {
        $this->formHandler();
        $form_id = get_option('urg_form');
        $page_id = get_option('urg_page');
        $forms = $this->delight_cf7_forms();
        $pages = $this->getPages(); ?>
        <div class="wrap ugr-wrap">
            <h1 class="wp-heading-inline">User Register Generate</h1>
            <?php if (!function_exists('wpcf7')): ?>
                <div class="ugr-item ugr-item-warn">
                    <h2>Перечень необходимых плагинов:</h2>
                    <p>Contact Form 7</p>
                </div>
            <?php endif; ?>
            <div class="ugr-item">
                <h2>Настройки:</h2>
                <div class="grid">
                    <div class="col col-60">
                        <form class="urg-form" action="" method="post">
                            <h4>Выберите контактную форму:</h4>
                            <p>
                                <select name="urg_form" id="">
                                    <?php foreach ($forms as $id => $title): ?>
                                        <option value="<?php echo $id ?>" <?php echo $id == $form_id ? 'selected' : '' ?> ><?php echo $title ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>
                            <h4>Выберите страницу переадресации:</h4>
                            <p>
                                <select name="urg_page" id="">
                                    <?php foreach ($pages as $id => $title): ?>
                                        <option value="<?php echo $id ?>" <?php echo $id == $page_id ? 'selected' : '' ?> ><?php echo $title ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </p>
                            <p>
                                <input class="button" type="submit" value="Submit">
                            </p>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ugr-item">
                <h2 class="wp-heading-inline">Шерткоды:</h2>
                <div class="grid ugr-sc">
                    <div class="col col-70"><strong>Форма регистрации</strong>
                        (используйте данный шерткод для вывода формы
                        регистрации):
                    </div>
                    <div class="col col-30">[urg_form]</div>
                </div>
                <div class="grid ugr-sc">
                    <div class="col col-70"><strong>Идентификатор пользователя</strong> (вставьте этот шерткод в
                        настройках
                        письма для пользователя Contact Form 7)
                    </div>
                    <div class="col col-30">[user_id]</div>
                </div>
                <div class="grid ugr-sc">
                    <div class="col col-70"><strong>Выводит данные пользователя после отправки формы</strong> (добавьте
                        на
                        странице переадресации)
                    </div>
                    <div class="col col-30">[urg_success]</div>
                </div>
            </div>
        </div>
        <?php
    }

    public function formHandler()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }
        if (!empty($_POST['urg_form'])) {
            update_option('urg_form', $_POST['urg_form']);
        }
        if (!empty($_POST['urg_page'])) {
            update_option('urg_page', $_POST['urg_page']);
        }
    }

    public function urg_create_shortcode()
    {
        $form_id = get_option('urg_form');
        return do_shortcode('[contact-form-7 id="' . $form_id . '"]');
    }

    public function createFormShortcode()
    {

        add_shortcode('urg_form', array($this, 'urg_create_shortcode'));
    }

    public function delight_cf7_forms()
    {
        if (function_exists('wpcf7')) {
            $cfs = get_posts(array(
                    'post_type' => 'wpcf7_contact_form',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'orderby' => 'title'
                )
            );
        }
        $cf7_forms = array('' => __('Select a Form', 'TEXT-DOMAIN'));
        if (!empty($cfs)) {
            foreach ($cfs as $cf) {
                $cf7_forms[absint($cf->ID)] = strip_tags($cf->post_title);
            }
        }
        return $cf7_forms;
    }

    private function getPages()
    {
        $pages = get_posts(array(
                'post_type' => 'page',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'order' => 'ASC',
                'orderby' => 'title'
            )
        );
        $pages_arr = array('' => __('Select a Page', 'TEXT-DOMAIN'));
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $pages_arr[absint($page->ID)] = strip_tags($page->post_title);
            }
        }
        return $pages_arr;
    }


}