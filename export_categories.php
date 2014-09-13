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
echo '<div style="margin:400px 0px">';


$collection_of_products = Mage::getModel('catalog/category')->getCollection()->addFieldToFilter('display_mode', 'PRODUCTS')->addAttributeToSelect('url_path');

$url = array();
$category = array();

function clean( &$url ) 
{
	$url = trim($url);
	$url = str_replace('-', ' ', $url);
	$url = str_replace('.html', '', $url);
	return $url;
}


foreach ($collection_of_products as $product) 
{
    echo '<pre>';
print_r($product);
echo '</pre>';

    $url = explode('/', $product->getData('url_path'));
  	echo '<pre>';
    //print_r($url);
	echo '</pre>';
    //if ( $url[0] == 'pecas-usadas' )
    //{
	$url[0] = clean($url[0]);
	$url[1] = ($url[1] != '') ? clean($url[1]) : '';
	$url[2] = ($url[2] != '') ? clean($url[2]) : '';
	$url[3] = ($url[3] != '') ? clean($url[3]) : '';

    	if ( ( !$category[$url[0]] ) && $url[0] )
    	{
    		$category[$url[0]] = null;
    	}
    	if ( (!in_array($url[1], $category[$url[0]] ) )  && ( $url[1] != '') )
    	{
    		if ( $url[0] != 'pecas usadas')
    			$category[$url[0]][] = $url[1];	
	    }
    	if ( (!in_array($url[2], $category[$url[0]][$url[1]]) )  && ($url[2] != '') )
    	{

			//$category[$url[0]][$url[1]][] = $url[2];	
			// echo 'url2: ' . $url[2] . ' <br />';
    	}
		if ( (!in_array($url[3], $category[$url[0]][$url[1]][$url[2]] ) ) && ( $url[3] != '') )
		{

			// array_push($category[$url[0]][$url[1]][$url[2]], $url[3]);
			$category[$url[0]][$url[1]][$url[2]][] = $url[3];
			// array_push($category[$url[0]][$url[1]][$url[2]][$url[3]], '');
		}
    //}
}

echo '<pre>';
echo json_encode($category);
echo '</pre>';
//file_put_contents("categories.json",json_encode($category));

header("Location: http://http://www.motojacs.pt/categories.json");

echo '</div>';


