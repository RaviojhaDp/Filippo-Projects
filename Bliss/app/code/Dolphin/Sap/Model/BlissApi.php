<?php
namespace Dolphin\Sap\Model;

class BlissApi extends \Magento\Framework\Model\AbstractModel {

	protected $objectManager;
	protected $request;
	protected $_dir;

	public function __construct(
		\Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\ObjectManagerInterface $objectManager
	) {

		$this->objectManager = $objectManager;

		parent::__construct($context, $registry);
	}

	public function getstockStatus($productsku) {
		$input_xml = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
		   <Body>
		  	<ZBLE_MATERIAL_STATUS xmlns="urn:sap-com:document:sap:rfc:functions">
		     	<IV_MATNR xmlns="">' . $productsku . '</IV_MATNR>
		  	</ZBLE_MATERIAL_STATUS>
		   </Body>
		</Envelope>';

		return $this->getSapStatusCall($input_xml);
	}

	public function getSapStatusCall($input_xml) {
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/DolphinBeforeCart.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://ASSVIL.DAMIANI.IT:8012/sap/bc/srt/rfc/sap/zble_material_status/100/zble_material_status/zble_material_status_bind',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $input_xml,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: text/xml',
				'Authorization: Basic bmljb2xwYW86MzNMZW9wZXJleg==',
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
		$xml = new \SimpleXMLElement($response);
		$body = $xml->xpath('//soap-env:Body');
		$response = json_decode(json_encode((array) $body), true);
		$logger->info(print_r($response,true));
		return $response;
	}

}
