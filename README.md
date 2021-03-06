Formless project
=============
A proposal for the elimination of the paper form


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


Project Installation
-----------
* Clone the whole project in the webpage folder (*var/www*) in the case of Linux Ubuntu
* Change the configuration for GnuPG (*/applications/libraries/securitygpg*)
```
$CONFIG['gnupg_home'] = '/var/www/.gnupg';
putenv("GNUPGHOME={$CONFIG['gnupg_home']}");
```
* Make sure that you have granted the permission for apache user (www) in the gnupg_home folder
* Create a database with the name **formless_tpt** and the collation **utf8_general_ci** and execute the script file (**formless_tpt.sql**) in the **/application/sql**
* Database configuration can be found at **application/config/database.php**.

Contact
----------
* Email: lenguyenhaohiep@gmail.com

