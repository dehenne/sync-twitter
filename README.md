MegaMaker Twitter List Sync
======

This little app fetches all twitter accounts from the MegaMaker club into a Twitter List

![MegaMaker](https://club.megamaker.co/uploads/default/optimized/1X/9205786661c1c58d22f5971cc32a5d8593e449c3_1_600x500.png)

What you need
------

- You need a twitter app: https://apps.twitter.com/
- Enable read/write access for your twitter app
- Grab your access tokens from the twitter developer site

Installation
------

1. Install all requirements with composer


```shell
composer install
```


2. Setup the Twitter stuff  

    create a `etc/settings.php` file and fill in the settings please
    
```php
<?php

return [
    'oauth_access_token'        => "YOUR_OAUTH_ACCESS_TOKEN",
    'oauth_access_token_secret' => "YOUR_OAUTH_ACCESS_TOKEN_SECRET",
    'consumer_key'              => "YOUR_CONSUMER_KEY",
    'consumer_secret'           => "YOUR_CONSUMER_SECRET",

    'twitter_user' => 'de_henne',
    'twitter_list' => 'megamaker',
    
    'discourse_api_key'      => 'YOUR_DISCOURSE_API_KEY',
    'discourse_api_username' => 'YOUR_DISCOURSE_API_USERNAME'
];

```

How to run?
------

```shell
php sync.php
```

the best thing is to set up a cron to run this script daily or every few hours.


Customizing
------

You can also manually add users to the list.
Just create a user.php in the etc folder

```php
<?php

return [];

```

You can now fill this array with twitter user names.


```php
<?php

return [
    'mijustin',
    'jonfriesen',
    'dehenne'
];

```
