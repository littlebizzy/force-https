# Force HTTPS

Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).

* [Plugin Homepage](https://www.littlebizzy.com/plugins/force-https)
* [Download Latest Version (ZIP)](https://github.com/littlebizzy/force-https/archive/v1.4.3.zip)
* [**Become A LittleBizzy.com Member Today!**](https://www.littlebizzy.com/members)

### Defined Constants

    /** Force HTTPS Functions */
    define('FORCE_HTTPS', true); // default = true
    define('FORCE_HTTPS_EXTERNAL_LINKS', false); // default = false
    define('FORCE_HTTPS_EXTERNAL_RESOURCES', true); // default = true
    define('FORCE_HTTPS_INTERNAL_LINKS', true); // default = true
    define('FORCE_HTTPS_INTERNAL_RESOURCES', true); // default = true

### Compatibility

This plugin has been designed for use on [SlickStack](https://slickstack.io) web servers with PHP 7.2 and MySQL 5.7 to achieve best performance. All of our plugins are meant primarily for single site WordPress installations — for both performance and usability reasons, we strongly recommend avoiding WordPress Multisite for the vast majority of your projects.

Any of our WordPress plugins may also be loaded as "Must-Use" plugins (meaning that they load first, and cannot be deactivated) by using our free [Autoloader](https://www.littlebizzy.com/plugins/autoloader) script in the `mu-plugins` directory.

### Our Philosophy

> "Decisions, not options." — **WordPress.org**

> "Everything should be made as simple as possible, but not simpler." — **Albert Einstein** (et al)

> "Write programs that do one thing and do it well... write programs to work together." — **Doug McIlroy**

> "The innovation that this industry talks about so much is bullshit. Anybody can innovate... 99% of it is 'get the work done.' The real work is in the details." — **Linus Torvalds**

### Support Issues

We welcome experienced developers to submit Pull Requests to the Master branch, although opening a new Issue (instead) is usually more helpful so that users can discuss the topic. Please become a [**LittleBizzy.com Member**](https://www.littlebizzy.com/members) if your company requires official support, and keep in mind that GitHub is for code development and not customer service.
