<?php

/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.10.17
 * Time: 22:45
 */
class CfHandler
{
    public function initHooks()
    {
        add_action("wpcf7_before_send_mail", [$this, 'beforeSendMail']);
    }

    public function beforeSendMail($cf7)
    {
        $form_id = get_option('urg_form');

        if ($cf7->id() != $form_id) {
            return;
        }

        $title = 'cf_submitted';
        $fields = [];
        foreach ($cf7->collect_mail_tags() as $form_tag) {
            $fields[$form_tag] = isset($_POST[$form_tag]) ? $_POST[$form_tag] : '';
            if ($form_tag == 'your-email' || $form_tag == 'email') {
                $title = $fields[$form_tag];
            }
        }
        $meta_fields = [];
        $meta_fields['user_id'] =  base64_encode($title . date('now'));
        $meta_fields['form_data'] = json_encode($fields);

        $id = wp_insert_post(['meta_input' => $meta_fields, 'post_type' => 'cf_submitted', 'post_title' => $title]);
        if (!session_id()) {
            session_start();
        }
        $_SESSION['submitted_form_id'] = $id;

        add_filter('wpcf7_mail_components', function ($wpcf7_data, $form = null) use ($meta_fields) {

            $tempDir = wp_upload_dir();

            QRcode::png($meta_fields['user_id'], $tempDir['path'] . '/' . $meta_fields['user_id'] . '.png', QR_ECLEVEL_L, 3);

            $qr = '<img src="' . $tempDir['url'] . '/' . $meta_fields['user_id'] . '.png" />';

            $wpcf7_data['body'] = str_replace('[user_id]', $qr, $wpcf7_data['body']);

            return $wpcf7_data;
        });
    }

}