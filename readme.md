# DB Backup Kit Beta 0.1
A simple PHP Script to save one, multiple, or all available database(s) from server (only works in GNU/Linux Operation System)


### How to use

```sh
$ php db-save.php -help
[?] usage : php save-db.php <options>
[?] available options : 
  -help	 display this message
  -h	 set MySQL host
  -u	 set MySQL user
  -p	 set MySQL pass
  -show	 show all available database in server
  -save <db_name>  save selected database [separated by comma]
  -save-all  save all available database in server
  -zip	 compress saved database into zip file [coming soon]
[?] example : 
 $ php save-db.php -h host -u user -p pass -show  [check all database]
 $ php save-db.php -h host -u user -p pass -save my_db  [save 1 database]
 $ php save-db.php -h host -u user -p pass -save my_db1,my_db2  [multiple db]
 $ php save-db.php -h host -u user -p pass -save-all  [all available db]
```

### Requirements

 - php-mysql extension