<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Certificato\Controller\Index;
 
class Autosuggestboutailer extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Customer\Model\CustomerFactory $customerFactory,
    \Magento\Customer\Model\Customer $customers,
     \Magento\Framework\App\ResourceConnection $Resource
	) {
		 parent::__construct($context);
        $this->_customerFactory = $customerFactory;
        $this->_customer = $customers;
		$this->_objectManager = $objectmanager;
        $this->_resource = $Resource;
	}
	
    public function execute()
    {
    
		$data = key($this->getRequest()->getPostValue());
        /*$CustomerCollection = $this->_customerFactory->create()->getCollection()
                ->addAttributeToSelect("*")
                ->addAttributeToFilter("firstname", array("like" => "%test%"))
                ->load();
                echo  $CustomerCollection->getSelect(); 
                die;*/

		$model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
		$model->addFieldToFilter('name',array('like' => '%'.$data.'%'));
        $model->addFieldToFilter('customer_group_id',array('neq' => '5'));
        $second_table_name = $this->_resource->getTableName('customer_entity'); 
        $model->getSelect()->joinLeft(array('second' => $second_table_name),'main_table.customer_id = second.entity_id');
        $third_table_name = $this->_resource->getTableName('customer_address_entity'); 
        $model->getSelect()->joinLeft(array('third' => $third_table_name),'main_table.customer_id = third.parent_id');
		if(count($model->getData()) > 0){
		foreach($model->getData() as $row){
			?>
			
			<li class='result' data-value='<?php echo $row['customer_id'] ?>'><?php echo $row['firstname']." ".$row['lastname']; ?></li> 
		 <?php }
		}
		else{
			echo __('No search result found.');
		}
	}
       
   
}