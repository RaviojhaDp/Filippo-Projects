<?php
 
namespace Dolphin\Certificato\Controller\Index;
 
class Zipcodevalidate extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager

	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;

	}
	
    public function execute()
    {
    	 $zipcode = $this->getRequest()->getPostValue('zip');
     	 $zipcode = (is_numeric($this->getRequest()->getPostValue('zip'))) ? $this->getRequest()->getPostValue('zip'):'0000';
    	 $region_code = $this->getRequest()->getPostValue('region_code');
    	 if($zipcode != '' &&  $region_code !=''){
			 $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
			 $connection = $resource->getConnection();
			 $tableName = $resource->getTableName('luxmadein_zipcodefilter');
			 $attribute_information = "Select * FROM " . $tableName . " Where `zipcode`=".$zipcode." and `region_id`=".$region_code; 
			 $result = $connection->fetchAll($attribute_information);
			 if(count($result) > '0'){
			 	echo 1;
			 }else{
			 	echo 0;
			 }	 
		}
	}
}