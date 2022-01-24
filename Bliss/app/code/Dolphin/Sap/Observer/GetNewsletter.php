<?php
namespace Dolphin\Sap\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Newsletter\Model\SubscriberFactory;

class GetNewsletter implements ObserverInterface
{      
  protected $_customerRepositoryInterface;
  
	/**
     * @var SubscriberFactory
     */
    private $subscriberFactory;

	/**
	 * @var \Magento\Framework\App\RequestInterface
	 */
	public $request;

	
  public function __construct(
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
	SubscriberFactory $subscriberFactory,
	\Magento\Framework\App\RequestInterface $request
	) {
	    $this->_customerRepositoryInterface = $customerRepositoryInterface;
		$this->subscriberFactory = $subscriberFactory;
		$this->request = $request;
	}

 public function execute(\Magento\Framework\Event\Observer $observer)
 {
	$post = $this->request->getPost();
	$newsletter = '173990000';
	
		if(@$post['privacy_subscribe'] == '1'){
			$newsletter = '173990001';
		}
	$postDataArray['Nome'] =  $post['firstname'] ? $post['firstname'] : '';
    $postDataArray['Cognome'] =  $post['lastname'] ? $post['lastname'] : '';
    $postDataArray['Email'] =  $post['email'] ? $post['email'] : '';
    $postDataArray['BrandOrigine'] =  '4';
	$postDataArray['Telefono'] =  @$post['phonenumber'] ? @$post['phonenumber'] : '';
    $postDataArray['Newsletter'] =  $newsletter;
    $postDataArray['Lingua'] =  "IT";
    $this->getApiCall($postDataArray);
   /* $newsletterDetailId = $observer->getEvent()->getSubscriber()->getSubscriberId();
         try {
            $collection = $this->subscriberFactory->create()->getCollection()
                        ->addFieldToFilter('subscriber_id', $newsletterDetailId);
			echo "<pre>";
	print_r($collection->getData());
	die;
        }catch (\Exception $e) {
                return null;
        }*/
 }
 
   public function getApiCall($postDataArray){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/NewsletterCRM.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://damiani-api-dev.p365.it/api/Ecommerce/NewsletterRegistration",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_HEADER => 1,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postDataArray),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic JERNTi91c2VyYWNjZXNzOi1AP3UleGd6NlJ5cURBeWRnZVc1Y1pSIV5IVEdBR044blNoM3JQQzRjV3ByVXJtOGtWR3FLLVhWLUVRcw==",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));
      $logger->info('---Customer Register event after response CRM-------');
      $response = curl_exec($curl);    
      $logger->info(print_r($postDataArray,true));     
	  $logger->info(print_r($response,true));     
  }
}