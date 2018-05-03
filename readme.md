# wordpress-composer-setup
A composer plugin to setup your WordPress environment to be friends with composer. 
Make use of johnpbloch/wordpress package to get the WordPress files.

How?
This plugin will create the wp-config.php file with the necessary definitions to let WordPress to be suitable with composer.
You just need to insert database details and this wordpress-composer-setup will handle the rest.

Why wp-config.php?
The wp-config.php is the only non-core file that surely, will be loaded either in frontend or in admin. so its a good place to setup everything we need

### Usage
To Setup a WordPress installation including wp-config.php, add the following to your package's composer file:

```
"require": {
	"johnpbloch/wordpress": "4.9.5",
	"giofreitas/wordpress-composer-setup": "dev-master"
}
```
You can manually set the site url in extra (do not define if you want it to be dynamic through all environments):

```
"extra": {
	"wordpress-site-url": "localhost"
}
```

We can also make use of some options from johnpbloch/wordpress-core-installer and composer/installers to change some default configurations:

```
"extra": {
	"wordpress-install-dir": "wordpress"
	"installer-paths": {
	    "wp-content/themes/{$name}/": ["type:wordpress-theme"],
	    "wp-content/plugins/{$name}/": ["type:wordpress-plugin"]
	    "wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"]
    }
}
```

For more information visit [johnpbloch/wordpress-core-installer](https://github.com/johnpbloch/wordpress-core-installer) and [composer/installers](https://github.com/composer/installers).
