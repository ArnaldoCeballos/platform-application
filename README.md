Oro Platform Empty Application
==============================

An example of an empty application using Oro Platform.

This repository contains application configuration settings and depends on Oro Platform. It can be used as a starting point to build applications using the Oro Platform.

## Requirements

Oro Platform is a Symfony 2 based application with the following requirements:

* PHP 5.5.9 or above with command line interface
* PHP Extensions
    * GD
    * Mcrypt
    * JSON
    * ctype
    * Tokenizer
    * SimpleXML
    * PCRE
    * ICU
* MySQL 5.1 or above
* PostgreSQL 9.1 or above

## Installation instructions

### Using Composer

Both Symfony 2 and Oro Platform use [Composer][1] to manage their dependencies, this is the recommended way to install Oro Platform.

If you do not have the Composer yet, download it and follow the instructions on
http://getcomposer.org/ website or simply run the following command:

```bash
    curl -s https://getcomposer.org/installer | php
```

Oro Platform uses [fxpio/composer-asset-plugin][9] to manage dependency in third-party asset libraries. The plugin has to be installed globally (per user):
 
```bash
    composer global require "fxp/composer-asset-plugin:~1.2"
```

- Clone https://github.com/orocrm/platform-application.git Platform Application project with

```bash
    git clone https://github.com/orocrm/platform-application.git
```

- Go to platform-application folder and look at list of releases.
 
```bash
git branch -a
```

- Select the latest release (release_branch for example can be 1.10)

```bash
git checkout release_branch
```

- Make sure that you have [NodeJS][3] installed

- Install OroCRM dependencies with the Composer. If the installation process is too slow, you can use the "--prefer-dist" option.
  Run the Composer installation:

```bash
php composer.phar install --prefer-dist --no-dev
```

- Create the database with the name specified in the previous step (default name is "bap_standard").

- Install the application and the admin user with the Installation Wizard by opening install.php in the browser or from CLI:

```bash  
php app/console oro:install --env prod
```

- Enable WebSockets messaging

```bash
php app/console clank:server --env prod
```

- Configure crontab or scheduled tasks execution to run the command below every minute:

```bash
php app/console oro:cron --env prod
```
 
**Note:** ``app/console`` is a path from project root folder. Please make sure you are using full path for crontab configuration if you are running console command from a different location.

## Installation notes

Installed PHP Accelerators must be compatible with Symfony and Doctrine (support DOCBLOCKs).

Note that the port used in Websocket must be open in firewall for outgoing/incoming connections.

Using MySQL 5.6 on HDD is potentially risky as it can result in performance issues.

Recommended configuration for this case:

    innodb_file_per_table = 0

Ensure that timeout has the default value

    wait_timeout = 28800

See [Optimizing InnoDB Disk I/O][2] for more information.

The default MySQL character set utf8 uses a maximum of three bytes per character and contains only BMP characters. The [utf8mb4][5] character set uses a maximum of four bytes per character and supports supplemental characters (e.g. emojis). It is [recommended][6] to use utf8mb4 character set in your app/config.yml:

```
...
doctrine:
    dbal:
        connections:
            default:
                driver:       "%database_driver%"
                host:         "%database_host%"
                port:         "%database_port%"
                dbname:       "%database_name%"
                user:         "%database_user%"
                password:     "%database_password%"
                charset:      utf8mb4
                default_table_options:
                    charset: utf8mb4
                    collate: utf8mb4_unicode_ci
                    row_format: dynamic
...
```

Using utf8mb4 might have side effects. MySQL indexes have a default limit of 767 bytes, so any indexed fields with varchar(255) will fail when inserted, because utf8mb4 can have 4 bytes per character (255 * 4 = 1020 bytes), thus the longest data can be 191 (191 * 4 = 764 < 767). To be able to use any 4 byte charset, all indexed varchars should be at most varchar(191). To overcome the index size issue, the server can be configured to have large index size by enabling [sysvar_innodb_large_prefix][7]. However, innodb_large_prefix requires some additional settings to work:

- innodb_default_row_format=DYNAMIC (you may also enable it per connection as in the config above)
- innodb_file_format=Barracuda
- innodb_file_per_table=1 (see above performance issues with this setting)

More details about this issue can be read [here][8]

## PostgreSQL installation notes

You need to load `uuid-ossp` extension for proper doctrine's `guid` type handling.
Log into the database and run sql query:

```
CREATE EXTENSION "uuid-ossp";
```

## Web Server Configuration

The Oro Platform application is based on the Symfony standard application, so the web server configuration recommendations are the [same][4].

[1]:  http://getcomposer.org/
[2]:  http://dev.mysql.com/doc/refman/5.6/en/optimizing-innodb-diskio.html
[3]:  https://github.com/joyent/node/wiki/Installing-Node.js-via-package-manager
[4]:  http://symfony.com/doc/2.8/setup/web_server_configuration.html
[5]:  https://dev.mysql.com/doc/refman/5.6/en/charset-unicode-utf8mb4.html
[6]:  http://symfony.com/doc/current/doctrine.html#configuring-the-database
[7]:  http://dev.mysql.com/doc/refman/5.6/en/innodb-parameters.html#sysvar_innodb_large_prefix
[8]:  https://mathiasbynens.be/notes/mysql-utf8mb4#utf8-to-utf8mb4
[9]:  https://github.com/fxpio/composer-asset-plugin/blob/master/Resources/doc/index.md
