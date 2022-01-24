<?php
 
namespace Dolphin\Certificato\Controller\Index;
 
class Autoloadcity extends \Magento\Framework\App\Action\Action
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

    	 //$zipcode = $this->getRequest()->getPostValue('zip');
     	 //$zipcode = (is_numeric($this->getRequest()->getPostValue('zip'))) ? $this->getRequest()->getPostValue('zip'):'0000';
    	 $region_code = $this->getRequest()->getPostValue('region_code');
    	 if($region_code != ''){
		 $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
		 $connection = $resource->getConnection();
		 $tableName = $resource->getTableName('luxmadein_cityfilter');
		 $attribute_information = "Select * FROM " . $tableName . " Where `region_id`=".$region_code; 
		 $result = $connection->fetchAll($attribute_information);
		 if(count($result) > '0'){
		 	$html = '';
		 	$html .= '<select name="city" id="city-2">';
            //$html .= '<option value="">'. __('Citta').'</option>';  
            foreach($result as $name){
                 $html .= '<option value = "'.$name['city'].'">'.ucwords(strtolower($name['city'])) .'</option>'; 
               }
               $html .= '</select>';
          echo $html;
          exit;
		 }
		 
		 //echo $html;
		}
		/*else{
			echo "Region/Zip value missing";exit;
		}
	*/
 }
}