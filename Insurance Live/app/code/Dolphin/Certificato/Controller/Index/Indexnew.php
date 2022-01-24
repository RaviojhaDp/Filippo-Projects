<?php
namespace Dolphin\Certificato\Controller\Index;

use \Psr\Log\LoggerInterface;
class Indexnew extends \Magento\Framework\App\Action\Action
{
	
  	protected $logger;
    private $_transportBuilder;
    protected $escaper;
    protected $_objectManager;

    public function __construct(

        LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context,
         \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory,   
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder,
        \Magento\Framework\Escaper $_escaper,
         array $data = []
    ) {
    	
        $this->logger = $logger;
          $this->certificatoModelFactory = $certificatoModelFactory;
        $this->_escaper = $_escaper;
        $this->_objectManager = $objectmanager;
        $this->_transportBuilder = $_transportBuilder;
        parent::__construct($context);
    }
	
	
    public function execute() {
       
        $this->logger->info("start Expiry cron");
		$objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
        $date = $objDate->gmtDate();
        $date1 = date('Y-m-d', strtotime($date));
		$d2 = date('Y-m-d', strtotime("-0 days"));
        $model = $this->certificatoModelFactory->create()->getCollection();
        $model->addFieldToFilter('brand', array('eq' => "damiani"));
        $model->addFieldToFilter('status', array('eq' => "1"));
		 $model->addFieldToFilter('created_at', array('eq' => $d2));
       
        $collection = $model->getData();
        $post = array();
        foreach ($collection as $item) {
            $item['expire_date'] = date("d/m/Y",strtotime($item['expire_date']));
            $certid = $item['certificato_id'];
            $item['urlnew'] = "https://www.damianigroupcustomercare.com/it/damiani/assicurazione.html?certid=$certid&renewal=1";
            $SolitaryCatApi = $this->checkSolitaryCatApi($item['equpiment'], $item['brand']);
            $SolitaryArray = json_decode($SolitaryCatApi,true);
			
            if($SolitaryArray['equipment_status'] == "true"){     
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($item);
                $error = false;
                $sender = [
                    'name' => $this->_escaper->escapeHtml("Damiani Group"),
                    'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
                ];

                //$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $storeScope = '1';
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
                        //->addTo($item['email'])
                        ->getTransport();
                $transport->sendMessage();
          
        }
    }
        $this->logger->info('Expiry Cron Ends');               
    }
    public function checkSolitaryCatApi($equpiment , $brand){
       // $equpiment = "73131300";

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

        $postData = array(
        $postCrmArray["PRODUCT_REFERENCE"] = $equpiment,
        $postCrmArray["LANGUAGE"] = "IT",
        $postCrmArray["PRICE_LIST"] = "EURO_Z001",
    );
     
        $url1 = 'https://testapp.damianigroup.com/wsservice/services/damiani-json/execute?action=GET_PRODUCT_REFERENCE_INFO&';
        $url1 .= "parameter=".(json_encode($postCrmArray));               
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
         $responses = json_decode($response,true);
         /*echo "-0------0-0---->"."<pre>";
            print_r($responses);
            die;*/
         if(isset($responses['parameter'])){  
         $branda = $responses['parameter']['BRAND'];
         $cat = $responses['parameter']['MACRO_FAMILY'];
         if(strtolower($branda) == "damiani"){
         if(strtolower($branda) == "damiani"){
         //if(isset($cat) && $cat == '001'){ 
            return '{"equipment_status" : "true","message" : "Success","return":"OK"}';
           }else{
              return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
              }
           }
           else{
            return '{"equipment_status" : "false","message" : "Price not in range","return":"KO"}';
          }
        }
        else{
            return $response;
        }
    }
  public  function dateDiff($date1, $date2) {
            $date1_ts = strtotime($date1);
            $date2_ts = strtotime($date2);
            $diff = $date2_ts - $date1_ts;
            return round($diff / 86400);
        }
}