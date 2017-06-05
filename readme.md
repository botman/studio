# BotMan Laravel Starter

This repository is an example Laravel application to instantly start developing cross-platform messaging bots using [BotMan](https://github.com/mpociot/botman).

## Installation

1. Create a new bot project using this boilerplate.

```bash
composer create-project mpociot/botman-laravel-starter my_new_bot
```

2. Install [Laravel Valet](https://laravel.com/docs/5.3/valet) and use `valet share` to retrieve a HTTPS URL that you can use in 
the messaging services for testing. The predefined route is `/botman`.

3. Edit your `.env` file and [connect BotMan with your Messaging Services](https://github.com/mpociot/botman#connect-with-your-messaging-service)

4. Modify your bot logic in `app/Http/Controllers/BotManController.php` and/or `routes/botman.php`.

5. Build awesome bots!

> If you want to use the Slack RTM API, simply call `php artisan botman:listen` to let your bot connect to your Slack channel!

## Extras

### Add Facebook Get Started button

Adding a "Get Started" button resolves the issue of users not knowing what to write to break the ice with your bot. It is displayed the first time the user interacts with a Facebook chatbot. When you click it, it will send a payload (text) to BotMan and you can react to it and send the first welcome message to the user and tell him how to use your bot. In order to define this payload you need to send a [CURL request](https://developers.facebook.com/docs/messenger-platform/messenger-profile/get-started-button) with some data to Facebook. But BotMan can do that for you too!

First define the payload text in your `services.php` BotMan config.

```php
'facebook_start_button_payload' => 'YOUR_PAYLOAD_TEXT'
```

Then run the artisan command `php artisan botman:addFbStartButton` which will add the Get Started button to your page's chat. You are now able to listen to this button with just the payload in your `hears` method.

```php
$botman->hears('YOUR_PAYLOAD_TEXT', function (BotMan $bot) {
	...
});
```

### Add Facebook Get Started button

With this starter project it now gets much easier to add a persistent Facebook menu to your bot. First define the structure and types of your menu in your `services.php` config file. There you will find a demo menu payload. Just edit it to your needs. You can read more about the menu possibilities in the [Facebook Docs](https://developers.facebook.com/docs/messenger-platform/messenger-profile/persistent-menu#post). Then run the artisan command `php artisan botman:facebookAddMenu` to create or update the Facebook menu. 

## License

BotMan and this boilerplate is free software distributed under the terms of the MIT license.
