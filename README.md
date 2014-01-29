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

Install client side dependencies.

`$ bower install`

Go to `installers/`:

Edit the next file(s) to configure the installer

* `platform_installer.json`

#### Databases and main configurations
`$ python platform_installer.py`

Requirements
------------

* PHP >= 5.5
* Python >= 2.7
* JS Node
* Bower
* Apache >= 2.2
* MySQL