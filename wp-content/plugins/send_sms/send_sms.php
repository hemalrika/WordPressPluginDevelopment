<?php
/**
 * @package send_sm
 */
/*
Plugin Name: Send sms
Plugin URI: https://ptc.com/
Description: Send sms plugin is a light weight for count words.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: HemalRika(HR) Foundation
Author URI: https://hemalrika-hr.com
License: GPLv2 or later
Text Domain: send_sm
*/
// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';


// Your Account SID and Auth Token from console.twilio.com
$sid = "ACaa042e144b17691064c7a01f84a4d6b9";
$token = "e6db2713700c2d38e8ef711783b2e5b9";
$client = new Twilio\Rest\Client($sid, $token);

// Use the Client to make requests to the Twilio REST API
$client->messages->create(
    // The number you'd like to send the message to
    '+8801758632013',
    [
        // A Twilio phone number you purchased at https://console.twilio.com
        'from' => '+15418626864',
        // The body of the text message you'd like to send
        'body' => "Hey rika. how are you!"
    ]
);