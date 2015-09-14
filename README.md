# Silex Issue Tracker

Simple Issue tracking system based on Silex.

This project relies on:
* [Silex](https://github.com/silexphp/Silex)
* [Doctrine ORM](https://github.com/doctrine/doctrine2)
* [AdminLTE v2 template](https://github.com/almasaeed2010/AdminLTE)

Current project status: **IN DEVELOPMENT**! A demo can be seen [Here](http://tracker.devzone.eu).

Contributions, suggestions and improvements of any kind are gladly welcome.

---

### Getting started.

1. Clone this project in your *www* directory and install its dependencies with `composer install`.
   * In order to install AdminLTE template, [Bower](http://bower.io) is required. After running `composer install` you need to run `bower install` as well which will download all of the template files required. They are mapped to be downloaded in `public_html/assets/components`.
2. Create a virtual host pointing to the `public_html` directory of the project. (Optional, you can freely load the project with it's absolute URL)
3. Create empty database and name it however you like.
4. Copy `config.sample` and rename it to `config.php` placed in `src/Resources/config/`
5. A cache directory is also available in `var/cache` and needs **write** permissions on linux.
6. Edit `config.php` by configuring the appropriate options for database connection.
7. **silex-issue-tracker** ships with helper commands for database management.

Open terminal from **project root directory** and run the following command to create our database schema:

```bash
bin/console.php orm:schema-tool:create
```

After your schema is created, run the following command so that we can populate our tables with necessary data:

```bash
bin/console.php seed:init
```

The expected output if this command should look like this:

```bash
[0] Inserting users...
[1] Inserting priorities...
[2] Inserting trackers...
[3] Inserting statuses...
[4] Inserting permissions...
[5] Inserting roles...
[6] Inserting project...
[7] Inserting issues...
Done...

```

An admin account is also added so you can log-in immediately, with credentials as follows:
* E-mail:   *admin@demo.com*
* Password: *admin*

All you have to do now is open your project in your browser. There are 2 index files added, one with extra configuration option that allow us to see debug messages if something break our application - `index.php` and `index_dev.php`
