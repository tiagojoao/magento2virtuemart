<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
echo '<style>body{font-size: 12px;}</style>';
// Set flag that this is a parent file.
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', dirname(__FILE__));
	require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

$json_data = file_get_contents('../json/pecas.json');
$data = json_decode($json_data, true);
$a = 0;
$err = array();
$a_count = 0;
foreach ($data as $key) {
	if ( !is_null($key[2] ) ) {
				
		$db = JFactory::getDbo();
		$n = trim($key[1]);
		$query = $db->getQuery(true); 
		
		$query->select("virtuemart_category_id")
		->from("q1asj_virtuemart_categories_pt_pt")
		->where("category_name LIKE '" . $n  ."'");

		$db->setQuery($query);
		$virtuemart_category_id = $db->loadObjectList() ;
		$category_id = $virtuemart_category_id[0]->{'virtuemart_category_id'};
		
		if ( !$virtuemart_category_id ) {	
			if ( $a_count > 0 )
			{
				echo 'ERRO: </br >';
				echo '<br />' . $db->getQuery() . '<br />';
				echo '<br />n:' . $n . '<br />';
				echo '<pre>';
				print_r($key);
				echo '</pre>';
				echo '<hr />';
				array_push($err, $key);

		
		// echo 'category_id: ' . $category_id . ' <br />';

		/*
		*	INSERT INTO on273_virtuemart_products
		*/

		$query = $db->getQuery(true); 
		
		$columns = array(
			'pordering',
			'virtuemart_vendor_id',
			'product_parent_id',
			'product_sku',
			'product_weight',
			'product_weight_uom',
			'product_length',
			'product_width',
			'product_height',
			'product_lwh_uom',
			'product_in_stock',
			'product_ordered',
			'low_stock_notification',
			'product_available_date',
			'product_special',
			'product_sales',
			'product_unit',
			'product_packaging',
			'product_params',
			'hits',
			'layout',
			'published'
			);

		$values = array( 
			0,
			1,
			0,
			$db->quote($key['sku']),
			$db->quote($key['weight']),
			$db->quote('KG'),
			10.000,
			0,
			0,
			$db->quote('M'),
			$key[2],
			0,
			5,
			$db->quote('2013-01-11 00:00:00'),
			0,
			0,
			$db->quote('KG'),
			0,
			$db->quote('min_order_level=""|max_order_level=""|step_order_level=""|product_box=""|'),
			0,
			0,
			1
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_products'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		$db->query();
		$product_id = $db->insertid();


		/*
		*	INSERT INTO on273_virtuemart_products_de_de
		*/

		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'product_s_desc',
			'product_desc',
			'product_name',
			'slug'
			);

		$values = array( 
			$product_id,
			$db->quote($key['short_description']),
			$db->quote($key['description']),
			$db->quote($key['name']),
			$db->quote($key['sku'])
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_products_pt_pt'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		$db->query();

		// $manufacturer = explode('_', $key[0]);
		// $manufacturer_id = null;
		//print_r($manufacturer);
		/*
		switch ($manufacturer[1]) {
			case 'suzuki':
				$manufacturer_id = 12;
				break;
			case 'bmw':
				$manufacturer_id = 13;
				break;
			case 'honda':
				$manufacturer_id = 9;
				break;
			case 'yamaha':
				$manufacturer_id = 11;
				break;
			case 'kawasaki':
				$manufacturer_id = 10;
				break;
			default:
				break;
		}
		*/
		/*
		*	INSERT INTO on273_virtuemart_products_de_de
		*/
		/*
		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'virtuemart_manufacturer_id'
			);

		$values = array( 
			$product_id,
			$manufacturer_id
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_product_manufacturers'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		//echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		// $db->query();
		*/
		/*
		*	INSERT INTO on273_virtuemart_product_categories
		*/
		/*
		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'virtuemart_category_id',
			'ordering'
			);

		$values = array( 
			$product_id,
			$category_id,
			$a
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_product_categories'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		//echo '<br />' . $query . '<br />'; 
		$db->setQuery($query);
		// $db->query();
		*/
		/*
		*	INSERT INTO on273_virtuemart_product_customfields
		*/
		/*
		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'virtuemart_category_id',
			'ordering'
			);

		$values = array( 
			$product_id,
			$category_id,
			$a
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_product_customfields'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		//echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		// $db->query();
		*/
		/*
		*	INSERT INTO on273_virtuemart_medias
		*/

		$query = $db->getQuery(true); 
		
		$columns = array(
			'file_url',
			'file_url_thumb',
			'virtuemart_vendor_id',
			'file_title',
			'file_description',
			'file_mimetype',
			'file_type',
			'file_is_product_image',
			'file_is_downloadable',
			'file_is_forSale',
			'ordering',
			'shared',
			'published',
			'created_on',
			'created_by',
			'modified_on',
			'modified_by',
			'locked_on',
			'locked_by'
			);
		
		$values = array( 
			$db->quote( 'images/product' . $key['image'] ),
			$db->quote( 'images/product' . $key['thumbnail'] ),
			1,
			$db->quote( substr($key['image'], 5) ),
			$db->quote( substr(substr($key['image'], 5), 0, -4) ),
			$db->quote( 'image/jpeg' ),
			$db->quote( 'product' ),
			$db->quote( 'product' ),
			0,
			0,
			0,
			0,
			1,
			$db->quote('2013-01-11 00:00:00'),
			0,
			$db->quote('2013-01-11 00:00:00'),
			0,
			$db->quote('2013-01-11 00:00:00'),
			0
			);

		$query
		->insert($db->quoteName('q1asj_virtuemart_medias'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		$db->query();
		$media_id = $db->insertid();
		
		/*
		*	INSERT INTO on273_virtuemart_medias
		*/

		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'virtuemart_media_id',
			'ordering'
			);

		$values = array( 
			$product_id,
			$media_id,
			$a
			);
		
		$query
		->insert($db->quoteName('q1asj_virtuemart_product_medias'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		$db->query();

		/*
		*	INSERT INTO on273_virtuemart_medias
		*/

		$query = $db->getQuery(true); 
		
		$columns = array(
			'virtuemart_product_id',
			'virtuemart_shoppergroup_id',
			'product_price',
			'override',
			'product_override_price',
			'product_tax_id',
			'product_discount_id',
			'product_currency',
			'product_price_publish_up',
			'product_price_publish_down',
			'price_quantity_start',
			'price_quantity_end',
			'created_on',
			'created_by',
			'modified_on',
			'modified_by',
			'locked_on',
			'locked_by'
			);

		$values = array( 
			$product_id,
			0,
			$key['price'],
			0,
			$key['price'],
			0,
			-1,
			144,
			$db->quote('2013-01-11 00:00:00'),
			$db->quote('2013-01-11 00:00:00'),
			0,
			0,
			$db->quote('2013-01-11 00:00:00'),
			0,
			$db->quote('2013-01-11 00:00:00'),
			0,
			$db->quote('2013-01-11 00:00:00'),
			0,
			);
		
		$query
		->insert($db->quoteName('q1asj_virtuemart_product_prices'))
		->columns($db->quoteName($columns))
		->values(implode(',', $values));
		
		echo '<br />' . $query . '<br />';
		$db->setQuery($query);
		$db->query();

			}
			$a_count++;
			//continue;
		}
	$a++;	
	}	
}

// if ( file_put_contents('../json/products_error.json', json_encode($err)) ) echo 'yes'; else echo 'no';