<?php

/**
 * Test controller
 *
 * @category   Dev
 * @package    Dev_Log
 * @copyright  Copyright (c) 2012 Anja Vogel <mail@anjavogel.com>
 */
class Dev_Log_LogController extends Mage_Core_Controller_Front_Action
{
    
    /**
     * Log with title
     */
    protected function _dev()
    {
        $i = 0;
        
        Dev::log(FALSE, 'title ' . $i++);
        Dev::log(TRUE, 'title ' . $i++);
        Dev::log(NULL, 'title ' . $i++);
        Dev::log(0, 'title ' . $i++);
        Dev::log('', 'title ' . $i++);
        Dev::log(array(), 'title ' . $i++);
        Dev::log(array('a', 'b', 'c'), 'title ' . $i++);
        Dev::log(array('a' => 'a', 'b' => 'b', 'c' => 'c'), 'title ' . $i++);
        
        $object = new stdClass;
        $object->name = "Test";
        Dev::log($object, 'stdClass');
        
        Dev::log(Mage::app()->getStore(), 'store');
        //Dev::log(print_r(Mage::app()->getStore(), true), 'store (magento style)'); // magento style
        
        Dev::log(Mage::app()->getLayout()->getAllBlocks(), 'layout blocks');
        
        Dev::log(Mage::helper('catalog/category')->getStoreCategories()->getNodes(), 'category nodes');
        
        Dev::log(Mage::getModel('catalog/product')->load(1), 'product');
        Dev::log(Mage::getModel('catalog/product')->load(1)->getResource(), 'product resource');
        
        // backtrace
        Dev::log('firebug back trace log', Dev::TRACE);
    }
    
    /**
     * normal Mage::log()
     */
    protected function _mage()
    {
        Mage::log(FALSE);
        Mage::log(TRUE);
        Mage::log(NULL);
        Mage::log(0);
        Mage::log('');
        Mage::log(array());
        Mage::log(array('a', 'b', 'c'));
        Mage::log(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
        
        $object = new stdClass;
        $object->name = "Test";
        Mage::log($object);
        
        Mage::log(Mage::app()->getStore());
        //Mage::log(print_r(Mage::app()->getStore(), true)); // magento style
        
        Mage::log(Mage::app()->getLayout()->getAllBlocks());
        
        Mage::log(Mage::helper('catalog/category')->getStoreCategories()->getNodes());
        
        Mage::log(Mage::getModel('catalog/product')->load(1));
        Mage::log(Mage::getModel('catalog/product')->load(1)->getResource());
        
        // backtrace not possible: "Bad log priority"
    }
    
    /**
     * My test action
     */
    public function indexAction()
    {
        // backtrace
        Dev::log('firebug back trace log', 'TRACE');
        
        // xml
        Dev::log('<?xml version="1.0"?>' . Mage::app()->getConfig()->getNode('default/design')->asXml());
        Dev::log(Mage::app()->getConfig()->getNode('default/design')->asNiceXml());
        
        Dev::log((string)Mage::app()->getConfig()->getNode('modules/Dev_Log/active'));
    }
    
    /**
     * Layout & Dev::log() tests
     */
    public function devAction()
    {
        //Dev::log('before load layout');
        
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Dev::log() tests'));
        
        $this->_dev(); // between layout
        
        $this->renderLayout();
        
        Dev::log('after render layout');
    }
    
    /**
     * Layout & Mage::log() tests
     */
    public function mageAction()
    {
        Mage::log('before load layout');
        
        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Mage::log() tests'));
        
        $this->_mage(); // between layout
        
        $this->renderLayout();
        
        Mage::log('after render layout');
    }

}
