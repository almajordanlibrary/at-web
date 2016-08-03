at-web
======

Web search for Archivist Toolkit

This project adds a web search interface to Archivist Toolkit. The project consists of a database scripts that would add stored procedures to the applications's database (mysql) and webpages to allow a user to perform searches.

Installation

The script/script.sql file contains the sql commands to create the stored procedures in the archivist toolkit's database. After executing the script, a user would then have to be created on the database that is given execute permissions to the stored procedures. This user would be used by the webpages to logon to the database and execute the stored procedures.

The web application is written in php so the webserver would need php installed on it. A directory created in the webserver's webroot to contain the webpages. The contents of the webpages directory are then copied to that directory on the webserver. The file application/config/database.php is then edited to configure the database settings. The lines that are to be changed are

$db['spcol']['hostname'] = 'localhost';

$db['spcol']['username'] = 'spcolWeb';

$db['spcol']['password'] = 'spcolWeb';

$db['spcol']['database'] = 'spcol';

$db['spcol']['port'] = 3306;

$db['spcol']['hostname'] is set to the hostname that the database resides on. $db['spcol']['username'] is the username of the user created above that has access to execute the stored procedures. $db['spcol']['password'] is the password for the user. $db['spcol']['database'] is the name of the archivist toolkit database. $db['spcol']['port'] is the port that the database is listening on. The default is 3306 and it would have to be changed if the database is using a different port. Once the changes are saved, the url to be used to access the search would be similar to http://<webserver name>/<direcotry name>/index.php

