<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Formatter_Firebug extends Zend_Log_Formatter_Simple implements Zend_Log_Formatter_Interface
{

    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array    $event    event data
     * @return string             formatted line to write to the log
     */
    public function format($event)
    {
        if ($event['message'] === FALSE || $event['message'] === TRUE || $event['message'] === NULL) {
            // nothing, Firebug can present it nice
            
        } elseif (empty($event['message'])) {
            $event['message'] = 'EMPTY (' . gettype($event['message']) . ')'; 
            
        } elseif (is_array($event['message'])) {
            $event['message'] = $this->_formatMageArray($event['message']);
            $event['priority'] = 9; // = TABLE
            
        } elseif (is_object($event['message'])) {
            $event['message'] = $this->_formatMageObject($event['message']);
            $event['priority'] = 9; // = TABLE
        
        } /*elseif (is_string($event['message'])) {
            preg_match('#<\?xml(.*)?>(.*)?<\/(.*)>#', $event['message'], $match);
            if (isset($match[0]) && $match[0]) {
                $event['logmode'] = 'xml';
                $event['priority'] = 9; // = TABLE
 				print_r($event['message']);               
                $event['message'] = array('Xml (please click)', array(array($event['message'])));
            }
        }*/
        
        $this->_format = '%message%';
        
        return $event;
    }

    protected function _formatMageArray($var)
    {
        $values[] = array('key', 'value');

        foreach($var as $k => $v){
            $values[] = array($k, $this->_makeFlat($v));
        }
        return $values;
    }
    
    protected function _formatMageObject($var)
    {
        try {
            $msg[] = array(get_class($var));
            if (get_parent_class($var)) {
                $msg[] = array('parent:', get_parent_class($var), '');
            }
            if (method_exists($var, 'getResourceName')) {
                $msg[] = array('resource:', $var->getResourceName(), '');
            }
            if (method_exists($var, 'getId')) {
                $msg[] = array('id:', $var->getId(), '');
            }

            $methods = get_class_methods($var);
            if (!empty($methods)) {
                $msg[] = array('methods:', '', '');
                sort($methods);
                foreach ($methods as $method) {
                    $msg[] = array('', $method, '');
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
                $msg[] = array('data:', 'key', 'value');
                foreach($variables as $k => $v){
                    $msg[] = array('', $k, $this->_makeFlat($v));
                }
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



