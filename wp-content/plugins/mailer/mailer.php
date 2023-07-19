<?php
/**
 * @package php_mailer
 */
/*
Plugin Name: php Mailer
Plugin URI: https://ptc.com/
Description: php Mailer plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: php_mailer
*/
require_once(plugin_dir_path(__FILE__) . 'PHPMailer-master/src/PHPMailer.php');
require_once(plugin_dir_path(__FILE__) . 'PHPMailer-master/src/Exception.php');
require_once(plugin_dir_path(__FILE__) . 'PHPMailer-master/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailer_plugins_loaded() {
    load_plugin_textdomain('php_mailer', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'mailer_plugins_loaded');
function send_email_with_phpmailer() {
    $pm = new PHPMailer(true);

    try {
        // Configure PHPMailer SMTP settings
        $pm->isSMTP();
        $pm->Host = "smtp.gmail.com";
        $pm->SMTPAuth = true;
        $pm->Username = "hemalrika@gmail.com";
        $pm->Password = "sgtrwxdavysoiicz";
        $pm->SMTPSecure = "tls";
        $pm->Port = 587;

        // Set email details
        $pm->setFrom("hemalrika@gmail.com", "MD hemal akhand");
        $pm->addAddress("hemalrika@gmail.com", "Saad vai");
        $pm->Subject = "Subject of the email";

        // Set HTML body
        $htmlBody = "<h1>HTML Email Example</h1>";
        $htmlBody .= "<p>This is the content of the email.</p>";
        $pm->Body = $htmlBody;
        $pm->isHTML(true);

        // Send the email
        $pm->send();

        echo "Mail Sent";
    } catch (Exception $e) {
        echo "Failed to send email: " . $pm->ErrorInfo;
    }
}

add_action('init', 'send_email_with_phpmailer');