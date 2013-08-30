## Wardrobe

[![Latest Stable Version](https://poser.pugx.org/wardrobe/core/version.png)](https://packagist.org/packages/wardrobe/core) [![Total Downloads](https://poser.pugx.org/wardrobe/core/d/total.png)](https://packagist.org/packages/wardrobe/core)

Wardrobe is designed to be a very minimal blogging platform with the primary focus on writing. Currently it is a work in progress but you are free to give it a try. (Just be warned this alpha/beta quality). If you have any issues or ideas please report them.

![Wardrobe](http://wardrobecms.com/media/wardrobe-air-large.png)


Installing Wardrobe As A Project
---------------------------------------

Creating a stand-alone Wardrobe installation is now as simple as running `composer create-project wardrobe/wardrobe`. For more information visit [wardrobecms.com](http://wardrobecms.com).

Installing Wardrobe In An Existing Laravel Application
---------------------------------------

Installing Wardrobe in an existing Laravel application couldn't be easier!

If you have the [Laravel Package Installer](https://github.com/rtablada/package-installer), simply run `php artisan package:install wardrobe/core`.

If you do not have the package installer then add  `"wardrobe/core": "1.0.*"` to your `composer.json` file and run `composer update`.
Then add `Wardrobe\Core\WardrobeServiceProvider` to your providers and `'Wardrobe' => 'Wardrobe\Core\Facades\WardrobeFacade'` to your aliases in `app/config/app.php`.

Now the last thing you need to do is publish the necessary files configuration and theme files by running `php artisan wardrobe:config`, `php artisan config:publish wardrobe/core`, and `php artisan wardrobe:themes`.

Configuring the Database Connection
---------------------------------------

WardrobeCMS is designed to give you maximum database configuration within existing Laravel applications.
If you would like to use the default database connection from your host app, no configuration is necessary.
However, if you would like to use a separate database connection, this is available in the `app/config/package/wardrobe/core/database.php` file.

If the `default` configuration is set to `default` it will use the host application connection. Otherwise, it will use the connection details listed in this `connection` array.

Finally, to migrate to your selected database connection run `php artisan wardrobe:migrate`.

Creating Your First User
---------------------------------------

If you are using Wardrobe as a package, you will have to create a user.
This is as easy as running `php artisan wardrobe:user:create first_name last_name email password`, of course filling in your own details as the arguments.

Using Wardrobe
---------------------------------------

By default, your WardrobeCMS blog will be located in your applications index.
The administration panel will be located at `/wardrobe`.

Both of these routes can be configured using route group rules from the `app/config/package/wardrobe/core/routes.php` file.

Theming Wardrobe
---------------------------------------
By default, your theme files are located in `public/themes`.
You can modify these themes or create your own using the default themes as a guide.
The configuration for your themes is located in `app/config/packages/wardrobe/core/wardrobe.php` in the `theme` option.
