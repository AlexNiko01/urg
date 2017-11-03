<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31.10.17
 * Time: 22:19
 */
class RegisterReporter
{
    public function __construct()
    {
        add_shortcode('urg_success', [$this, 'shortcodeSuccess']);
        add_action('wp_footer', [$this, 'jsFormHandler']);
    }

    public function shortcodeSuccess()
    {

        $form_id = $_SESSION['submitted_form_id'];
        $form_data = get_post_meta($form_id, 'form_data', true);
        $form_data = json_decode($form_data);
        $user_id = get_post_meta($form_id, 'user_id', true);
        $markup = '';
        $tempDir = wp_upload_dir();

        foreach ($form_data as $key => $value) {
            $markup .= '<p>' . $value . '</p>';
        }
        $markup .= '<img src="' . $tempDir['url'] . '/' . $user_id . '.png" />';
        return $markup;
    }

    public function jsFormHandler()
    {
        $page_id = get_option('urg_page');
        if (empty($page_id)) {
            return;
        }
        $link = get_the_permalink($page_id);
        ?>
        <script>
            jQuery('.wpcf7').on('wpcf7:mailsent', function (event) {
                window.location.href = '<?php echo $link;?>';
            });
        </script>
        <?php
    }

}