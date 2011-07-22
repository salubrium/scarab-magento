echo "Catalog export in progress...";
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
	Mage::getModel('scarabresearch/cron')->backup();
	echo "Export successful. Fresh catalog is located at www.yourwebshopdomain.com/export/scarab.csv. You can now close this window.";
} catch (Exception $e) {
    Mage::printException($e);
}