<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2016 Anja Vogel <mail@anjavogel.com>
 */
class Dev
{
    const TRACE = 'TRACE';
    
    /**
     * Wrapper for Mage::log() with title
     *
     * @param string $message
     * @param string $title
     * @param bool $forceLog
     */
    public static function log($message, $title = '', $level = null, $file = null, $forceLog = false)
    {
        if (empty($file)) {
            $file = Mage::getStoreConfig('dev/devlog/logfile_name');
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

        // log
        self::out($message, $level, $file, $forceLog);
    }

    public static function out($message, $level, $file, $forceLog)
    {        
        if (!Mage::getConfig()) {
            return;
        }

        try {
            $logActive = Mage::getStoreConfig('dev/log/active');
            if (empty($file)) {
                $file = Mage::getStoreConfig('dev/log/file');
            }
        }
        catch (Exception $e) {
            $logActive = true;
        }

        if (!Mage::getIsDeveloperMode() && !$logActive && !$forceLog) {
            return;
        }

        static $loggers = array();

        $level  = is_null($level) ? Zend_Log::DEBUG : $level;
        $file = empty($file) ? 'system.log' : $file;

        try {
            if (!isset($loggers[$file])) {
                $logDir  = Mage::getBaseDir('var') . DS . 'log';
                $logFile = $logDir . DS . $file;

                if (!is_dir($logDir)) {
                    mkdir($logDir);
                    chmod($logDir, 0755);
                }

                if (!file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    chmod($logFile, 0644);
                }

                $format = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
                $formatter = new Zend_Log_Formatter_Simple($format);
                $writerModel = (string)Mage::getConfig()->getNode('global/log/core/writer_model');
                if (!Mage::app() || !$writerModel) {
                    $writer = new Zend_Log_Writer_Stream($logFile);
                }
                else {
                    $writer = new $writerModel($logFile);
                }
                $writer->setFormatter($formatter);
                $loggers[$file] = new Zend_Log($writer);
            }

            $loggers[$file]->log($message, $level);
        }
        catch (Exception $e) {
        }
    }

}


