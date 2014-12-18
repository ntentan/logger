Ntentan Logger
==============
A static logger wrapped around the super awesome monolog logging framework. 
The whole idea behind the wrapper is to minimize the setup code and expose
logging settings. By that approach applications can use external configuration 
files to configure the logging backend (this is however yet to be implemented). 
The ntentan logger class tries to be compatible with the interfaces defined 
in the PSR-3 specification.

Installation
------------
You can install Logger through composer since its available on packagist
(ntentan/logger).

Example
-------
To use the ntentan logger.

````php
<?php

use ntentan\logger\Logger;

// Initialize the logger
Logger::init("/path/to/log/file.log");

// Write some logs
Logger::log(Logger::DEBUG, "Hello!");
Logger::warning("World!");

````
