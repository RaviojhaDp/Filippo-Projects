<?php namespace Luxmadein\Certificatodisostituzione\Model\Config\Source;

class Model implements \Magento\Framework\Option\ArrayInterface
{

protected $_resourceConnection;
protected $_connection;
public function __construct(
    \Magento\Framework\App\ResourceConnection $resourceConnection
) {
    $this->_resourceConnection = $resourceConnection;
}
public function toOptionArray()
{	

    $this->_connection = $this->_resourceConnection->getConnection();
    //Your custom sql query
    $query = "Select * FROM eav_attribute where entity_type_id=4"; 

    $collection = $this->_connection->fetchAll($query);
    /*echo "<pRE>";
    print_r($collection);
    die;*/
    $array = array();
    foreach ($collection as $key => $val) {
    	$array[$key]["value"] = $val['attribute_code'];
    	$array[$key]["label"] = $val['frontend_label'];
    }
    return $array;

    //return $collection;
}

/*public function toOptionArray()
 {
  return [
    ['value' => 'damiani', 'label' => __('Damiani')],
    ['value' => 'bliss', 'label' => __('Bliss')],
    ['value' => 'salvini', 'label' => __('Salvini')],
    ['value' => 'rocca', 'label' => __('Rocca')]
  ];
 }*/
}