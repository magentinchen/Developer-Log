<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Formatter_Simple extends Zend_Log_Formatter_Simple implements Zend_Log_Formatter_Interface
{

    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array    $event    event data
     * @return string             formatted line to write to the log
     */
    public function format($event)
    {
        $var = &$event['message'];
        
        if ($var === FALSE) {
            $var = 'FALSE';
            
        } elseif ($var === TRUE) {
            $var = 'TRUE';
            
        } elseif ($var === NULL) {
            $var = 'NULL';
            
        } elseif (empty($var)) {
            $var = 'EMPTY (' . gettype($var) . ')'; 
            
        } elseif (is_array($var)) {
            $var = $this->_formatMageArray($var);
            
        } elseif (is_object($var)) {
            $var = $this->_formatMageObject($var); // cache tag?
            
        } elseif (is_string($var)) {
            preg_match('#<\?xml(.*)?>(.*)?<\/(.*)>#', $var, $match);
            if (isset($match[0]) && $match[0]) {
                $var = $this->_formatMageXml($var);
                $event['logmode'] = 'xml';
            }
        }
        $var = print_r($var, true);
        
        if ($format = Mage::getStoreConfig('dev/devlog/timestamp')) {
            $event['timestamp'] = date($format);
        }
        $event['message'] = parent::format($event);
        $event['message'] = trim(str_replace("%count%", Dev::$count, $event['message']));
        $event['message'] = trim(str_replace("%title%", Mage::helper('devlog')->getTitle(), $event['message']));

        return $event;
    }
    
    public function setFormat($format)
    {
        $this->_format = $format;
    }

    protected function _formatMageXml($var)
    {
        $str = str_replace("\n", "", $var);
        $xml = new DOMDocument('1.0');
        $xml->preserveWhiteSpace = false;
        $xml->loadXML($str);
        $xml->formatOutput = true;
        $var = $xml->saveXML();

        return $var;
    }

    protected function _formatMageArray($var)
    {
        foreach($var as $k => $v){
            $var[$k] = $this->_makeFlat($v);
        }
        return $var;
    }

    protected function _formatMageObject($var)
    {
        try {
            $msg = get_class($var) . "\n";
            $msg .= get_parent_class($var) ? " parent: " . get_parent_class($var) . "\n" : '';
            $msg .= method_exists($var, 'getResourceName') ?  '  resource: ' . $var->getResourceName() . "\n" : '';
            $msg .= method_exists($var, 'getId') && $var->getId() ?  '  id: ' . $var->getId() . "\n" : '';

            $methods = get_class_methods($var);
            if (!empty($methods)) {
                $msg .= "  methods:\n";
                sort($methods);
                foreach ($methods as $method) {
                    $msg .= '    ' . $method . "\n";
                }
            }

            $variables = get_object_vars($var);
            if (empty($variables)) {
                if (method_exists($var, 'getData')) {
                    $variables = $var->getData();
                } elseif (method_exists($var, 'toArray')) {
                    $variables = $var->toArray();
                }
            }
            if (!empty($variables)) {
                ksort($variables);
                $msg .= "  data:\n";

                foreach($variables as $k => $v){
                    $variables[$k] = $this->_makeFlat($v);
                }

                $msg .= print_r($variables, TRUE);
            }
            return $msg;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function _makeFlat($var)
    {
        return is_object($var) ? get_class($var) . ' (object)' : $var;
    }

}
