<?php

namespace Dolphin\Claim\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;

class Successemail extends \Magento\Framework\App\Action\Action
{
  
	protected $_pageFactory;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
    \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
    TransportBuilder $_transportBuilder,
    Escaper $_escaper)
	{
   $this->_pageFactory = $pageFactory;
   $this->_escaper = $_escaper;
   $this->_transportBuilder = $_transportBuilder;
   $this->directory_list = $directory_list;  
   return parent::__construct($context);
 }

 public function execute()
 {
  $_POST = $this->getRequest()->getPostValue();
  $postFaber = $this->getRequest()->getPostValue('faber_post');
  $POST = json_decode($postFaber,true);
		//if(isset($_POST)){
  /*Code to IMPORT CRM Starts here*/
/*
	$data = [
            'login' => 'magento',
            'password' => 'arGK!eR5'
        ];
        
        $url = "https://testapp.damianigroup.com/wsservice/authenticate.do";
        $params = '';
        foreach($data as $key=>$value)
            $params .= $key.'='.$value.'&';
            
        $params = trim($params, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            
        $token = tempnam('/tmp','cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR, $token);
            
        // helpful options
        curl_setopt($ch,CURLOPT_AUTOREFERER, true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
            
        curl_setopt($ch, CURLOPT_URL, $url.'?'.$params ); //Url together with parameters
            
        $response = curl_exec($ch); 
        curl_close($ch);
    if(trim($response) == 'Succesful') {
     $token;

    if(isset($POST['privacy'])){
    $privacy = true;
    }else{
     $privacy = false;   
    }
    if(isset($POST['marketing'])){
    $marketing = true;
    }else{
     $marketing = false;   
    }
    if(isset($POST['profiling'])){
    $profiling = true;
    }else{
     $profiling = false;   
    }
    if(isset($POST['cession'])){
    $cession = true;
    }else{
     $cession = false;   
    }

$dob = $POST['dob'];
$dob = date("d/m/Y", strtotime($dob));
$purchase_date = $POST['purchase_date'];
$purchase_date = date("d/m/Y", strtotime($purchase_date));

 $postData = array(
        $postCrmArray["FIRSTNAME"] = $POST['name'],
        $postCrmArray["LASTNAME"] = $POST['surname'],
        $postCrmArray["COUNTRY_CODE"] = $POST['country_id'],
        $postCrmArray["EMAIL"] = $POST['email'],
        $postCrmArray["CITY"] = $POST['city'],
        $postCrmArray["STATE_PROVINCE"] = "MB",
        $postCrmArray["PHONE"] = $POST['phone'],
        $postCrmArray["STREET"] = $POST['address'],
        $postCrmArray["ZIP_CODE"] = $POST['zipcode'],
        $postCrmArray["NEWSLETTER"] =  false,
        $postCrmArray["CERTIFICATE_NO"] = $POST['certificato_code'],
        $postCrmArray["PRODUCT_NUMBER"] = $POST['model'],
        $postCrmArray["EQUIPMENT"] = $POST['equpiment'],
        $postCrmArray["BIRTHDATE"] = $dob,
        $postCrmArray["PURCHASE_DATE"] = $purchase_date,
        $postCrmArray["PRIVACY1_INSURANCE_ACTIVATION"] =  true,
        $postCrmArray["PRIVACY2_MARKETING_PROMOTION"] =  $privacy,
        $postCrmArray["PRIVACY3_MARKETING_PROFILE"] =  $marketing,
        $postCrmArray["PRIVACY4_SHARE_INFO"] =  $profiling,
        $postCrmArray["PRIVACY5_WEB_PROFILE"] =  $cession
    );


    $url1 = 'https://testapp.damianigroup.com/wsservice/services/damiani-rest/execute?action=CRM_INSERT_INSURANCE_DATA&';
         $url1 .= "parameter=".(json_encode($postCrmArray));
         
         //echo $url1; exit;
         
        //$url1 .= "parameter=".(json_encode($postCrmArray));                       
                         
            $finalurl = str_replace("\/","/",$url1);
            $urlquotes = str_replace('"','%22',$finalurl);
            $lastfinalurl = str_replace(" ",'%20',$urlquotes);
         
         
      //echo $lastfinalurl."<br>"; 
         $ch = curl_init();         
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_COOKIEFILE, $token);
         curl_setopt($ch,CURLOPT_AUTOREFERER, true);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
         //curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
         curl_setopt($ch, CURLOPT_URL, $lastfinalurl); //Url together with parameters
         $response = curl_exec($ch);       
         print_r($response);
         
       }*/
       /*Code to IMPORT CRM Ends here*/
       $headerId =  $_POST['headerId'];
       $STORE =  $_POST['storename'];
       $certificato_code =  $_POST['certificato_code'];
       $prefix = strtoupper(substr($_POST['parent_cat'], 0, 1));
       $postfix = strtoupper(substr($POST['certificato_code'], 1));
       $claimcode = $prefix.$postfix;     
       if(strtolower($STORE) == "english"){
        $fileName = "Claim N. ".$claimcode.".pdf";
      }else{
        $fileName = "Denuncia N. ".$claimcode.".pdf";

      }
      $path = $this->directory_list->getPath('media')."/Signed_Document/Claim/$headerId/".$claimcode.".pdf";
      $postObject = new \Magento\Framework\DataObject();
      $postObject->setData($_POST);
      $error = false;
      $sender = [
        'name' => $this->_escaper->escapeHtml("Damiani Group"),
        'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
      ];
      if(strtolower($STORE) == "english"){
        $storeScope = '2';
      }else{
        $storeScope = '1';
      }
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
                ->addTo($_POST['email'])
                ->addAttachment($path, $fileName)  
                ->getTransport();
                $transport->sendMessage();
                try{ 
                //$transport->sendMessage();
                } catch (\Exception $e) {
                  return $response = ['status' => 'false', 'message' => $e->getMessage()];
                }
              }
            }