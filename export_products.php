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
<!--
<style>
* {
    text-align: left !important;
}

body {
    font-family: monaco;
    color: black !important;
}
 
</style>-->
<?php
/*
ini_set('memory_limit', '-1');
// ->addAttributeToFilter('entity_id',  1000);
echo '<div style="margin:100px 0px">';


// Peças Usadas

$collection_of_products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('attribute_set_id',  9);

$out = array();
$a = 1;
foreach ($collection_of_products as $product) {

	//if ( $product['stock_item']['is_in_stock'] )
	//{
    $url = array();
    $p = array();
    $p = $product->getData();
    $categoryIds = $product->getCategoryIds();
    echo '<h1>' . $product->getName() . '</h1>';
    
    $category = Mage::getModel('catalog/category')->load($categoryIds[0]);
    $url = $category['url_path'];
    $url = explode("/", $url);
    $slug = str_replace('-', ' ', strtolower($category->getName())) . '_' . $url[1];
    print_r($category);
    array_push($p, $category['name']);
    // array_push($p, $slug);
    // array_push($p, $category->getName()); 
    array_push($out, $p);
  //}
}

file_put_contents("json/motas.json",json_encode($out));

// header("Location: http://" . $_SERVER['SERVER_NAME'] . "/products.json");
// var_dump($collection_of_products->getFirstItem()->getData());
echo '</div>';


