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

use org\bovigo\vfs\vfsStream;

class LoggerTest extends \PHPUnit_Framework_TestCase
{
    private $log;
    
    public function setUp()
    {
        vfsStream::setup("logs");
        file_put_contents(vfsStream::url("logs/file.log"), '');
        Logger::init(vfsStream::url("logs/file.log"), 'test');
        $this->log = "test EMERGENCY: Test logger!\n".
            "test ALERT: Test logger!\n".
            "test CRITICAL: Test logger!\n".
            "test ERROR: Test logger!\n".
            "test WARNING: Test logger!\n".
            "test NOTICE: Test logger!\n".
            "test INFO: Test logger!\n".
            "test DEBUG: Test logger!\n";
        Logger::setLogFormat("%channel% %level_name%: %message%\n");
    }
    
    public function testLogging()
    {
        Logger::log(Logger::EMERGENCY, "Test logger!");
        Logger::log(Logger::ALERT, "Test logger!");
        Logger::log(Logger::CRITICAL, "Test logger!");
        Logger::log(Logger::ERROR, "Test logger!");
        Logger::log(Logger::WARNING, "Test logger!");
        Logger::log(Logger::NOTICE, "Test logger!");
        Logger::log(Logger::INFO, "Test logger!");
        Logger::log(Logger::DEBUG, "Test logger!");
        
        $this->assertStringEqualsFile(vfsStream::url("logs/file.log"), $this->log);
    }
    
    public function testLoggingMethods()
    {
        Logger::emergency("Test logger!");
        Logger::alert("Test logger!");
        Logger::critical("Test logger!");
        Logger::error("Test logger!");
        Logger::warning("Test logger!");
        Logger::notice("Test logger!");
        Logger::info("Test logger!");
        Logger::debug("Test logger!");
        
        $this->assertStringEqualsFile(vfsStream::url("logs/file.log"), $this->log);        
    }
}

