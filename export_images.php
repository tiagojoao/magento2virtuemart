<?php

$compilerConfig = 'includes/config.php';
if (file_exists($compilerConfig)) {
    include $compilerConfig;
}

$mageFilename = 'app/Mage.php';
$maintenanceFile = 'maintenance.flag';

if (!file_exists($mageFilename)) {
    if (is_dir('downloader')) {
        header("Location: downloader");
    } else {
        echo $mageFilename." was not found";
    }
    exit;
}

if (file_exists($maintenanceFile)) {
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}

require_once $mageFilename;

#Varien_Profiler::enable();

if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}

#ini_set('display_errors', 1);

umask(0);

/* Store or website code */
$mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';

/* Run store or run website */
$mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

Mage::run($mageRunCode, $mageRunType);
?>

<style>
* {
    text-align: left !important;
}

body {
    font-family: monaco;
    color: black !important;
}
 
</style>
<?php

ini_set('memory_limit', '-1');
// ->addAttributeToFilter('entity_id',  1000);
echo '<div style="margin:100px 0px">';

$products = Mage::getModel('catalog/product')
->getCollection()
->addAttributeToSelect('*')
->addAttributeToFilter('visibility', 4)
->addAttributeToFilter('status', 1)
->addAttributeToFilter('attribute_set_id', 4);

$o = array();
foreach($products as $product) {
	$out = array();
  $_product = Mage::getModel('catalog/product')->load($product->getId());
  $media = $_product->getMediaGalleryImages();
  array_push($out, $product->getName());
  array_push($out, $product->getSku());
  $a = 0;
  echo '<pre>';
  print_r($media);
  echo '</pre>';
  foreach ($media as $item) {
  	if ( $a > 0 )
  		array_push($out, $item->getFile());
  	$a++;
  }
  array_push($o, $out); 
	file_put_contents("json/images.json",json_encode($o));
}
