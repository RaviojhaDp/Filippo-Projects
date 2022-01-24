<?php

namespace Dolphin\Roccaapi\Model;

class Observer {

    public function execute() {

        $autho = $this->getToken();
        if ($autho->access_token && $autho->access_token != '') {
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $day = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('roccacronjobs/roccacronjobs_import/day_differ');
            $date = (new \DateTime())->modify('-'.$day.' day');
            $createdAt =$date->format('Y-m-d');

            $order_details=$this->getOrderDetails($autho->access_token , $createdAt);
            $order_detail = $order_details->data;
			$included = $order_details->included;
             
             foreach ($order_detail as $key1 => $value1) {
             	//echo $value->relationships->shipments->data[0]->id."<bR>";
             	$billing_id = $value1->relationships->billing_address->data->id;
             	$order_items = $this->getItemdetails($autho->access_token ,$value1->relationships->shipments->data[0]->id);
                /*echo "<pre>";
             print_r($order_items);
             die;  */  
             	foreach ($included as $key => $value2) {
             		if($value2->id == $billing_id){
                        $equipment = $order_items->included[0]->attributes->reference;
                        if($order_items->included[0]->attributes->reference == ''){
                            $equipment = '';
                        }
             			//echo "------------".$value2->attributes->last_name;die;
             			//echo "<prE>";print_r($value2);
                        $state_code = $value2->attributes->state_code;
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $region = $objectManager->create('Magento\Directory\Model\Region')
                        ->loadByCode($state_code, $value2->attributes->country_code);
                        $region_name = $region->getData('name');
                        $importArray["flag"] =  '1';
             			$importArray["firstname"] =  $value2->attributes->first_name;
             			$importArray["lastname"] =  $value2->attributes->last_name;
             			$importArray["serial"] =  $value1->attributes->number;
                        $importArray["region"] =  $region_name;
             			$importArray["email"] =  $value1->attributes->customer_email;
             			//$importArray["email"] =  "ravi.ohja11@mailinator.com";
             			$importArray["created_at"] =  $value1->attributes->placed_at;
             			$importArray["street"] =  $value2->attributes->line_1;
             			$importArray["city"] =  $value2->attributes->city;
             			$importArray["postcode"] =  $value2->attributes->zip_code;
             			$importArray["country_id"] =  $value2->attributes->country_code;
             			$importArray["telephone"] =  $value2->attributes->phone;
             			$importArray["equipment"] =  $equipment;
             			$importArray["model"] =  $order_items->included[0]->attributes->sku_code;
                        $importArray['order_id'] =$value1->attributes->number;
                       
             			break;

             		}
             	}
             	if (isset($importArray["email"])){
             		$objectManager = \Magento\Framework\App\ObjectManager::getInstance()->create('Dolphin\Roccaapi\Block\Detail')->emailLink($importArray);
             	}
             	
             }
     
        }
     
    }

    function getToken() {
         $curl = curl_init();
       		curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rocca-1794.commercelayer.io/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"grant_type\": \"client_credentials\",\n  \"client_id\": \"bb52c13d96c22f10b1dcc1e464c2797a98e251c248f088f168052b1c36481912\",\n  \"client_secret\": \"53d4c8b2bdea1a24c83d539dca1ccdef6576e7d6e98cc40f83a62fecec15a0cc\"\n}",
            CURLOPT_HTTPHEADER => array(
   			 "Content-Type: application/json",
   			 "cache-control: no-cache"
 			 ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $autho = json_decode($response);
        
        return $autho;
    }

    function getOrderDetails($token , $day) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rocca-1794.commercelayer.io/api/orders?include=billing_address,shipments&filter[q][status_eq]=approved&filter[q][placed_at_gt]=".$day."&fields[orders]=number,customer_email,placed_at,shipments,billing_address&fields[shipments]=status&fields[addresses]=first_name,last_name,line_1,line_2,city,zip_code,state_code,country_code,phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
    		"Authorization: Bearer ".$token."",
  			),
        ));

        $response = curl_exec($curl);
        $orderData = json_decode($response);
        return $orderData;
    }

    function getItemdetails($token,$shipmentid) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://rocca-1794.commercelayer.io/api/shipments/".$shipmentid."/parcels?include=parcel_line_items&fields[parcels]=reference&fields[parcel_line_items]=sku_code,reference",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
   			 "Authorization: Bearer ".$token."",
  			),
        ));

        $response = curl_exec($curl);
        $orderData = json_decode($response);
        return $orderData;
    }

}
