<?php
namespace Dolphin\Claim\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
class Data extends AbstractHelper
{

    public function callClaimApi($faber_post, $post)
       {
        ob_start();
        $om = \Magento\Framework\App\ObjectManager::getInstance();
      	$filesystem = $om->get('Magento\Framework\Filesystem');
     		$directoryList = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
      	$token = $this->generateAccessToken();
    		//$postfieldsJson = json_encode($postfields,true);
    		/*echo "<pre>";
        print_r($faber_post);
        print_r($post);*/

        $countryID = $faber_post['country_id'] ? $faber_post['country_id'] : "";
        if($countryID == "IT"){
          $countryID = "Italia";
        }
        $address = $faber_post['address'] ? $faber_post['address'] : "";
        $city = $faber_post['city'] ? $faber_post['city'] : "";
        
       
        if(isset($post['storename'])){
          if($post['storename'] == "Italiano"){
              $ID_MODULE = 'a17a812567b9';
          }else{
              $ID_MODULE = 'a17a81266626';
          } 
        }
        $postfields["ID_MODULE"] = $ID_MODULE;
        $postfields["FIELDS"]['NOME'] = @$faber_post['name'] ? $faber_post['name'] : "";
        $postfields["FIELDS"]['COGNOME'] = @$faber_post['surname'] ? $faber_post['surname'] : "";

        $postfields["FIELDS"]['MARCHIO'] = @$faber_post['brand'] ? $faber_post['brand'] : "";
        $postfields["FIELDS"]['TELEFONO'] = @$faber_post['mobile_phone'] ? $faber_post['mobile_phone'] : "";
        $postfields["FIELDS"]['EMAIL'] = @$faber_post['email'] ? $faber_post['email'] : "";
       //$postfields["FIELDS"]['EMAIL'] = $post['email'] ? $post['email'] ? : "";
        $postfields["FIELDS"]['INDIRIZZO'] = @$address." ".$city." - ".$countryID;
        $postfields["FIELDS"]['DATA_SINISTRO'] = date_format(date_create(@$post['left_date'] ? $post['left_date'] : ""),"d/m/Y");
        $postfields["FIELDS"]['ORA_SINISTRO'] = '12:30';
        $postfields["FIELDS"]['INDIRIZZO_SINISTRO'] = @$post['left_address'] ? $post['left_address'] : "";
        $postfields["FIELDS"]['CAP_SINISTRO'] = @$post['left_zipcode'] ? $post['left_zipcode'] : "";
        $postfields["FIELDS"]['CITTA_SINISTRO'] =  @$post['left_city'] ? $post['left_city'] : "";
        $postfields["FIELDS"]['PROVINCIA_SINISTRO'] = '';
        $postfields["FIELDS"]['NAZIONE_SINISTRO'] = @$post['left_country'] ? $post['left_country'] : "";
        $postfields["FIELDS"]['MATERIALE'] = @$faber_post['model'] ? $faber_post['model'] : "";
        $postfields["FIELDS"]['DATA_ATTIVAZIONE'] = date_format(date_create(@$faber_post['created_at'] ? $faber_post['created_at'] : ""),"d/m/Y");
        $postfields["FIELDS"]['NUMERO_CERTIFICATO'] = @$faber_post['certificato_code'] ? $faber_post['certificato_code'] : "";
        $postfields["FIELDS"]['DESCRIZIONE_EVENTO'] = @$post['describe_events'] ? $post['describe_events'] : "";
        $postfields["FIELDS"]['DATA_DENUNCIA'] = date_format(date_create(@$post['date_of_termination'] ? $post['date_of_termination'] : ""),"d/m/Y");
        $postfields["FIELDS"]['DATA_DOCUMENTO'] = @$post['doc_number'] ? $post['doc_number'] : "";
        $postfields["FIELDS"]['LUOGO_DOCUMENTO'] = @$post['location_authority'] ? $post['location_authority'] : "";
        $postfields["FIELDS"]['AUTORITA'] = $post['authority'] ? $post['authority'] : "";
        $postfields["FIELDS"]['TOP_IMAGE_URL'] =  "https://ambassador.damianigroup.com/ambassador/images/DamianiGroup.png";
        $postfields["FIELDS"]['BOTTOM_IMAGE_URL'] = "https://ambassador.damianigroup.com/ambassador/images/LoghiDamiani.png";
       
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
	    return $response;
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
}