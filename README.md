Marcusgsta QUESTIONFORUM
==================================

[![Latest Stable Version](https://poser.pugx.org/marcusgsta/comment/v/stable)](https://packagist.org/packages/marcusgsta/comment)
[![Join the chat at https://gitter.im/marcusgsta/comment](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/marcusgsta/comment?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/marcusgsta/comment.svg?branch=master)](https://travis-ci.org/marcusgsta/comment)
[![CircleCI](https://circleci.com/gh/marcusgsta/comment.svg?style=svg)](https://circleci.com/gh/marcusgsta/comment)
[![Build Status](https://scrutinizer-ci.com/g/marcusgsta/comment/badges/build.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/comment/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/marcusgsta/comment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/comment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/marcusgsta/comment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/comment/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/efdf126a-3a9b-472a-ac31-0668ba47b59c/mini.png)](https://insight.sensiolabs.com/projects/efdf126a-3a9b-472a-ac31-0668ba47b59c)
[![Maintainability](https://api.codeclimate.com/v1/badges/59bf0d51b17dafc2f59d/maintainability)](https://codeclimate.com/github/marcusgsta/comment/maintainability)


Marcusgsta QUESTIONFORUM module.





Install
------------------

Install using composer and integrate the module with your Anax installation.

### Install an anax base

```
anax create name-of-your-app ramverk1-site-develop
```

### Install with composer

```
composer require marcusgsta/questionforum
```

### Configuration files for Question Forum

```
rsync -av vendor/marcusgsta/questionforum/config/{database.php,navbar.php,di-for-testing.php} config/
```

### Config files for testing

```
rsync -av vendor/marcusgsta/questionforum/config/test config/test/
```

### Router files

```
rsync -av vendor/marcusgsta/questionforum/config/route/ config/route/
```

### Views

```
rsync -av vendor/marcusgsta/questionforum/view/ view/
```

### Database files

```
rsync -av vendor/marcusgsta/questionforum/data/ data/
```

### Set permissions on database folder and file

```
sudo chmod 777 data && chmod 666 data/db.sqlite
```

### CSS files

```
rsync -av vendor/marcusgsta/questionforum/htdocs/css/ htdocs/css/
```

### JS files

```
rsync -av vendor/marcusgsta/questionforum/htdocs/js/ htdocs/js/
```

### DI services

You need to add and replace the services from the configuration in `vendor/marcusgsta/questionforum/config/di.php` into your own anax installation `config/di.php`. Services that already exist need to be replaced with the new ones, since they have been edited.


### Database sql files

There is a default sqlite-database included in `data/db.sqlite`. If you need to set up a new database you can take a look at the sql-files in the `sql`-directory.

### Administrator usage
Log in with username: admin and password: admin.
You will be able to set other users as admin by creating new user and assigning them role: 10. You will also be able to edit/delete all comments and all users.

Regular users can only edit/delete their own comments and their own user profile.

License
------------------

This software carries a MIT license.




```
 .  
..:  Copyright (c) 2017 Marcus Gustafsson (marcusgu@icloud.com)
```
