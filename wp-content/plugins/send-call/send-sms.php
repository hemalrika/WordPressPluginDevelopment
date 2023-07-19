<?php
/**
 * @package send_call
 */
/*
Plugin Name: Send call
Plugin URI: https://ptc.com/
Description: Send call plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: send_call
*/
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';

function call_plugins_loaded() {
    load_plugin_textdomain('php_call', false, __FILE__.'/languages');
}
add_action('plugins_loaded', 'call_plugins_loaded');


$sid = "ACaa042e144b17691064c7a01f84a4d6b9";
$token = "e6db2713700c2d38e8ef711783b2e5b9";

$client = new Twilio\Rest\Client($sid, $token);

// Read TwiML at this URL when a call connects (hold music)
$call = $client->calls->create(
    '+8801758632013',
    // Call this number
    '+15418626864',
    // From a valid Twilio number
    [
        'url' => 'https://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient'
    ]
);
if($call) {
    echo "Success";
}