<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Writer_Stream extends Dev_Log_Model_Writer_Firebug
{

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        if (!Mage::helper('core')->isDevAllowed()) {
            return;
        }
        
        // prevent firebug loop when HEADERS ALREADY SENT
        if (!(is_string($event['message']) && 'HEADERS ALREADY SENT' == substr($event['message'], 0, 20))) {
            
            // firebug writer
            $event['logmode'] = '';
            
            $this->_formatter = new Dev_Log_Model_Formatter_Firebug();
            $firebugEvent = $this->_formatter->format($event);
            $this->_writeFirebug($firebugEvent);
        }
                
        // other writer
        /*if ($event['priority'] < 0) {
            $event['priority'] = 0;
        }
        if ($event['priority'] > 7) {
            $event['priority'] = 7;
        }*/
        $event['priority'] = max(Zend_Log::EMERG, $event['priority']);
        $event['priority'] = min(Zend_Log::DEBUG, $event['priority']); 
           
        $this->_formatter = new Dev_Log_Model_Formatter_Simple();
        $this->_formatter->setFormat(Mage::getStoreConfig('dev/devlog/format'));
        
        $event = $this->_formatter->format($event);
        $line = $event['message'];
      
        $this->_writeStream($line);
        
        if ('xml' == $event['logmode']) {
            $line = htmlentities($event['message']); 
        }
        $this->_writeBrowser($line);

        Mage::unregister('devlog_title');
    }
    
    protected function _writeStream($line)
    {
        // for title
        if (is_string($line) && ':' != substr($line, -1)) {
            $line .= "\n";
        }
        $line .= "\n";
        
        if (false === @fwrite($this->_stream, $line)) {
            #require_once 'Zend/Log/Exception.php';
            throw new Zend_Log_Exception("Unable to write to stream");
        }
    }

}
