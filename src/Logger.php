<?php
/* 
 * The MIT License
 *
 * Copyright 2014 Ekow Abaka Ainoson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ntentan\logger;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * A static logger wrapped around the super awesome monolog logging framework. 
 * The whole idea behind the wrapper is to minimize the setup code and expose
 * logging settings. By that approach applications can use external configuration files to
 * configure the logging backend (this is however yet to be implemented). 
 * The ntentan logger class tries to be compatible with the interfaces defined 
 * in the PSR-3 specification.
 * 
 * @author Ekowa Abaka Ainooson <jainooson@gmail.com>
 */
class Logger
{
    /**
     * An instance of monolog
     * @var \Monolog\Logger
     */
    private static $backend;
    
    /**
     * An instance of the monolog stream handler.
     * @var type \Monolog\Handler\StreamHandler;
     */
    private static $stream;
    
    /**
     * A path to the location where logs would be written.
     * @var string
     */
    private static $logFilePath = 'log/app.log';
    
    /**
     * A name for the logger.
     * This parameter is used as the name of the channel within monolog.
     * @var string
     */
    private static $name;
    
    /**
     * The minimum logging level.
     * Any logging messages that are triggered below the minimum logging level
     * is not displayed.
     * @var integer
     */
    private static $minimumLevel;
    
    /**
     * The format of the log lines written to the log files.
     * This format is directly passed to monolog's LineFormatter as such it's 
     * format is directly compatible with that of monolog's.
     * @var string
     */
    private static $logFormat = "[%datetime%] [%channel%] %level_name% : %message%\n";
    
    /**
     * When set to false the logger is simply disabled.
     * @var 
     */
    private static $active = false;
    
    const EMERGENCY = \Monolog\Logger::EMERGENCY;
    const ALERT     = \Monolog\Logger::ALERT;
    const CRITICAL  = \Monolog\Logger::CRITICAL;
    const ERROR     = \Monolog\Logger::ERROR;
    const WARNING   = \Monolog\Logger::WARNING;
    const NOTICE    = \Monolog\Logger::NOTICE;
    const INFO      = \Monolog\Logger::INFO;
    const DEBUG     = \Monolog\Logger::DEBUG;
    
    
    /**
     * Returns a singleton instance of a monolog logger.
     * @return \Monolog\Logger
     */
    public static function getBackend()
    {
        if(!is_object(self::$backend))
        {
            self::$backend = new \Monolog\Logger(self::$name);
            self::$backend->pushHandler(self::getStreamHandler());
            self::setLogFormat(self::$logFormat);
        }
        return self::$backend;
    }
    
    /**
     * Sets up the log format of the log lines written to the log files.
     * This method actually creates a new monolog LineFormatter and passes the
     * format argument directly to it. Due to this implementation, the format
     * it accepts is exactly what monolog uses.
     * 
     * @param string $format
     */
    public static function setLogFormat($format)
    {
        self::$logFormat = $format;
        self::getStreamHandler()->setFormatter(new LineFormatter($format));
    }
    
    /**
     * Returns a singleton instance of the monolog stream handler.
     * @return \Monolog\Handler\StreamHandler
     */
    private static function getStreamHandler()
    {
        if(!is_object(self::$stream))
        {
            self::$stream = new StreamHandler(self::$logFilePath, self::$minimumLevel);
        }
        return self::$stream;
    }
    
    /**
     * Initializes the logger.
     * 
     * @param string $path
     * @param string $name
     * @param integer $minimumLevel
     */
    public static function init($path, $name = 'log', $minimumLevel = self::DEBUG)
    {
        if($path === 'php://output')
        {
            self::$active = true;
        }
        else if(is_writable($path))
        {
            self::$active = true;
        }
        else 
        {
            self::$active = false;
        }
        
        self::$backend = null;
        self::$stream = null;
        self::$name = $name;
        self::$logFilePath = $path;
        self::$minimumLevel = $minimumLevel;
    }
    
    /**
     * Write out the log message to the log file at the specified log level.
     * 
     * @param integer $level
     * @param string $message
     */
    public static function log($level, $message)
    {
        if(self::$active)
        {
            self::getBackend()->addRecord($level, $message);
        }
    }
    
    public static function emergency($message)
    {
        self::log(self::EMERGENCY, $message);
    }
    
    public static function alert($message)
    {
        self::log(self::ALERT, $message);
    }
    
    public static function critical($message)
    {
        self::log(self::CRITICAL, $message);
    }
    
    public static function error($message)
    {
        self::log(self::ERROR, $message);
    }
    
    public static function warning($message)
    {
        self::log(self::WARNING, $message);
    }
    
    public static function notice($message)
    {
        self::log(self::NOTICE, $message);
    }
    
    public static function info($message)
    {
        self::log(self::INFO, $message);
    }
    
    public static function debug($message)
    {
        self::log(self::DEBUG, $message);
    }
}

