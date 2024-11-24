# Mysql2Sqlite

This is a simple utility to migrate data in a MySQL database to a SQLite 
database.  I wrote this for myself to use in a pinch, but feel free to use
it if it meets your needs.

Because this utility binds all data to named placeholders for all tables
being migrated, it can safely copy all data from one database to the other,
assuming all the tables have been created with schema and data definitions
that match the MySQL database.  This can be accomplished quite easily in
Laravel, for example, by running your migrations while your `DB_CONNECTION`
is set to sqlite in your `.env` file.

## Prerequisites

* You must have MySQL running on your system, containing a database from which
you want to migrate data.
* You must have PHP in your system, and in your path, so that it can be
executed from the shell.
* You must have the mysql, PDO, and SQLite extensions installed in your
version of PHP.
* You should have the SQLite binary on your system and in your path so you 
can check the database once the data has been migrated.  Note: this utility
will work fine without it, but you won't be able to check your migrated
data without it.
* Your SQLite database file must contain empty tables that match the
schema and table definitions in the MySQL tables. 

## Configuration

Edit the `config.php` file to meet your needs.

Below is sample configuration to move all table data from a database 
named `blog` in MySQL, to SQLite. You can either define an array of tables 
you want to migrate, or leave the array empty to migrate ALL tables. 

Note: You can use a relative or absolute path to the target SQLite
database file

```php
return array (
    'MYSQL_DBNAME' => 'bikemike', // database name
    'MYSQL_DBUSER' => 'root', // database username
    'MYSQL_DBPASS' => '', // database password
    'SQLITE_DBFILE' => './database.sqlite', // path to sqlite file
    'MYSQL_TABLES' => [], // leave empty to migrate all tables
    // 'MYSQL_TABLES' => ['t1', 't2', 't3', 't4'], // or add specific tables
);
```

## Run the utility

Open a terminal where this file is located, and simply run the file as
a PHP script.  On success, output will look something like this:

```bash

php mysql2sqlite.php           

Migrated posts!
Migrated users!
Migrated comments!
Migrated images!
Migrated headers!
Migrated password_resets!

DONE!

```
---

_@updated on 24 November 2024 by Steve George_
