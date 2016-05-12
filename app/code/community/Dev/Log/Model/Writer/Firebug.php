<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Writer_Firebug extends Dev_Log_Model_Writer_Browser
{
    
    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        return $this->_writeFirebug($event['message']);
    }
    
    /**
     * LOG, INFO, WARN, ERROR, TRACE, EXCEPTION, TABLE
     * 
    const EMERG   = 0;  // Emergency: system is unusable
    const ALERT   = 1;  // Alert: action must be taken immediately
    const CRIT    = 2;  // Critical: critical conditions
    const ERR     = 3;  // Error: error conditions
    const WARN    = 4;  // Warning: warning conditions
    const NOTICE  = 5;  // Notice: normal but significant condition
    const INFO    = 6;  // Informational: informational messages
    const DEBUG   = 7;  // Debug: debug messages
     */
    protected function _writeFirebug($event) //$var, $label=null, $style=null, $options=array())
    {
        if (!Mage::getStoreConfigFlag('dev/devlog/firebug')) {
            return;
        }
        
        //$this->setFormatter(new Zend_Log_Formatter_Firebug());
        //$line = $this->_formatter->format($event);
        
        $var = $event['message'];
        $title = Mage::helper('devlog')->getTitle();
        if ('TRACE' == $title) {
            $event['priority'] = 8;
            $title = (string)$event['message']; 
        }
        
        $label = strlen($title) ? $title : null;
        if (9 == (int)$event['priority']) {
            $label .= strlen($label) ? '' : '(object or array)';
        }
        $options = array(
            'maxObjectDepth' => 1,
            'maxArrayDepth' => 1,
            'useNativeJsonEncode' => true,
            'includeLineNumbers' => false
        ); 
               
        switch ((int)$event['priority']) {
            case 0:
            case 1:
            case 2:
            case 3:
                $style = 'ERROR';
                break;
            case 4:
                $style = 'WARN';
                break;
            case 5:
            case 6:
                $style = 'INFO';
                break;
            case 7:
                $style = 'LOG';
                break;
            case 8:
                $style = 'TRACE'; // WICHTIG!!!!!
                break;
            case 9:
                $style = 'TABLE';
                break;
            case 10:
                $style = 'EXCEPTION';
                break;
            default:
                $style = null;
        }
              
        try {
            
            Zend_Wildfire_Channel_HttpHeaders::getInstance()
                ->setRequest(Mage::app()->getRequest())
                ->setResponse(Mage::app()->getResponse());
    
            Zend_Wildfire_Plugin_FirePhp::getInstance()->send($var, $label, $style, $options); // *)
            Zend_Wildfire_Channel_HttpHeaders::getInstance()->flush();

            if (Mage::app()->getResponse()->canSendHeaders()) {
                Mage::app()->getResponse()->sendHeaders();
            }
            
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Not realy a good way to have my custom formater,
     * but Mage class sets the formater to Zend_Log_Formatter_Simple
     */
    /*public function setFormatter(Zend_Log_Formatter_Interface $formatter)
    {
        if (!$this->_formatter && $formatter instanceof Zend_Log_Formatter_Firebug) {
            $this->_formatter = new Zend_Log_Formatter_Firebug();
        }
    }*/

}
