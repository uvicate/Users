Users
=====

RESTful API that handles:
* Create, edit and delete Users
* Login and logout
* Forgotten password

Authenticated with OAuth 2.0

Installation
------------

Get and install dependencies

```
$ php composer.phar install
```

Then create the databse using `sql/script.sql` to create the tables

To complete installation, edit the database name and password in `configure.php` file.

Requirements
------------

* PHP > 5.3
* MySQL
* Apache2