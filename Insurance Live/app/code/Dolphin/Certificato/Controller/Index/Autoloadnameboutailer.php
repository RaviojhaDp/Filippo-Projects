<?php
 
namespace Dolphin\Certificato\Controller\Index;
 
class Autoloadnameboutailer extends \Magento\Framework\App\Action\Action
{
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Customer\Model\AddressFactory $addressFactory,
     \Magento\Customer\Model\Session $customerSession,
    \Magento\Framework\App\ResourceConnection $Resource
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->addressFactory = $addressFactory;
		$this->_resource = $Resource;
        $this->customerSession = $customerSession;
	}
	
    public function execute()
    {
    	$customerNameget = $this->getRequest()->getPostValue('param');
        $customerName = trim(str_replace('_', ' ', $customerNameget));
        $customerId = $this->customerSession->getCustomer()->getId();
        $group = $this->customerSession->getCustomer()->getGroupId();
    	$model = $this->_objectManager->create('Magento\Customer\Model\Customer')->getCollection();
                $model->addFieldToFilter(array(
                                            array(
                                                'attribute' => 'group_id',
                                                'eq' => '3'),
                                            array(
                                                'attribute' => 'group_id',
                                                'eq' => '4')
                                        ));
                 $model->addFieldToFilter(array(
                                            array(
                                                'attribute' => 'firstname',
                                                'like' => '%'.$customerName.'%'),
                                            array(
                                                'attribute' => 'lastname',
                                                'like' => '%'.$customerName.'%')
                                            
                                        ));
                $third_table_name = $this->_resource->getTableName('customer_address_entity'); 
                $model->getSelect()->joinLeft(array('third' => $third_table_name),'e.entity_id = third.parent_id')
                ->columns("e.entity_id") 
                ->columns(['joinlastname'=>'e.lastname'])
                ->columns(['joinfirstname'=>'e.firstname']);  

         if(count($model->getData()) > 0){
		foreach($model->getData() as $row){?>
			<li class='result' data-value='<?php echo $row['entity_id'] ?>'><?php
            if (($group != '5') && ($group != '1')) {
                echo $row['joinfirstname']." ".$row['joinlastname'];
            }else{
             echo $row['joinfirstname']; 
            }?>
            </li> 
            <?php 
		  }
		}
		else{
			echo  __('No search result found'); 
		}
        exit;

    
	}
}