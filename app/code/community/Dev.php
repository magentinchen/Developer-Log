<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2016 Anja Vogel <mail@anjavogel.com>
 */
class Dev
{
    const TRACE = 'TRACE';

    static $count = 0;
    static $loggers = array();

    /**
     * Similar like Mage::log(), with title
     *
     * @param string $message
     * @param string $title
     * @param bool $forceLog
     */
    public static function log($message, $title = '', $level = null, $file = null, $forceLog = false)
    {
        if (!Mage::getConfig()) {
            return;
        }

        try {
            $logActive = Mage::getStoreConfig('dev/log/active');
            if (empty($file)) {
                $file = Mage::getStoreConfig('dev/devlog/logfile_name');
            }
        } catch (Exception $e) {
            $logActive = true;
        }

        if (!Mage::getIsDeveloperMode() && !$logActive && !$forceLog) {
            return;
        }


        // title
        $devlogTitle = Mage::registry('devlog_title');

        if (!$devlogTitle) {
            $devlogTitle = new Varien_Object();
            $devlogTitle->setTitle($title);
            Mage::register('devlog_title', $devlogTitle);
        } else {
            $devlogTitle->setTitle($title);
        }

        self::$count++;


        $level = is_null($level) ? Zend_Log::DEBUG : $level;
        $file = empty($file) ? 'system.log' : $file;

        try {
            if (!isset(self::$loggers[$file])) {
                $logDir = Mage::getBaseDir('var') . DS . 'log';
                $logFile = $logDir . DS . $file;

                if (!is_dir($logDir)) {
                    mkdir($logDir);
                    chmod($logDir, 0755);
                }

                if (!file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    chmod($logFile, 0644);
                }

                $format = Mage::getStoreConfig('dev/devlog/format'); // . PHP_EOL;
                $formatter = new Dev_Log_Model_Formatter_Simple($format);
                $writer = new Dev_Log_Model_Writer_Stream($logFile);

                $writer->setFormatter($formatter);
                self::$loggers[$file] = new Zend_Log($writer);
            }

            self::$loggers[$file]->log($message, $level);

        } catch (Exception $e) {
        }
    }

}

