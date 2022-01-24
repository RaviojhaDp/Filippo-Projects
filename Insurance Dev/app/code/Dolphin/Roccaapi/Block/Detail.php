<?php
namespace Dolphin\Roccaapi\Block;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;
class Detail extends \Magento\Framework\View\Element\Template
{
	protected $customerFactory;
    protected $storeManager;
    protected $certificatoFactory;
    protected $_escaper;
    private $_transportBuilder;
    protected $_scopeConfig;
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\CustomerFactory $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        StoreManagerInterface $storeManager,
         TransportBuilder $_transportBuilder, 
         \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
         Escaper $_escaper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Dolphin\Certificato\Model\CertificatoFactory $certificatoFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory; // non-injectable objects used factory
    	$this->certificatoFactory = $certificatoFactory; 
    	$this->_escaper = $_escaper;
    	$this->_transportBuilder = $_transportBuilder;
    	 $this->scopeConfig = $scopeConfig;
    }
	

	public function emailLink($importArray)
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");


				try {

						$day = $this->scopeConfig->getValue("roccacronjobs/roccacronjobs_import/day_differ",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		                //$brand = $this->scopeConfig->getValue("roccacronjobs/roccacronjobs_import/select_brand",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                        $brand = "rocca";
		                 $senderEmail = $this->scopeConfig->getValue("roccacronjobs/roccacronjobs_import/sender_email",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		                
		                if(strtolower($brand) == "rocca"){
		                    $logo_name = "logo-rocca-new.svg";
		                }
		                

                $logo_url = $base_url_config."media/".$logo_name;

                    $importArray['brand'] = $brand;  
					$importArray['logo_url'] =  $logo_url;
					    

					       
                    $query = http_build_query($importArray);
                    $importArray['urla'] =$base_url_config."it/".$importArray['brand']."/assicurazione.html?".$query;
                       // echo $importArray['urla'];die;
                    $postObject = new \Magento\Framework\DataObject();
                    $postObject->setData($importArray);
                                $error = false;

                                $sender = [
                                    'name' => $this->_escaper->escapeHtml("Damiani Group"),
                                    'email' => $this->_escaper->escapeHtml($senderEmail),
                                ];

                                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                                $transport = $this->_transportBuilder
                                        ->setTemplateIdentifier('luxmadein_certificatodisostituzione_email_template_it') // this code we have mentioned in the email_templates.xml
                                        ->setTemplateOptions(
                                                [
                                                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                                                    'store' => '2',
                                                ]
                                        )
                                        ->setTemplateVars(['customer' => $postObject])
                                        ->setFrom($sender)
                                        //->addTo($importArray['email'])
                                        ->addTo("ravi789@mailinator.com")
                                        ->getTransport();
                                         $transport->sendMessage();
                             echo $importArray['email']." "."Mail sent succesfully"."<br>";   
                        } catch (\Exception $e) {
                         $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
                         );
                         $this->_redirect('*/*/');
                        return;
                      }

                     
		/*
		$websiteId = $this->storeManager->getWebsite()->getWebsiteId();
		$customerModel = $this->customerFactory->create();
		$customerModel->setWebsiteId($websiteId);
		if($customerModel->loadByEmail($post["email"])->getId() != "") {
			
            $customer_id = $customerModel->loadByEmail($post["email"])->getId();
            $response_data_group = $customerModel->loadByEmail($post["email"])->getGroupId();
            $this->importToCertificato($post,$customer_id);
            exit("Exist");
		}else{
			$group_id = '5';
            $password = $this->generateRandomString(8);
            $customerModel->setEmail($post['email']);
            $customerModel->setFirstname($post['name']);
            $customerModel->setLastname($post['surname']);
            $customerModel->setPassword($password);
            $customerModel->setForceConfirmed(true);
            $customerModel->setGroupId($group_id);
            $customerModel->save();
            echo "password ----->".$password;
            $this->importToCertificato($post,  $customerModel->getId(),$password);
            
	 }*/

 }

   /* public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }*/

    public function importToCertificatos($data,$customer_id,$password=NULL) {
    	/*Mail to new user registration*/
    	if($password != ""){
    	    $postObject = new \Magento\Framework\DataObject();
    	 	$data['password'] = $password;
            $postObject->setData($data);
            $error = false;
            $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('dolphin_certificato_template') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => '2',
                    ]
                )
                ->setTemplateVars(['customer' => $postObject])
                ->setFrom($sender)
                ->addTo($data["email"])
                ->getTransport();
                try{ 
                $transport->sendMessage();
              
              } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
         }
     } 
  /*Mail to new user registration*/

  /*Import To Certificato*/
    	$expire = date('Y-m-d', strtotime('+3 years'));
    	$certificato = $this->certificatoFactory->create();
    	$data["customer_group_id"] = '5'; // 5 id is for client groups
    	$data["expire_date"] = $expire;
    	$data["customer_id"] = $customer_id;
    	$certificato->setData($data)->save();
  /*Import To Certificato*/

    	//echo "<pre>";print_r($certificato->getData());exit;
    	$response = ['status' => 'true','certificato_id'=>$certificato->getId(),'created_at'=>$certificato->getCreatedAt(),'message' => 'Your Rocca Certificvato Activated.'];
    
    	//echo "<pre>";print_r($response);exit;
    }
}