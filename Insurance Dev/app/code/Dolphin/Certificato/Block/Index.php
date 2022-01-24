<?php
namespace Dolphin\Certificato\Block;
 
class Index extends \Magento\Framework\View\Element\Template
{
    protected $directoryBlock;
    protected $_isScopePrivate;
    protected $_customerSession;
    
    public function __construct(
         	\Magento\Framework\View\Element\Template\Context $context,
         	\Magento\Directory\Block\Data $directoryBlock,
             \Magento\Framework\ObjectManagerInterface $objectmanager,
             \Magento\Framework\App\ResourceConnection $Resource,
             \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
            \Magento\Customer\Model\SessionFactory $customerSession,
         	array $data = []
        	)
        	{
         	parent::__construct($context, $data);
         	$this->_isScopePrivate = true;
            $this->customerRepository = $customerRepository;
            $this->_customerSession = $customerSession;
         	$this->directoryBlock = $directoryBlock;
            $this->_objectManager = $objectmanager;
             $this->_resource = $Resource;
        	}
 
        	public function getCountries()
        	{ 
				$country = $this->directoryBlock->getCountryHtmlSelect();
				return $country;
        	}
			
        	public function getRegion()
        	{
				$region = $this->directoryBlock->getRegionHtmlSelect();
				return $region;
        	}
			
        	 public function getCountryAction()
        	{
				return $this->getUrl('certificato/index/country', ['_secure' => true]);
        	}
            public function getRBDetails()
            { 

                $model = $this->_objectManager->create('Magento\Customer\Model\Customer')->getCollection();
                $model->addFieldToFilter(array(
                                            array(
                                                'attribute' => 'group_id',
                                                'eq' => '3'),
                                            array(
                                                'attribute' => 'group_id',
                                                'eq' => '4')
                                        ));
                 /*$model->addFieldToFilter(array(
                                            array(
                                                'attribute' => 'firstname',
                                                'like' => '%test%'),
                                            
                                        ));*/
                $third_table_name = $this->_resource->getTableName('customer_address_entity'); 
                $model->getSelect()->joinLeft(array('third' => $third_table_name),'e.entity_id = third.parent_id')
                ->columns("e.entity_id"); 
            /*echo $model->getSelect();
            die;*/
                $html = '';
                $html .= '<option value="">'. __('Select Boutique / Jewelry Store').'</option>';  
                foreach($model->getData() as $name){
                    if($name['entity_id'] == '')
                        continue;
                     $html .= '<option value = "'.$name['entity_id'].'">'. $name['firstname'] ." ".$name['lastname'].'</option>'; 
                }
                 return $html;   
            }
            public function getStatusRBDetails()
            { 
           
                   $groupRepository  = $this->_objectManager->create('\Magento\Customer\Api\GroupRepositoryInterface');
                   $storeManager  = $this->_objectManager->get('\Magento\Store\Model\StoreManagerInterface');   
                   $storeName     = $storeManager->getStore()->getName(); //IT :1, ENG : 2  
                   $category = $this->_objectManager->get('Magento\Framework\Registry')->registry('current_category');
                   $customerSession = $this->_objectManager->create('Magento\Customer\Model\Session');
                   $display_other = false;
                   $display_to_user = false;
                   if ($customerSession->isLoggedIn()) {
                    if($customerSession->getCustomer()->getGroupId() != '5'){
                        $display_other = true;
                    }
                    if($customerSession->getCustomer()->getGroupId() == '5'){
                        $display_to_user  = true;
                    }
                    if($display_to_user){
                     $model = $this->_objectManager->create('\Dolphin\Certificato\Model\ResourceModel\Certificato\Collection')->addFieldToFilter('customer_id',$customerSession->getCustomer()->getId())->addFieldToFilter('status','1');
                     
                     $html = '';
                     $html .= '<option value="">'. __('Active warranty').'</option>';  
                     foreach($model->getData() as $name){
                        $html .= '<option value = "'.$name['certificato_id'].'">'. $name['certificato_code'] ." ". date("d-m-Y", strtotime($name['created_at'])).'</option>'; 
                     }
                     return $html;
                        
            }
        }
   }
    public function getCustomerCollection(){
        return $this->_customerSession->create();
    }
}
?>