<?php

/**
 * This file synchronize the twitter users from MegaMaker to a Twitter List
 *
 * @author Henning Leutz (https://github.com/dehenne)
 */

require 'vendor/autoload.php';

$settings = require 'etc/settings.php';

/**
 * @return TwitterAPIExchange
 */
function Twitter()
{
    $settings = require 'etc/settings.php';

    return new TwitterAPIExchange([
        'oauth_access_token'        => $settings['oauth_access_token'],
        'oauth_access_token_secret' => $settings['oauth_access_token_secret'],
        'consumer_key'              => $settings['consumer_key'],
        'consumer_secret'           => $settings['consumer_secret']
    ]);
}


/**
 * get twitter usernames from twitter list
 */

$userResult = [];

$fetchMembers = function () {
};

$fetchMembers = function ($cursor = false) use (
    $settings,
    &$fetchMembers,
    &$userResult
) {
    $username = $settings['twitter_user'];
    $list     = $settings['twitter_list'];

    $query = [
        "slug"              => $list,
        "owner_screen_name" => $username
    ];

    if ($cursor) {
        $query['cursor'] = $cursor;
    }

    try {
        $result = Twitter()->setGetfield(http_build_query($query))->buildOauth(
            'https://api.twitter.com/1.1/lists/members.json',
            'GET'
        )->performRequest();
    } catch (\Exception $Exception) {
        echo $Exception->getMessage();
        exit;
    }

    $result = json_decode($result, true);

    if (isset($result['errors'])) {
        return;
    }

    $userResult = array_merge($userResult, $result['users']);

    if (!empty($result['next_cursor'])) {
        $fetchMembers($result['next_cursor']);
    }
};

$fetchMembers();

// filter
$twTwitterUsernames = []; // twitter usernames

foreach ($userResult as $entry) {
    $twTwitterUsernames[] = $entry['screen_name'];
}

/**
 * Get twitter users from MegaMaker
 */

$mmTwitterUsernames = shell_exec('php getTwitterNames.php'); // MegaMaker usernames
$mmTwitterUsernames = json_decode($mmTwitterUsernames, true);

// cleanup
$mmTwitterUsernames = array_map(function ($username) {
    if (strpos($username, 'http') !== false) {
        $parse    = parse_url($username);
        $parse    = trim($parse['path']);
        $parse    = trim($parse, '/');
        $parse    = trim($parse, '@');
        $username = $parse;
    }

    return $username;
}, $mmTwitterUsernames);

/**
 * Add newest users
 */

// filter missing names
$listFlip = array_flip($twTwitterUsernames);
$missing  = array_filter($mmTwitterUsernames, function ($username) use ($listFlip) {
    return !isset($listFlip[$username]);
});

if (empty($missing)) {
    echo 'No new user found :-)'.PHP_EOL;
    exit;
}

/**
 * Add new users to the list
 */

foreach ($missing as $username) {
    try {
        echo 'adding: '.$username.PHP_EOL;

        $result = Twitter()->buildOauth(
            'https://api.twitter.com/1.1/lists/members/create.json',
            'POST'
        )->setPostfields([
            "slug"              => $settings['twitter_list'],
            "owner_screen_name" => $settings['twitter_user'],
            "screen_name"       => $username
        ])->performRequest();

        $result = json_decode($result, true);

        if (isset($result['errors'])) {
            echo $result['errors'][0]['message'].PHP_EOL;
            exit(0);
        }
    } catch (\Exception $Exception) {
        echo $Exception->getMessage().PHP_EOL;
        exit(0);
    }
}

echo 'Added all new users \(^^)/'.PHP_EOL;
