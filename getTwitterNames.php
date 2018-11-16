<?php

/**
 * This file fetches all twitter accounts from the MegaMaker club
 * and outputs these data as json
 *
 * @author Henning Leutz (https://github.com/dehenne)
 */

// helper functions

function getAuthQuery()
{
    $settings = require 'etc/settings.php';

    return [
        'api_key'      => $settings['discourse_api_key'],
        'api_username' => $settings['discourse_api_username']
    ];
}

/**
 * @param string $url
 * @return string
 */
function fetch($url)
{
    $ch      = curl_init();
    $timeout = 5;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

/**
 * @param $username
 * @return string
 */
function fetchUser($username)
{
    return fetch(
        'https://club.megamaker.co/users/'.$username.'.json?'.http_build_query(getAuthQuery())
    );
}

/**
 * fetches all users from Discourse
 *
 * @param integer|bool $page
 * @return mixed
 */
function fetchList($page = false)
{
    $query = getAuthQuery();

    if ($page) {
        $query['page'] = $page;
    }


    $call   = 'https://club.megamaker.co/admin/users/list/active.json?'.http_build_query($query);
    $result = fetch($call);
    $users  = json_decode($result, true);

    return $users;
}

/**
 * Fetches all users from Discourse
 *
 * @return array
 */
function getAllUsers()
{
    $users = [];

    for ($i = 0; $i < 100; $i++) {
        $result = fetchList($i);

        if (empty($result)) {
            break;
        }

        $users = array_merge($users, $result);
    }

    return $users;
}

/**
 * STUFF
 */

$twitterUser = [];
$users       = getAllUsers();

if (!is_array($users)) {
    echo '[]';
    exit;
}

// get the profiles of each user
foreach ($users as $user) {
    $userData = fetchUser($user["username"]);
    $userData = json_decode($userData, true);

    if (!$userData) {
        continue;
    }

    if (!isset($userData["user"]) || !isset($userData["user"]['user_fields']) || !$userData["user"]['user_fields'][1]) {
        continue;
    }
    
    $twitterUser[] = $userData["user"]['user_fields'][1];

    // rate limiter
    sleep(0.75);
}

echo json_encode($twitterUser);
