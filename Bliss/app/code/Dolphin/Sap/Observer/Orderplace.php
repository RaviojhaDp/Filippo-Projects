<?php
namespace Dolphin\Sap\Observer;

class Orderplace implements \Magento\Framework\Event\ObserverInterface {

	protected $_helper;

	public function __construct(
		\Dolphin\Sap\Helper\Data $helper
	) {
		$this->_helper = $helper;
	}

	public function execute(\Magento\Framework\Event\Observer $observer) {
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/rv_order_place.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);

		$order = $observer->getEvent()->getInvoice()->getOrder();;
		$response = $this->_helper->getcallApi($order);
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
	    $xml = new \SimpleXMLElement($response);
	    $body = $xml->xpath('//soap-env:Body');
	    $response = json_decode(json_encode((array) $body), true);
	    $logger->info(print_r($response,true));
	    //if(isset($response[0]["n0ZBLE_SALES_ORDER_GETDETAILResponse"]["EV_TRACK_NUMB"])){
	      if(@$response[0]["n0ZBLE_SALES_ORDER_CREATEResponse"]["EV_ESITO"] == "OK"){
	      	$sapOrderId = @$response[0]["n0ZBLE_SALES_ORDER_CREATEResponse"]["EV_VBELN"];
	      	$order->setSapOrderId($sapOrderId); // set SAP order ID
	      	$order->setTrackflag('0'); 
        	 try {
			       $order->save();
			    } catch (\Exception $e) {
			       $logger->info('Error with Order SAP. Not getting ORDER ID from SAP');
				   $logger->info($e);
			}
	      }

	   	
		$logger->info('Yes its done');
		$logger->info(print_r($response,true));
		return $this;
	     // }
	}
}