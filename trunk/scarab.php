<?php

/**
Manual catalog export
 */

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    die;
}

Mage::app();

try {
	Mage::getModel('scarabresearch/cron')->backup();
	echo "Succesfully finished.";
} catch (Exception $e) {
    Mage::printException($e);
}