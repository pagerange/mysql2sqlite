<?php

/* CONFIGURATION 
--------------------------------------------------------------------------- */

// Edit these options to meet your needs.

return array (
    'MYSQL_DBNAME' => 'bikemike', // database name
    'MYSQL_DBUSER' => 'root', // database username
    'MYSQL_DBPASS' => '', // database password
    'SQLITE_DBFILE' => './database.sqlite', // path to sqlite file
    'MYSQL_TABLES' => [], // leave empty to migrate all tables
    // 'MYSQL_TABLES' => ['t1', 't2', 't3', 't4'], // or add specific tables
);
