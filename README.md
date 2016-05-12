# Developer-Log

### Beispiele zur Nutzung

* Dev::log(FALSE);
* Dev::log(TRUE);
* Dev::log(NULL);
* Dev::log(0);
* Dev::log('');
* Dev::log(array());
* Dev::log(array('a', 'b', 'c'));
* Dev::log(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
* Dev::log(Mage::app()->getStore());
* Dev::log(Mage::app()->getLayout()->getAllBlocks());
* Dev::log(Mage::helper('catalog/category')->getStoreCategories()->getNodes());       
* Dev::log(Mage::getModel('catalog/product')->load(1));
* Dev::log(Mage::getModel('catalog/product')->load(1)->getResource());
* Dev::log(Mage::app()->getConfig()->getNode('default/design')->asNiceXml());
* Dev::log('<?xml version="1.0"?>' . Mage::app()->getConfig()->getNode('default/design')->asXml());
