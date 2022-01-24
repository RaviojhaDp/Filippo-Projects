<?php

namespace Luxmadein\Certificatodisostituzione\Cron;
 
class Insuranceorder
{
    protected $logger;
    private $_transportBuilder;
    protected $escaper;
    protected $_objectManager;
    protected $_scopeConfig;
 
    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder,
        \Magento\Framework\Escaper $_escaper
    ) {
        $this->logger = $loggerInterface;
        $this->_escaper = $_escaper;
        $this->_objectManager = $objectmanager;
        $this->scopeConfig = $scopeConfig;
        $this->_transportBuilder = $_transportBuilder;

    }
 
    public function execute() {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $base_url_config = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue("web/unsecure/base_url");
        $Enable = $this->scopeConfig->getValue("certificatodisostituzione/general/active",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($Enable == '0'){
            return true;
        }
        $model = $this->scopeConfig->getValue("certificatodisostituzione/general/model",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
         $serial = '';
         $equipment = '';
        $day = $this->scopeConfig->getValue("certificatodisostituzione/general/day_differ",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $brand = $this->scopeConfig->getValue("certificatodisostituzione/general/select_brand",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
     $senderEmail = $this->scopeConfig->getValue("certificatodisostituzione/general/sender_email",\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    if(strtolower($brand) == "damiani"){
    $logo_name = "logo-damiani-new.svg";
    }
    if(strtolower($brand) == "salvini"){
        $logo_name = "logo-salvini-new.svg";
    }
    if(strtolower($brand) == "rocca"){
        $logo_name = "logo-rocca-new.svg";
    }
    if(strtolower($brand) == "bliss"){
        $logo_name = "logo-bliss-new.svg";
    }

    $logo_url = $base_url_config."media/".$logo_name;

    $orderDatamodel = $this->_objectManager->get('Magento\Sales\Model\Order')->getCollection();  
    $date = (new \DateTime())->modify('-'.$day.' day');
    $createdAt =$date->format('Y-m-d');
    //$createdAt = "2019-08-26";
    $orderDatamodel->addFieldToFilter('main_table.updated_at', ['lteq' =>$date->format('Y-m-d 23:59:59')])->addFieldToFilter('main_table.updated_at', ['gteq' => $date->format('Y-m-d 00:00:01')])
    ->addFieldToFilter('main_table.status', ['eq' => "complete"]);
    $orderDatamodel->getSelect()
     ->join(
            ['sales_item' => 'sales_order_item'],
            'main_table.entity_id = sales_item.order_id'
    )
     ->columns("sales_item.product_options");

     foreach($orderDatamodel->getData() as $orderData){

        //$this->logger->info("-----------------------------START MASIL-------------------------------------".print_r($orderData));
        $orderItenDatamodel = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderData['entity_id']);
        $productLoad = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($orderData['product_id']);
        
        $items = $orderItenDatamodel->getAllVisibleItems();
        
        $IncrementId = $orderItenDatamodel->getData("increment_id");
        $customerID = $orderItenDatamodel->getData("customer_id");

        $customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($customerID);
        $BillingAddress = $orderItenDatamodel->getBillingAddress()->getData();
        $postcertiArray['flag'] = '1';
        $postcertiArray['customer_id'] = $orderItenDatamodel->getData("customer_id");
        $postcertiArray['firstname'] = $customerObj->getData("firstname");
        $postcertiArray['lastname'] = $customerObj->getData("lastname");
        $postcertiArray['gender'] = $customerObj['gender'];
        $postcertiArray['dob'] = $customerObj['dob'];
        $postcertiArray['telephone'] = $BillingAddress['telephone'];
        $postcertiArray['street'] = $BillingAddress['street'];
        $postcertiArray['city'] = $BillingAddress['city'];
        $postcertiArray['region'] = $BillingAddress['region'];
        $postcertiArray['country_id'] =$BillingAddress['country_id'];
        $postcertiArray['postcode'] =$BillingAddress['postcode'];
        $postcertiArray['region'] =$BillingAddress['region'];
        $postcertiArray['email'] =$customerObj['email'];
        $postcertiArray['brand'] =  $brand;
        $postcertiArray['equipment'] =  $productLoad->getData($equipment);
        $postcertiArray['serial'] =  $productLoad->getData($serial);
        $postcertiArray['model'] =  $productLoad->getData($model);
        $postcertiArray['created_at'] =$orderItenDatamodel->getData("created_at");
        $postcertiArray['order_id'] =$orderItenDatamodel->getData("increment_id");
        $postcertiArray['logo_url'] =  $logo_url;

    try {

        $query = http_build_query($postcertiArray);
        $postcertiArray['urla'] =$base_url_config."it/".$postcertiArray['brand']."/assicurazione.html?".$query;
        $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($postcertiArray);
                $error = false;

                $sender = [
                    'name' => $this->_escaper->escapeHtml("Support"),
                    'email' => $this->_escaper->escapeHtml($senderEmail),
                ];

                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $transport = $this->_transportBuilder
                        ->setTemplateIdentifier('luxmadein_certificatodisostituzione_email_template_it') // this code we have mentioned in the email_templates.xml
                        ->setTemplateOptions(
                                [
                                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                                ]
                        )
                        ->setTemplateVars(['customer' => $postObject])
                        ->setFrom($sender)
                        ->addTo("filippomaria123@mailinator.com")
                        ->getTransport();
                $transport->sendMessage();          
               
        } catch (\Exception $e) {
         $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
         );
         $this->_redirect('*/*/');
        return;
      }
                       
    }
        $this->logger->debug('Dolphin\Insuranceorder\Cron\Insuranceorder');

    }
}