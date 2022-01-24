<?php

namespace Dolphin\Certificato\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
	
    private $_transportBuilder;    
    protected $escaper;
    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper    
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context,	
	\Magento\Framework\Escaper $_escaper,
	\Magento\Framework\ObjectManagerInterface $objectmanager,
	\Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory,
	\Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder
    ) {
        $this->_transportBuilder = $_transportBuilder;
               
        $this->_escaper = $_escaper;                
       $this->_objectManager = $objectmanager;
	   $this->certificatoModelFactory = $certificatoModelFactory;
        parent::__construct($context);
    }
	
	
    public function execute()
    {
    	echo "dsfsdf--->";
    	print_r($_REQUEST); die;
    	//file_put_contents('abc.text', "here is my data " . implode('->', $_POST));
		if ($_POST):
		    print_r($_POST);
		endif;
    	die;
		//echo 'test'; 
		$model = $this->certificatoModelFactory->create()->getCollection();
		//echo '<pre>';print_r($model->getData());
		$objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
		$date = $objDate->gmtDate();
		
				function dateDiff($date1, $date2) 
					{			  						
						$date1_ts = strtotime($date1);
						$date2_ts = strtotime($date2);
						$diff = $date2_ts - $date1_ts;
						return round($diff / 86400);
					}	
			$date1 = date('Y-m-d',strtotime($date));
			$collection = $model->getData();
			$post = array();
			
			foreach($collection as $item){
				$date2 = $item['expire_date'];
				$monthDiff = dateDiff($date1, $date2);
				 $post['name'] = $item['name'];
				 $post['email'] = $item['email'];
				if($monthDiff == '180'){
					$postObject = new \Magento\Framework\DataObject();
					$postObject->setData($post);
					$error = false;

					$sender = [
						'name' => $this->_escaper->escapeHtml("Support"),
						'email' => $this->_escaper->escapeHtml($post['email']),
					];

				$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
				$transport = $this->_transportBuilder
					->setTemplateIdentifier('dolphin_expire_email_template') // this code we have mentioned in the email_templates.xml
					->setTemplateOptions(
						[
							'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
							'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
						]
					)
					->setTemplateVars(['customer' => $postObject])
					->setFrom($sender)
					->addTo($post['email'])
					->getTransport();
					//$transport->sendMessage();
				}
			}
		exit('done1');
		
    }
}