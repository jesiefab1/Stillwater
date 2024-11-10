<?php
    require_once ('GoogleAPI/vendor/autoload.php');

    // init configuration
    $clientID = '175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'https://shiny-yodel-p466qv9g645crpp7-8000.app.github.dev/htdocs/Stillwater_system/log_in.php';

    // create Client Request to access Google API
    $client = new Google_Client();
    $client->setClientId("175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com");
    $client->setClientSecret("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");
    $client->setRedirectUri("https://shiny-yodel-p466qv9g645crpp7-8000.app.github.dev/htdocs/Stillwater_system/log_in.php");
    $client->addScope("email");
    $client->addScope("profile");

    // authenticate code from Google OAuth Flow
    if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google\Service\Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    // now you can use this profile info to create account in your website and make user logged in.
    } else {
    echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
    }
?>