<?php
namespace Dolphin\InsurancePdf\Model;

use \Magento\Framework\Escaper;
use \Magento\Framework\Mail\Template\TransportBuilder;
use Dolphin\Certificato\Model\CertificatoFactory;

class Api extends \Magento\Framework\Model\AbstractModel
{
    const API_HOST = "https://app.damianigroup.com/";
	const API_URL_TYPE_AUTHENTICATE = "wsservice/authenticate.do";


    protected $objectManager;
    protected $configFactory;
    protected $faberHelper;
    protected $resultFactory;
	public $curl_url;
	public $curl_custom_request;//POST=Insert, PUT=Update

	public function __construct(
        \Magento\Framework\Model\Context $context,
		Escaper $_escaper,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
		\Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Config\Model\Config\Factory $configFactory,
		CertificatoFactory $CertificatoFactory,
		 TransportBuilder $_transportBuilder,
        \Dolphin\InsurancePdf\Helper\Data $faberHelper
    ){
        $this->objectManager = $objectManager;
		$this->_escaper = $_escaper;
        $this->configFactory = $configFactory;
        $this->resultFactory = $resultFactory;
        $this->certificatoFactory = $CertificatoFactory;
        $this->faberHelper = $faberHelper;
        $this->_transportBuilder = $_transportBuilder;
		parent::__construct($context,$registry);
    }

	
	public function generateAccessToken()
    {
		$postfields = [
            'login' => 'magento',
            'password' => 'arGK!eR5'
        ];

		$url = "https://app.damianigroup.com/wsservice/authenticate.do";
        $params = '';
        foreach($postfields as $key=>$value)
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
        return $token;

    }

	public function postToInsurancePdf($postfields = array())
    {
    	$om = \Magento\Framework\App\ObjectManager::getInstance();
    	$filesystem = $om->get('Magento\Framework\Filesystem');
   		$directoryList = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
    	$token = $this->generateAccessToken();
		$postfieldsJson = json_encode($postfields,true);
		$curl = curl_init();
		//echo "ApiUrl: ".$this->getApiUrl();
		curl_setopt($curl,CURLOPT_URL, "https://app.damianigroup.com/wsservice/services/damiani-json/execute?action=PRINT_MODULE");
		curl_setopt($curl, CURLOPT_COOKIEFILE, $token);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $postfieldsJson);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); //timeout to infi.
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout in seconds. 30 seconds
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		$response = json_decode($response,true);
		
    if($response['return'] == "OK"){
    	$docx = file_get_contents($response['parameter']['DOCUMENT_URL']);
		$resultPage = $this->resultFactory
					->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW)
					->setHeader('Content-Type', 'application/pdf', true)
					->setHeader('Content-Disposition', "attachment; filename=\"".$postfields['FIELDS']['NUMERO_CERTIFICATO'].".pdf\"", true)
					->setContents($docx)
					;
		$file_name = "/var/www/html/pub/media/Signed_Document";
		$media = $filesystem->getDirectoryWrite($directoryList::MEDIA);
    	$path = "/var/www/html/pub/media/Signed_Document/Certificato/".$postfields['FIELDS']['NUMERO_CERTIFICATO'].".pdf";
    	$contents = $docx;
    	$media->writeFile($path,$contents);

		if(is_numeric($postfields['FIELDS']['NUMERO_CERTIFICATO'])){
        	$certi = $postfields['FIELDS']['NUMERO_CERTIFICATO'];
         }else{
            $certi = ltrim(substr($postfields['FIELDS']['NUMERO_CERTIFICATO'], 1),"0");
        }
         $expire = date('Y-m-d', strtotime('+3 years'));
         $finalExpire =  date('Y-m-d', strtotime('-1 day', strtotime($expire)));
         $certificatoFactory = $this->certificatoFactory->create();
         $postUpdate = $certificatoFactory->load($certi);
         $postUpdate->setDocSignedid($postfields['FIELDS']['NUMERO_CERTIFICATO']);
         $postUpdate->setStatus('1');
         $postUpdate->setExpireDate($finalExpire);
         $postUpdate->save();
		    curl_close($curl);
      }else{
        $response = ['status' => 'false', 'message' => $response['message'] ];
      }
	return $response;
  }
	
	public function emailToInsurancePdf($certiArray){
	  $certificato_code = isset($certiArray['certificato_code']) ? $certiArray['certificato_code'] : "";
      $email = isset($certiArray['email']) ? $certiArray['email'] : "";
	  $fileName = "N. ".$certificato_code.".pdf";         
	  $path = "/var/www/html/pub/media/Signed_Document/Certificato/".$certificato_code.".pdf";
	  $postObject = new \Magento\Framework\DataObject();
      $postObject->setData($certiArray);
      $error = false;
      $sender = [
        'name' => "Damiani Group",
        'email' => "noreply@damianigroupcustomercare.com",
        ];
      $storeScope = '1';
    if(isset($certiArray['lang']) && $certiArray['lang'] == 'en'){
      $storeScope = '2';
    }
      
    //$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE; 
    $transport = $this->_transportBuilder
      ->setTemplateIdentifier('email_template_register_success') // this code we have mentioned in the email_templates.xml
      ->setTemplateOptions(
        [
          'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
          'store' => $storeScope,
        ])
        ->setTemplateVars(['customer' => $postObject])
        ->setFrom($sender)
        ->addTo($email)
        //->addTo("divyaraj1@yopmail.com")
        ->addAttachment($path, $fileName)  
        ->getTransport();
        //$transport->sendMessage();
          try{ 
              $transport->sendMessage();
            } catch (\Exception $e) {
          return $response = ['status' => 'false', 'message' => $e->getMessage()];
         }
     }
	
	
	
}