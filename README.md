# Construct
"This is the Construct. It's our loading program. We can load anything... From clothing to equipment, weapons, training simulations; anything we need."  ―Morpheus, on the Construct

Construct is Impresspages fork, since they are not supporting anymore. https://github.com/impresspages/ImpressPages

This repository is used as a library for the main https://github.com/urbanway/construct respository. Here are stored the most fundamental features of construct. In long run, this repository should become into a self sustainable construct-framework without CMS.
## Documentation
https://urbanway.github.io/construct-docs/


## Installation

If you want to use the latest version from github, please follow these steps to get everything up and running.

1. Create a file called `composer.json` and put the following content in it:

```php
{
"require": {
"urbanway/construct": "^5.0.0"
},
"scripts": {
"post-install-cmd": ["php vendor/urbanway/construct/bin/setup.php public"],
"post-update-cmd": ["php vendor/urbanway/construct/bin/setup.php public"]
},
"autoload": {
"psr-4": {"Plugin\\": "public/Plugin/"}
}
}
```

2. Install all composer dependencies by running `composer install`

3. Start a webserver to serve the `public` directory. You can also use the built-in PHP webserver, switch into the public directory `cd public` and start the webserver using this command: `php -S localhost:8000 index.php`

4. Open your webserver and navigationigate to whatever address you are using, for example `http://localhost:8000`.

5. Follow the setup wizard.
