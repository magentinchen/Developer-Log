<?php

/**
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_Model_Writer_Browser extends Zend_Log_Writer_Stream
{

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        return $this->_writeBrowser($event['message']);
    }
   
    protected function _writeBrowser($line)
    {
        if (!Mage::getStoreConfigFlag('dev/devlog/browser')) {
            return;
        }
        
        $layout = Mage::app()->getLayout();
        $blocks = $layout->getAllBlocks();
       
        if (isset($blocks['before_body_end'])) { // Mage::run() frontend and backend
            
            $line .= "\n";
            
            if (isset($blocks['devlog'])) {
                $lines = $blocks['devlog']->getLines();
                $lines[] = $line;
                $blocks['devlog']->setLines($lines);
                
            } else {
                $block = $layout->createBlock(
                    'core/template',
                    'devlog',
                    array('template' => 'dev/log.phtml')
                );
                $block->setLines(array($line));
                
                $blocks['before_body_end']->append($block);
            }
 
        } elseif (!Mage::app()->getRequest()->getControllerName()) { // Mage::app() test file
            
            echo "<pre>";
            print_r($line);
            echo "</pre>\n";
        
        } else { // controller action without layout #EB5E00
         
            Mage::app()->getResponse()->appendBody(
            	'<pre style="text-align:left;color:#000;background-color:#fff">' . $line . '</pre>'
            );
        }
    }


    /**
     * Not realy a good way to have my custom formater,
     * but Mage class sets the formater to Zend_Log_Formatter_Simple
     * @param Zend_Log_Formatter_Interface $formatter
     * @see Zend_Log_Writer_Abstract::setFormatter()
     */
    public function setFormatter(Zend_Log_Formatter_Interface $formatter)
    {
        if (!$this->_formatter && $formatter instanceof Dev_Log_Model_Formatter_Simple) {
            $this->_formatter = new Dev_Log_Model_Formatter_Simple();
        }
    }

}
