<?php
 
/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Dolphin\Claim\Controller\Index;
 
class Autosuggestcerti extends \Magento\Framework\App\Action\Action
{
	protected $_customerFactory;

   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
	 \Magento\Customer\Model\CustomerFactory $customerFactory,
	  \Magento\Customer\Model\Session $customerSession,
    \Magento\Framework\ObjectManagerInterface $objectmanager
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->_customerFactory = $customerFactory;
		$this->customerSession = $customerSession;
	}
	
    public function execute()
    {

    	$customerId = $this->customerSession->getCustomer()->getId();
    	$group = $this->customerSession->getCustomer()->getGroupId();
		$dataget = $this->getRequest()->getPostValue('key');
		$data = trim(str_replace('_', ' ', $dataget));
		$cat_name = strtolower($this->getRequest()->getPostValue('cat_name'));

		$model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
		$model->addFieldToFilter(array('name','surname'),
                                    array(
                                        array('like'=>'%'.$data.'%'), 
                                        array('like'=>'%'.$data.'%')
                                    ));
		
		$model->addFieldToFilter(array('name_boutique_retailer'),
                                    array(
                                        array('eq'=>$customerId)
                                        
                                    ));
		//$model->addFieldToFilter('name',array('like' => '%'.$data.'%'));
		$model->addFieldToFilter('status',array('like' => '1'));
		$model->addFieldToFilter('brand', array('eq' => $cat_name))->getSelect()->group('customer_id');
		
		if(count($model->getData()) > 0){
		
		foreach($model->getData() as $row){
			?>
			<?php /*<li class='result' data-value='<?php echo $row['customer_id'] ?>'><?php echo @$row['name']." ".@$row['surname']." ".@$row['equipment']." ". date("d-m-Y", strtotime($row['created_at'])) ?></li> */?>
                        <li class='result'data-certi-value='<?php echo $row['certificato_id'] ?>' data-value='<?php echo $row['customer_id'] ?>'><?php echo @$row['name']." ".@$row['surname'] ?></li> 
		 <?php }
		 exit;
		}
		else{
			echo  __('No search result found'); 
			exit;
		}
	}
      
   
}