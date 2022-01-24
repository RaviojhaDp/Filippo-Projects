<?php
namespace Dolphin\InsurancePdf\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;
use Dolphin\Certificato\Model\CertificatoFactory;
use Dolphin\InsurancePdf\Model\Api;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class Confirm extends \Magento\Framework\App\Action\Action
{
    protected $resultJsonFactory;
    protected $storeManager;
	protected $_pageFactory;
    protected $certificatoFactory;
    protected $request;
    protected $api;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
        StoreManagerInterface $storeManager,
        CustomerFactory $customerFactory,
         \Magento\Framework\App\Request\Http $request,
         Api $api,
         JsonFactory $resultJsonFactory,
		\Magento\Framework\View\Result\PageFactory $pageFactory, TransportBuilder $_transportBuilder,CertificatoFactory $CertificatoFactory, Escaper $_escaper)
	{
		$this->_api = $api;
        $this->customerFactory  = $customerFactory;
        $this->_pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
		$this->_escaper = $_escaper;
        $this->request = $request;
		$this->_transportBuilder = $_transportBuilder;
        $this->certificatoFactory = $CertificatoFactory;
        $this->resultJsonFactory = $resultJsonFactory;
		return parent::__construct($context);
	}

	public function execute()
	{
        //exit("sdfdf");
        $certiId = substr($this->request->getParam('certid'), 1);
        $lang = $this->request->getParam('lang');
		$certificatoFactory = $this->certificatoFactory->create();
        $postUpdate = $certificatoFactory->load($certiId);
        /*echo "<pre>";
        print_r($postUpdate->getData());*/
        //die;*/    
        $header_logo = "https://ambassador.damianigroup.com/ambassador/images/damiani.svg";
        $price = "100.000 euro (centomila)";
        if($postUpdate->getBrand()){
            if(strtolower($postUpdate->getBrand()) == "salvini"){
                $price = "35.000 euro (trentacinquemila)";
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/salvini.svg";
            }elseif(strtolower($postUpdate->getBrand()) == "damiani"){
                 $price = "100.000 euro (centomila)";
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/damiani.svg";
            }elseif(strtolower($postUpdate->getBrand()) == "bliss"){
                 $price = "5.000 euro (cinquemila)";
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/bliss.svg";
            }elseif(strtolower($postUpdate->getBrand()) == "rocca"){
                $price = "45.000 euro (quarantacinquemila)";
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/rocca.svg";
            }elseif(strtolower($postUpdate->getBrand()) == "calderoni"){
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/calderoni.svg";
                $price = "100.000 euro (centomila)";
            }else{
                $price = "100.000 euro (centomila)";
                $header_logo = "https://ambassador.damianigroup.com/ambassador/images/certificato/damiani-group.svg";
            }
        }
        $postfields = array();
        $ID_MODULE = 'a17a81264a1c';
        if($lang == "it"){
		 $ID_MODULE = "a1795c046715";
        }

        $postfields["ID_MODULE"] = "a1795c046715";
        $countryID = $postUpdate->getCountryId() ? $postUpdate->getCountryId() : "";
        if($countryID == "IT"){
        	$countryID = "Italia";
        }
        
        $Material_Code = $postUpdate->getModel() ? $postUpdate->getModel() : "";
        if(strtolower($postUpdate->getBrand()) == "calderoni"){
            $Material_Code = $postUpdate->getStoneCode() ? $postUpdate->getStoneCode() : "";
        }
        /*if(strtolower($postUpdate->getBrand()) == "bliss"){
            $Material_Code = $postUpdate->getStoneCode() ? $postUpdate->getStoneCode() : "";
        }*/

        $address = $postUpdate->getAddress() ? $postUpdate->getAddress() : "";
        $city = $postUpdate->getCity() ? $postUpdate->getCity() : "";
        $postfields["FIELDS"]['NOME'] = $postUpdate->getName() ? $postUpdate->getName() : "";
        $postfields["FIELDS"]['COGNOME'] = $postUpdate->getSurname() ? $postUpdate->getSurname() : "";
        $postfields["FIELDS"]['TELEFONO'] = $postUpdate->getMobilePhone() ? $postUpdate->getMobilePhone() : "";
        $postfields["FIELDS"]['INDIRIZZO'] = $address." ".$city." - ".$countryID;
        $postfields["FIELDS"]['DATA_NASCITA'] = date_format(date_create($postUpdate->getDob() ? $postUpdate->getDob() : ""),"d/m/Y");
        $postfields["FIELDS"]['EQUIPMENT'] = $postUpdate->getEqupiment() ? $postUpdate->getEqupiment() : "";
        $postfields["FIELDS"]['MATERIALE'] = $Material_Code;
        $postfields["FIELDS"]['DATA_ATTIVAZIONE'] = date("d/m/Y");
        $postfields["FIELDS"]['NUMERO_CERTIFICATO'] = $postUpdate->getCertificatoCode() ? $postUpdate->getCertificatoCode() : "";
        $postfields["FIELDS"]['DATA_DOCUMENTO'] = date_format(date_create($postUpdate->getCreatedAt() ? $postUpdate->getCreatedAt() : ""),"d/m/Y");
        $postfields["FIELDS"]['VALORE'] = $price;
        $postfields["FIELDS"]['TOP_IMAGE_URL'] = $header_logo;
        $postfields["FIELDS"]['BACKGROUND_IMAGE'] = "";
   
        $postToInsurancePdf = $this->_api->postToInsurancePdf($postfields);
        if($postToInsurancePdf){

          $certiChangeCollectionSave = $this->certificatoFactory->create()->load($certiId);
          $expire = date('Y-m-d', strtotime('+3 years'));
          $finalExpire =  date('Y-m-d', strtotime('-1 day', strtotime($expire)));
          $certiChangeCollectionSave->setStatus('1');
          $certiChangeCollectionSave->setExpireDate($finalExpire)->save();
		  $certiArray = $postUpdate->getData();
          $this->setCrmData($certiId);
          $certiArray['lang'] = $lang;
		  $emailSuccess = $this->_api->emailToInsurancePdf($certiArray);
               $this->_redirect('success?certi='.$postfields["FIELDS"]['NUMERO_CERTIFICATO'].'&brand='.$certiArray['brand'].'&create_at='.$postfields["FIELDS"]['DATA_DOCUMENTO']); 
		}
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
            "authorization: Basic JERNTi91c2VyYWNjZXNzOiFxd2V4Z3o2Mjkza2RoNGs0OTZqR044blNoM3JQQzMzbzExLjQ0MDVFUXM=",
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
        echo "-------------".$response."<br>";

        }
    }

    public function Emailexist($certiArray)
    {
        
      $resultJson = $this->resultJsonFactory->create();
      $firstName = $certiArray['name'];
      $lastName = $certiArray['surname'];
      $email = $certiArray['email'];
      $password = $this->generateRandomString(8);
      $websiteId = $this->storeManager->getWebsite()->getWebsiteId();
      $customer = $this->customerFactory->create();
      $customer->setWebsiteId($websiteId);

        if ($customer->loadByEmail($email)->getId()) {
            $response1 = $customer->loadByEmail($email)->getId();
            $response_data_group = $customer->loadByEmail($email)->getGroupId();
            $response2 = ['status' => 'exist', 'id' => $response1, 'group' => $response_data_group];
            $resultJson->setData($response2);
        } else {
            $group_id = '5';
            $post = $this->getRequest()->getPostValue();
            $post["password"] = $password;
            $post["email"] = $email;
            $customer->setEmail($email);
            $customer->setFirstname($firstName);
            $customer->setLastname($lastName);
            $customer->setPassword($password);
            $customer->setForceConfirmed(true);
            $customer->setGroupId($group_id);
            $customer->save();
            
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);
            $error = false;

            $sender = [
                'name' => $this->_escaper->escapeHtml("Damiani Group"),
                'email' => $this->_escaper->escapeHtml("noreply@damianigroupcustomercare.com"),
            ];

            $lang = $this->request->getParam('lang');
            if(strtolower($lang) == "en"){
                $storeScope = '2';
            }else{
                $storeScope = '1';
            }
            if ($customer->save()) {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('dolphin_certificato_template') // this code we have mentioned in the email_templates.xml
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                        'store' => $storeScope,
                    ]
                )
                ->setTemplateVars(['customer' => $postObject])
                ->setFrom($sender)
                ->addTo($email)
                ->getTransport();
                 //$transport->sendMessage();
                try{ 
                $transport->sendMessage();
              } catch (\Exception $e) {
            $response = ['status' => 'false', 'message' => $e->getMessage()];
             } 
        $response1 = $customer->loadByEmail($email)->getId();
        $response3 = $customer->loadByEmail($email)->getGroupId();
        $response2 = ['status' => 'new', 'id' => $response1,'group'=>$response3];
        //print_r($response2);die;
        $resultJson->setData($response2);
        }
      }
    }

    public function setCrmData($certiId)
    {
      $certificatoFactory = $this->certificatoFactory->create();
      $postUpdate = $certificatoFactory->load($certiId);
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $customerRepository = $objectManager->get('Magento\Customer\Api\CustomerRepositoryInterface');
	  
	  $vendorName = "";
	  $cattrValue = "";
	  if($postUpdate->getNameBoutiqueRetailer()){
      $customerBout = $customerRepository->getById($postUpdate->getNameBoutiqueRetailer());
      $vendorName = $customerBout->getFirstname();
      $customerBout = $customerBout->__toArray();
      $cattrValue = @$customerBout['custom_attributes']['boutique_sap_id']['value'];
	  }
     /*----Brand--------*/
        $Brand = "0";
        //if(isset($postUpdate->getBrand())){
            if($postUpdate->getBrand() == "damiani"){ 
                $Brand = '0';
            }elseif ($postUpdate->getBrand() == "salvini") { 
                $Brand = '1';
            }elseif ($postUpdate->getBrand() == "rocca") { 
                $Brand = '5';
            }elseif ($postUpdate->getBrand() == "bliss") { 
                $Brand = '6';
            }else{
            $Brand = '2';
            }
        //}
        //echo "--------->".$postUpdate->getCivilStatus;die;
        
        /*----Profession--------*/
        $profession = "";
        if(isset($postUpdate["profession"])){
            if($postUpdate["profession"] == '1'){
                $profession = "Commerciante";
            }elseif ($postUpdate["profession"] == '2') { 
                $profession = "Dirigente";
            }elseif ($postUpdate["profession"] == '3') { 
                $profession = "Impiegato";
            }elseif ($postUpdate["profession"] == '4') { 
                $profession = "Imprenditore";
            }elseif ($postUpdate["profession"] == '5') { 
                $profession = "Libero professionista";
            }elseif ($postUpdate["profession"] == '6') { 
                $profession = "Altro";
            }
            else{
            $profession = '';
            }
        }


        /*----CIVIL STATUS--------*/
        $StatoCivile = "";
        if(isset($postUpdate["civil_status"])){
            if($postUpdate["civil_status"] == '1'){ // single
                $StatoCivile = '1';
            }elseif ($postUpdate["civil_status"] == '5') { // Engaged
                $StatoCivile = '6';
            }elseif ($postUpdate["civil_status"] == '2') { // Married
                $StatoCivile = '2';
            }else{
            $StatoCivile = '';
            }
        }

        $NomeConsorteE = ""; //E = Engaged
        $CognomeConsorteE = "";
        $CompleannoConsorteE = "";
        
        if($postUpdate["civil_status"] == '5') { 
            /*----Partner Name--------*/
           if(isset($postUpdate['partner_name_single'])){ 
               $NomeConsorteE = $postUpdate['partner_name_single'];
            }
           if(isset($postUpdate['partner_surname_single'])){ 
               $CognomeConsorteE = $postUpdate['partner_surname_single'];
            }
           if(isset($postUpdate['partner_dob_single'])){ 
               $CompleannoConsorteE = $postUpdate['partner_dob_single'];
            }
          }

          $NomeConsorteM = "";
          $CognomeConsorteM = ""; // M = Married
          $CompleannoConsorteM = "";
          if($postUpdate["civil_status"] == '2') {
           
            if(isset($postUpdate['partner_name'])){
                $NomeConsorteM = $postUpdate['partner_name'];
             }
            if(isset($postUpdate['partner_surname'])){ //1.Married 2.Engaged
               $CognomeConsorteM = $postUpdate['partner_surname'];
            }
            if(isset($postUpdate['partner_dob'])){ 
               $CompleannoConsorteM = $postUpdate['partner_dob'];
            }
          }


        /*----Partner NumberOfChild--------*/
        $NumeroFigli = "";
        if(isset($postUpdate['first_no_of_child']) || isset($postUpdate['engaged_no_of_child']) || isset($postUpdate['no_of_child'])){
         if($postUpdate['first_no_of_child'] != '' && $postUpdate['first_no_of_child'] != '7'){
            $NumeroFigli = $postUpdate['first_no_of_child'];
         }elseif($postUpdate['engaged_no_of_child'] != '' && $postUpdate['engaged_no_of_child'] != '7'){
            $NumeroFigli = $postUpdate['engaged_no_of_child'];
         }
         else{
            $NumeroFigli = $postUpdate['no_of_child'];
         }
        }


        /*----CHILDREN ARRAY--------*/
        $Figli = array();
        $Figli[1]['NomeFiglio']  = $postUpdate['first_chidren_name_one'];
        $Figli[1]['CognomeFiglio']  = $postUpdate['first_chidren_surname_one'];
        $Figli[1]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_one'];

        $Figli[2]['NomeFiglio']  = $postUpdate['first_chidren_name_two'];
        $Figli[2]['CognomeFiglio']  = $postUpdate['first_chidren_surname_two'];
        $Figli[2]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_two'];

        $Figli[3]['NomeFiglio']  = $postUpdate['first_chidren_name_three'];
        $Figli[3]['CognomeFiglio']  = $postUpdate['first_chidren_surname_three'];
        $Figli[3]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_three'];

        $Figli[4]['NomeFiglio']  = $postUpdate['first_chidren_name_four'];
        $Figli[4]['CognomeFiglio']  = $postUpdate['first_chidren_surname_four'];
        $Figli[4]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_four'];

        $Figli[5]['NomeFiglio']  = $postUpdate['first_chidren_name_five'];
        $Figli[5]['CognomeFiglio']  = $postUpdate['first_chidren_surname_five'];
        $Figli[5]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_five'];

        $Figli[6]['NomeFiglio']  = $postUpdate['first_chidren_name_six'];
        $Figli[6]['CognomeFiglio']  = $postUpdate['first_chidren_surname_six'];
        $Figli[6]['CompleannoFiglio']  = $postUpdate['first_chidren_dob_six'];

        /*----------------Engaged Children--------------------------*/

        $Figli[7]['NomeFiglio']  = $postUpdate['engaged_chidren_name_one'];
        $Figli[7]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_one'];
        $Figli[7]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_one'];

        $Figli[8]['NomeFiglio']  = $postUpdate['engaged_chidren_name_two'];
        $Figli[8]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_two'];
        $Figli[8]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_two'];


        $Figli[9]['NomeFiglio']  = $postUpdate['engaged_chidren_name_three'];
        $Figli[9]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_three'];
        $Figli[9]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_three'];

        $Figli[10]['NomeFiglio']  = $postUpdate['engaged_chidren_name_four'];
        $Figli[10]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_four'];
        $Figli[10]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_four'];

        $Figli[11]['NomeFiglio']  = $postUpdate['engaged_chidren_name_five'];
        $Figli[11]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_five'];
        $Figli[11]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_five'];

        $Figli[12]['NomeFiglio']  = $postUpdate['engaged_chidren_name_six'];
        $Figli[12]['CognomeFiglio']  = $postUpdate['engaged_chidren_surname_six'];
        $Figli[12]['CompleannoFiglio']  = $postUpdate['engaged_chidren_dob_six'];

        /*----------------Married Children--------------------------*/

        $Figli[13]['NomeFiglio']  = $postUpdate['chidren_name_one'];
        $Figli[13]['CognomeFiglio']  = $postUpdate['chidren_surname_one'];
        $Figli[13]['CompleannoFiglio']  = $postUpdate['chidren_dob_one'];

        $Figli[14]['NomeFiglio']  = $postUpdate['chidren_name_two'];
        $Figli[14]['CognomeFiglio']  = $postUpdate['chidren_surname_two'];
        $Figli[14]['CompleannoFiglio']  = $postUpdate['chidren_dob_two'];

        $Figli[15]['NomeFiglio']  = $postUpdate['chidren_name_three'];
        $Figli[15]['CognomeFiglio']  = $postUpdate['chidren_surname_three'];
        $Figli[15]['CompleannoFiglio']  = $postUpdate['chidren_dob_three'];

        $Figli[16]['NomeFiglio']  = $postUpdate['chidren_name_four'];
        $Figli[16]['CognomeFiglio']  = $postUpdate['chidren_surname_four'];
        $Figli[16]['CompleannoFiglio']  = $postUpdate['chidren_dob_four'];

        $Figli[17]['NomeFiglio']  = $postUpdate['chidren_name_five'];
        $Figli[17]['CognomeFiglio']  = $postUpdate['chidren_surname_five'];
        $Figli[17]['CompleannoFiglio']  = $postUpdate['chidren_dob_five'];

        $Figli[18]['NomeFiglio']  = $postUpdate['chidren_name_six'];
        $Figli[18]['CognomeFiglio']  = $postUpdate['chidren_surname_six'];
        $Figli[18]['CompleannoFiglio']  = $postUpdate['chidren_dob_six'];

        $FigliFinal = array();
        $x = 0;
        foreach ($Figli as $key => $value) {
            if($value['NomeFiglio'] == ''){
                continue;
            }
            $FigliFinal[$x] = $value;
            $x++;
        }

       
        $api_params = [
           "Nome" => $postUpdate->getName() ? $postUpdate->getName() : "", 
           "Cognome" => $postUpdate->getSurname() ? $postUpdate->getSurname() : "", 
           "Indirizzo" => $postUpdate->getAddress() ? $postUpdate->getAddress() : "", 
           "Cap" => $postUpdate->getZipcode() ? $postUpdate->getZipcode() : "", 
           "Paese" => $postUpdate->getCountryId() ? $postUpdate->getCountryId() : "", 
           "Comune" => $postUpdate->getCity() ? $postUpdate->getCity() : "", 
           "Telefono" => $postUpdate->getPhone() ? $postUpdate->getPhone() : "", 
           "Cellulare" => $postUpdate->getMobilePhone() ? $postUpdate->getMobilePhone() : "", 
           "DataNascita" => $postUpdate->getDob() ? $postUpdate->getDob() : "", 
           "Genere" =>  $postUpdate->getSex() == "Female" ? "279640000" : "279640001", 
           "Email" => $postUpdate->getEmail() ? $postUpdate->getEmail() : "", 
           //"StatoCivile" => 6, 
           "StatoCivile" => $StatoCivile, 
           "Professione" => $profession ? $profession : "", 
           "NomeConsorte" => $NomeConsorteM, 
           "CognomeConsorte" => $CognomeConsorteM, 
           "CompleannoConsorte" => $CompleannoConsorteM, 
           "NumeroFigli" => $NumeroFigli, 
           "DataMatrimonio" => $postUpdate->getWeddingAnniversary() ? $postUpdate->getWeddingAnniversary() : "", 
           "NomePartner" => $NomeConsorteE, 
           "CognomePartner" => $CognomeConsorteE, 
           "CompleannoPartner" => $CompleannoConsorteE, 
           "Privacy" => $postUpdate->getPrivacy() == "0" ? "173990001" : "173990000", 
           "Marketing" => $postUpdate->getMarketing() == "0" ? "173990001" : "173990000", 
           "Profilazione" => $postUpdate->getProfiling() == "0" ? "173990001" : "173990000", 
           "Cessione" => $postUpdate->getCession() == "0" ? "173990001" : "173990000", 
           "Figli" => $FigliFinal, 
           "Brand" => $Brand 
        ];

        $api_insurance_params = [
        	"Email" => $postUpdate->getEmail() ? $postUpdate->getEmail() : "",
            "Equipment" => $postUpdate->getEqupiment() ? $postUpdate->getEqupiment() : "",
            "CodiceMateriale" => $postUpdate->getModel() ? $postUpdate->getModel() : "",
            "Boutique" => $cattrValue ? $cattrValue : "",
            //"Boutique" => '30182053',
            "DataAcquisto" => $postUpdate->getPurchaseDate() ? $postUpdate->getPurchaseDate() : "",
            "StatoAssicurazione" => 1,
            "NumeroCertificato" => $postUpdate->getCertificatoCode() ? $postUpdate->getCertificatoCode() : "",
            "DataAssicurazione" => $postUpdate->getCreatedAt() ? $postUpdate->getCreatedAt() : "",
            "DataScadenza" => $postUpdate->getExpireDate() ? $postUpdate->getExpireDate() : "",
            "OccasioneAcquisto" => 1,
            "Brand" => $Brand,
            "Venditore" => $postUpdate->getSellerName() ? $postUpdate->getSellerName() : "",
            "DataSinistro" => ""
            ];

        $url_customer = "https://damiani-api-prod.p365.it/api/InsurancePortal/UpsertCustomer";
        $url_insurance = "https://damiani-api-prod.p365.it/api/InsurancePortal/UpsertInsurance";

        echo "<prE>";
        print_r($api_params)."<br>";
        print_r($api_insurance_params)."<br>";

        $response = $this->getCallApi($api_params, $url_customer);
        $response2 = $this->getCallApi($api_insurance_params, $url_insurance);
        exit("---ENDS HERE---");

        /*$response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
       echo $response;die;
        }*/
    }



    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&';
        $charactersLength = strlen($characters);
        $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        return $randomString;
    }
}
