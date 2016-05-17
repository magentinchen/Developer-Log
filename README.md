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

#### Für Schreibfaule

* out(FALSE);
* out(TRUE);
* out(NULL);
* out(0);
* out('');
* out(array());
* out(array('a', 'b', 'c'));
* out(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
* out(Mage::app()->getStore());
* out(Mage::app()->getLayout()->getAllBlocks());
* out(Mage::helper('catalog/category')->getStoreCategories()->getNodes());       
* out(Mage::getModel('catalog/product')->load(1));
* out(Mage::getModel('catalog/product')->load(1)->getResource());
* out(Mage::app()->getConfig()->getNode('default/design')->asNiceXml());
* out('<?xml version="1.0"?>' . Mage::app()->getConfig()->getNode('default/design')->asXml());

### Log-Datei

* var/log/dev.log