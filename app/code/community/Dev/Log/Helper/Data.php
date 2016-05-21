<?php

class Dev_Log_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function isBackend()
    {
        return Mage::app()->getStore()->isAdmin(); 
    }

    public function getTitle()
    {
        $title = '';
        $devlogTitle = Mage::registry('devlog_title');
        if ($devlogTitle) {
            $title = $devlogTitle->getTitle();
        }
        return $title;
    }

}
