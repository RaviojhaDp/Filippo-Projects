<?php

namespace Dolphin\Claim\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 
use Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;

class Claim extends \Magento\Framework\App\Action\Action
{
	protected $_mediaDirectory;
	protected $_fileUploaderFactory;
	protected $_messageManager;
	protected $resultJsonFactory;
  protected $helperData;

	public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
	\Magento\Framework\Filesystem $filesystem,
	\Magento\Framework\Message\ManagerInterface $messageManager,
	\Dolphin\Claim\Model\ClaimFactory $claimModelFactory,
    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
      \Dolphin\Claim\Helper\Data $helperData,
      \Magento\Framework\View\Result\PageFactory $pageFactory,
    \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
    TransportBuilder $_transportBuilder,
    Escaper $_escaper,  
    JsonFactory $resultJsonFactory
	) {
     $this->helperData = $helperData;
   
		$this->_objectManager = $objectmanager;
		$this->claimModelFactory = $claimModelFactory;
		$this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
		$this->_fileUploaderFactory = $fileUploaderFactory;
		$this->_messageManager = $messageManager;
		$this->resultJsonFactory = $resultJsonFactory;
    $this->_pageFactory = $pageFactory;
   $this->_escaper = $_escaper;
   $this->_transportBuilder = $_transportBuilder;
   $this->directory_list = $directory_list; 
     parent::__construct($context);
	}
	
    public function execute()
    {

      $filesystem = $this->_objectManager->get('Magento\Framework\Filesystem');
      $directoryList = $this->_objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');
    	$resultJson = $this->resultJsonFactory->create();
    	$post = $this->getRequest()->getPostValue();
      $this->setClaimCrmApi($post);
      if(strtolower($post['storename'])== "italiano"){
                  $lang = "it";
              }else{
                  $lang = "en";
              }
        if(isset($post['left-city']) && $post['left-city'] != ''){
           $post['left_city'] = @$post['left-city'];
        }else{
           $post['left_city'] = @$post['city']; 
        }

        if(isset($post['city_stepone']) && $post['city_stepone'] != ''){
           $post['city'] = $post['city_stepone'];
        }
    
		$resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);	
        if (isset($_FILES['damiani_spa']['name']) && $_FILES['damiani_spa']['name'] != '') {
		 try {
                    $target = $this->_mediaDirectory->getAbsolutePath('damiani_spa/');
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'damiani_spa']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc','pdf']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($target);
                    $fullname = $_FILES['damiani_spa']['name'];
                    //$post['damiani_spa'] = $fullname;
                    $post['damiani_spa'] = $uploader->getUploadedFileName();
                } catch (\Exception $e) {     
            }
        }
        if (isset($_FILES['complaint_replica']['name']) && $_FILES['complaint_replica']['name'] != '') {
             
                try {
                    $target = $this->_mediaDirectory->getAbsolutePath('complaint/');
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'complaint_replica']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc','pdf']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($target);
                    $fullname = $_FILES['complaint_replica']['name'];
                    //$post['complaint'] = $fullname;
                    $post['complaint'] = $uploader->getUploadedFileName();
                    //exit('------RVVVVV--');
                } catch (\Exception $e) {                    
              }
            }

        
         if (isset($_FILES['authenticity']['name']) && $_FILES['authenticity']['name'] != '') {
         try {
                    $target = $this->_mediaDirectory->getAbsolutePath('authenticity/');
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'authenticity']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc','pdf']);
                    $uploader->setAllowRenameFiles(true);
                    $result = $uploader->save($target);
                    $fullname = $_FILES['authenticity']['name'];
                    //$post['authenticity'] = $fullname;
                    $post['authenticity'] = $uploader->getUploadedFileName();
                } catch (\Exception $e) {   
            }
        }
  	 
		$model = $this->claimModelFactory->create();		
        if(isset($post['left_date'])){
             $post['left_date'] = str_replace('/', '-', $post['left_date'] );   
              $post['left_date'] = date('Y-m-d', strtotime($post['left_date']));
            //$post['left_date']= new \DateTime($post['left_date']);
           }
           if(isset($post['date_of_termination'])){
             $post['date_of_termination'] = str_replace('/', '-', $post['date_of_termination'] );   
              $post['date_of_termination'] = date('Y-m-d', strtotime($post['date_of_termination']));
           // $post['date_of_termination']= new \DateTime($post['date_of_termination']);
           }
	        if(isset($post['created_at'])){
            $post['created_at']= date('Y-m-d');
           }

         $faber_post = json_decode($post['faber_post'] , true);
         $certiId = substr($faber_post['certificato_code'], 1);
         $session = $this->_objectManager->get('Magento\Customer\Model\Session');
         $session->setWarranty($faber_post['certificato_code']);
		     $session->setBrand($post['parent_cat']);
         $model->setData($post);
         $model->setSex($faber_post['sex']);
         $model->setCountryId($faber_post['country_id']);
         $model->setProvince($faber_post['region']);
         $model->setCertificatoId("$certiId");
         $model->setCertiCreateAt($faber_post['created_at']);
         $model->setBrand($faber_post['brand']);
         $model->setCustomerId($faber_post['customer_id']);
	       $model->setStatusClaim('1');
      try{
        
          $response = $this->helperData->callClaimApi($faber_post, $post);
          if($response['return'] == "OK"){
            $docx = file_get_contents($response['parameter']['DOCUMENT_URL']);
            $resultPage = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW)
                ->setHeader('Content-Type', 'application/pdf', true)
                ->setHeader('Content-Disposition', "attachment; filename=\"".$faber_post['certificato_code'].".pdf\"", true)
                ->setContents($docx)
                ;
            $file_name = "/var/www/html/pub/media/Signed_Document";
            $media = $filesystem->getDirectoryWrite($directoryList::MEDIA);
            $path = "Signed_Document/Claim/".$faber_post['certificato_code'].".pdf";
            $contents = $docx;
            $media->writeFile($path,$contents);

          if(is_numeric($certiId)){
                $certi = $certiId;
               }else{
                  $certi = ltrim(substr($certiId, 1),"0");
              }
          //curl_close($curl);
            }else{
              $response = ['status' => 'false', 'message' => $response['message'] ];
            }
        $objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $cdate = $objDate->gmtDate();
		    $claimSave = $model->save();
        if($claimSave){
           $this->_redirect('success-claim?certi='.$faber_post['certificato_code'].'&brand='.$claimSave->getBrand().'&create_at='.$cdate); 
        } 

       $fileName = "Denuncia N. ".$faber_post['certificato_code'].".pdf";
       $path = $this->directory_list->getPath('media')."/Signed_Document/Claim/".$faber_post['certificato_code'].".pdf";
       $postObject = new \Magento\Framework\DataObject();
       $postObject->setData($claimSave->getData());
       $error = false;
       $sender = [
        'name' => $this->_escaper->escapeHtml("Damiani Group"),
        'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
       ];
      if(strtolower($post['storename'])== "italiano"){
                   $storeScope = '1';
              }else{
                   $storeScope = '2';
              }
      //  $storeScope = '1';
      
      // $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
      $transport = $this->_transportBuilder
                ->setTemplateIdentifier('claim_email_template_register_success') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                  [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' =>  $storeScope,
                      ]
                    )
                ->setTemplateVars(['customer' => $postObject])
                ->setFrom($sender)
                ->addTo($claimSave->getEmail())
                ->addAttachment($path, $fileName)  
                ->getTransport();
               // $transport->sendMessage();
                try{ 
                $transport->sendMessage();
                } catch (\Exception $e) {
                  return $response = ['status' => 'false', 'message' => $e->getMessage()];
                }
        $response = ['status' => 'true','claim_id'=>$model->getClaimId(),'created_at'=>$model->getCreatedAt(),'message' => 'You Claim has been Activated'];
        } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
        }
        return $resultJson->setData($response);
    }

    public function setClaimCrmApi($postUpdate)
    {

      $faber_post = json_decode($postUpdate['faber_post'], true);
      $api_insurance_params = [
          "Email" => @$postUpdate['email'] ? @$postUpdate['email'] : "",
            "Equipment" => @$postUpdate['equipment'] ? @$postUpdate['equipment'] : "",
            //"Equipment" => '73152258',
            "CodiceMateriale" => @$postUpdate['model'] ? @$postUpdate['model'] : "",
            //"Boutique" => @$postUpdate['equipment'] ? @$postUpdate['equipment'] : "",
            "Boutique" => '30182053',
            "DataAcquisto" => date_format(date_create(@$faber_post['purchase_date'] ? @$faber_post['purchase_date'] : ""),"Y-m-d"),
            "StatoAssicurazione" => '173990000',
            "NumeroCertificato" => @$faber_post['certificato_code'] ? @$faber_post['certificato_code'] : "",
            "DataAssicurazione" => date_format(date_create(@$faber_post['created_at'] ? @$faber_post['created_at'] : ""),"Y-m-d"),
            "DataScadenza" => date_format(date_create(@$faber_post['expire_date'] ? @$faber_post['expire_date'] : ""),"Y-m-d"),
            "OccasioneAcquisto" => 1,
            "Brand" => @$faber_post['brand'] ? @$faber_post['brand'] : "",
            "Venditore" => @$postUpdate['seller_name'] ? @$postUpdate['seller_name'] : "",
            "DataSinistro" => date_format(date_create(@$postUpdate['left_date'] ? @$postUpdate['left_date'] : ""),"Y-m-d"),
            ];
            
    /* echo "<pre>";
      print_r($api_insurance_params);*/

      
      //$url_insurance_claim = "https://crmdaminsuranceservices-dev.azurewebsites.net/api/Insurance/UpsertInsuranceForCustomer";
      $url_insurance_claim = "https://crmdaminsuranceservices-dev.azurewebsites.net/api/InsurancePortal/UpsertInsurance";        
      $this->getCallApi($api_insurance_params, $url_insurance_claim);
      
      

    }

    public function getCallApi($value , $url)
    {
      $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($value),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic JERNTi91c2VyYWNjZXNzOi1AP3UleGd6NlJ5cURBeWRnZVc1Y1pSIV5IVEdBR044blNoM3JQQzRjV3ByVXJtOGtWR3FLLVhWLUVRcw==",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 4956a8ef-9cb9-186b-27e2-256366a9e4cd"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
      $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/CRMintegration.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer); 
            $logger->info($response);
        //echo "-------------".$response."<br>";

        }
    }
}