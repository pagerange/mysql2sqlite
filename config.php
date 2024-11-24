<?php

/* CONFIGURATION 
--------------------------------------------------------------------------- */

// Edit these options to meet your needs.

define('MYSQL_DBNAME', 'bikemike'); // database name
define('MYSQL_DBUSER', 'root'); // database username
define('MYSQL_DBPASS', ''); // database password
define('SQLITE_DBFILE', './database.sqlite'); // path to sqlite database file

// use only one of the following lines... comment out the other
// define('MYSQL_TABLES', ['t1', 't2', 't3']); // tables to migrate, or...
define('MYSQL_TABLES', []); // empty array migrates ALL tables
