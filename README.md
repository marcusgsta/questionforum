Marcusgsta QUESTIONFORUM
==================================

<!-- [![Latest Stable Version](https://poser.pugx.org/marcusgsta/questionforum/v/stable)](https://packagist.org/packages/marcusgsta/questionforum) -->
<!-- [![Join the chat at https://gitter.im/marcusgsta/questionforum](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/marcusgsta/questionforum?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) -->
[![Build Status](https://travis-ci.org/marcusgsta/questionforum.svg?branch=master)](https://travis-ci.org/marcusgsta/questionforum)
[![Build Status](https://scrutinizer-ci.com/g/marcusgsta/questionforum/badges/build.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/questionforum/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/marcusgsta/questionforum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/questionforum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/marcusgsta/questionforum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/marcusgsta/questionforum/?branch=master)
<!-- [![SensioLabsInsight](https://insight.sensiolabs.com/projects/efdf126a-3a9b-472a-ac31-0668ba47b59c/mini.png)](https://insight.sensiolabs.com/projects/efdf126a-3a9b-472a-ac31-0668ba47b59c)
[![Maintainability](https://api.codeclimate.com/v1/badges/59bf0d51b17dafc2f59d/maintainability)](https://codeclimate.com/github/marcusgsta/questionforum/maintainability) -->


Marcusgsta QUESTIONFORUM module.




Install
------------------

### Clone from Github

```
git clone https://github.com/marcusgsta/questionforum.git
```

### Update composer

```
composer update
```

### Set permissions on database folder and file

```
sudo chmod 777 data && chmod 666 data/db.sqlite
```



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
