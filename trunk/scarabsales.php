<?php

/**
Manual catalog export
 */

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet. Please complete installation first.";
    die;
}

Mage::app();

try {
	Mage::getModel('scarabresearch/salescron')->salesbackup();
	echo "Export successful. Your exportfile is located at www.yourwebshopdomain.com/export/scarabsales.csv. You can now close this window.";
} catch (Exception $e) {
    Mage::printException($e);
}