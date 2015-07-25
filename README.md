Formless project
=============

Description
-----------
The objective of the project is to propose a prototype that takes into account the usage of the electronic form instead of the traditional paper form. The prototype is based on a web-based solution with the main functions namely filling, sharing, signing and verifying the electronic form. Particularly, we consider the relations between forms to construct a method which allows us to reuse the common information between them.


Project's Dependencies
-----------
* PHP 5.6 at least
* MySQL database
* GPGme 1.5.5
* GnuPG 1.3.6
* wkhtmltopdf


Project's Setup
-----------
* Clone the whole project in the webpage folder (var/www) in the case of Linux Ubuntu
* Change the configuration for GnuPG (/applications/libraries/securitygpg)
```
$CONFIG['gnupg_home'] = '/var/www/.gnupg';
putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
```
* Make sure that you have granted the permission for apacher user (www) in the gnupg_home folder

Contact
----------
* Author: Hiep Le (Vietnamese)
* Email: lenguyenhaohiep@gmail.com

