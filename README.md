# Developer-Log * Modul Dev_Log

## Extension für Magento 1.x.

Nach dem Vorbild von Mage::log() wurde Dev::log() entwickelt, um möglichst alles loggen zu können, insbesondere Magento-Objekte und Arrays mit Objekten.
Versucht man dies mit Mage::log() wird man des öfteren eine weiße Seite zu Gesicht bekommen.
Der Aufbau eines Objektes ist: Klasse, Parentklasse, Resource-String, ID, Methoden (alphabetisch), Variablen.
Enthält ein Objekt oder ein Array ein Objekt, wird nur der Typ des Objektes ausgegeben. So bleibt alles sehr übersichtlich.
XML wird - falls erkannt - formatiert und Leerwerte unterschieden und sichtbar gemacht.


### Beispiele zur Nutzung

    Dev::log(FALSE);
    Dev::log(TRUE);
    Dev::log(NULL);
    Dev::log(0);
    Dev::log('');
    Dev::log(array());
    Dev::log(array('a', 'b', 'c'));
    Dev::log(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
    Dev::log(Mage::app()->getStore());
    Dev::log(Mage::app()->getLayout()->getAllBlocks());
    Dev::log(Mage::helper('catalog/category')->getStoreCategories()->getNodes());       
    Dev::log(Mage::getModel('catalog/product')->load(1));
    Dev::log(Mage::getModel('catalog/product')->load(1)->getResource());
    Dev::log(Mage::app()->getConfig()->getNode('default/design')->asNiceXml());
    Dev::log('<?xml version="1.0"?>' . Mage::app()->getConfig()->getNode('default/design')->asXml());


### Variante für Schreibfaule

    out(FALSE);
    out(TRUE);
    out(NULL);
    out(0);
    out('');
    out(array());
    out(array('a', 'b', 'c'));
    out(array('a' => 'a', 'b' => 'b', 'c' => 'c'));
    out(Mage::app()->getStore());
    out(Mage::app()->getLayout()->getAllBlocks());
    out(Mage::helper('catalog/category')->getStoreCategories()->getNodes());       
    out(Mage::getModel('catalog/product')->load(1));
    out(Mage::getModel('catalog/product')->load(1)->getResource());
    out(Mage::app()->getConfig()->getNode('default/design')->asNiceXml());
    out('<?xml version="1.0"?>' . Mage::app()->getConfig()->getNode('default/design')->asXml());


### Einstellungen

- Log Format, z.B. "%timestamp% %title%: %message%"
- Timestamp Format, z.B. "Y-m-d H:i"
- Log File Name, z.B. dev.log, system.log, out.log, etc.
- Log to Browser (selten sinnvoll)
- Log to Firebug (benötigt FirePHP Addon)

Mit verwendet werden zusätzlich:

- Developer Client Restrictions: Allowed IPs (comma separated)
- Log Settings: Enabled


### Log-Datei

    var/log/dev.log


### Tipps

Zu den Paramatern in Mage::log() ist an zweiter Stelle ein Titel hinzugekommen, alle anderen Parameter sind gleich.  
Gerne verwende ich __ METHOD __ und __ FILE __:

    out($mixed, __ METHOD __ );
    out($mixed, __ FILE __ );
   
    Dev::log($mixed, __ METHOD __ );
    Dev::log($mixed, __ FILE __ ); 


Ausgaben im Terminal automatisch verfolgen:

    tail -f var/log/dev.log
    tail -f var/log/*.log

Live Logging mit Firebug und "Allowed IPs" Einschränkung möglich.

