<?php
/*
 * Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
 * See LICENSE.md for licensing information. 
 *
 * This is the example configuration. To configure ESTAP copy this file
 * to config.php and then edit this new file.
 */

// Database connection parameters
define("ESTAP_DB_NAME", "estap");
define("ESTAP_DB_USER", "estap");
define("ESTAP_DB_PASS", "estap");
define("ESTAP_DB_TYPE", "mysql");
define("ESTAP_DB_HOST", "127.0.0.1");
define("ESTAP_DB_PORT", 3306);

define("API_HOST", "http://localhost");
define("API_SECRET", "test");

// Set to true if you want to reuse an open database connection. The default
// is creating a new database connection for each request. When setting it to
// true you must make sure the database can handle many parallel databse
// connections (Usually the maximum number of allowed web server processes)
define("ESTAP_DB_PERSISTENT", false);

// The database DSN. It is automatically build from the connection parameters
// above, so usually you don't need to touch it
define("ESTAP_DB_DSN", ESTAP_DB_TYPE . 
    ":dbname=" . ESTAP_DB_NAME . 
    ";host=" . ESTAP_DB_HOST .
    ";port=" . ESTAP_DB_PORT);
