<?php
/*
    Copyright 2018 MegaMaker Community Members.

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
*/

return [
    'oauth_access_token'        => getenv("TWITTER_OAUTH_ACCESS_TOKEN"),
    'oauth_access_token_secret' => getenv("TWITTER_OAUTH_ACCESS_TOKEN_SECRET"),
    'consumer_key'              => getenv("TWITTER_CONSUMER_KEY"),
    'consumer_secret'           => getenv("TWITTER_CONSUMER_SECRET"),

    'twitter_user' => getenv("TWITTER_USERNAME"),
    // Note you must create this list ahead of time
    // and underscores are converted to dashes
    'twitter_list' => getenv("TWITTER_LIST_NAME"),

    'discourse_api_key'      => getenv("DISCOURSE_API_KEY"),
    'discourse_api_username' => getenv("DISCOURSE_API_USERNAME")
];
