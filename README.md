# Mysql2Sqlite

This is a simple PHP CLI utility to migrate data in a MySQL database to a SQLite 
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

## Usage

 Usage:

`php mysql2sqlite.php -h` generates this help screen.
        
`php mysql2sqlite.php -d=dbname -u=dbuser -p=pass -f=sqlite_file --tables=posts,users,comments`

* if you leave out **-p**, will assume no password required
* if you leave out **-f**, will assume ./database.sqlite in current folder
* if you leave out **--tables**, will assume all tables to be migrated


**Examples:**

All tables, no password:  
    `php mysql2sqlite.php -d=blog -u=root -f=./blog.sqlite`

Some tables, password required:  
    `php mysql2sqlite.php -d=blog -u=root -p=mypass -f=./blog.sqlite --tables=posts,users`

All tables, no password, **./database.sqlite** as sqlite file   
    `php mysql2sqlite.php -d=blog -u=root`


## Output



```bash

php mysql2sqlite.php  -d=blog -u=root -f=./database.sqlite         

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
