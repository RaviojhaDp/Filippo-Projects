<?php

namespace Dolphin\Sap\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Directory\Model\RegionFactory;

class Data extends AbstractHelper {

	protected $storeManager;
	protected $objectManager;
	protected $regionFactory;

	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		RegionFactory $regionFactory
	) {
		$this->storeManager = $storeManager;
		$this->objectManager = $objectManager;
		$this->regionFactory = $regionFactory;
		parent::__construct($context);
	}

	public function getcallApi($order) {
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/blisstestv13.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$customerId = $order->getCustomerId();
		$order_id = $order->getIncrementId();
		$billingFirstName = $order->getBillingAddress()->getFirstName();
		$billinglastName = $order->getBillingAddress()->getlastName();
		$firstname = ($order->getCustomerFirstname()) ? $order->getCustomerFirstname() : $billingFirstName;
		$lastname = ($order->getCustomerLastname()) ? $order->getCustomerLastname() : $billinglastName;
		$email = $order->getCustomerEmail();
		$dob = $order->getCustomerDob();
		$taxvat = $order->getCustomerTaxvat();
		/*$logger->info("order id ----> ".$order_id);*/

		$shippingAddressObj = $order->getShippingAddress();
		$shippingAddressArray = $shippingAddressObj->getData();
		$street = $shippingAddressArray['street'];
		$city = $shippingAddressArray['city'];
		$telephone = $shippingAddressArray['telephone'];
		$country_id = $shippingAddressArray['country_id'];
		$postcode = $shippingAddressArray['postcode'];
		$region_id = $shippingAddressArray['region_id'];
		$region = $this->regionFactory->create()->load($region_id);
		$region_code = '';
		if($country_id == "IT"){
			$region_code = $region->getCode();
		}

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$logger->info($order->getQuoteId());

		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$tableName = $resource->getTableName('amasty_amcheckout_quote_custom_fields');
		$sql = "SELECT * FROM `amasty_amcheckout_quote_custom_fields` where `quote_id` =" . $order->getQuoteId();
		$result = $connection->fetchAll($sql);

		$invoiceval = "";
		$vatNumber = "";
		$businessName = "";
		$pec = "";
		$sdi = "";

		// invoice
		if (isset($_COOKIE['requestinvoice']) && $_COOKIE['requestinvoice'] == 'true') {
			$invoiceval = "X";
			// pass business name, vat id , PEC, SDI when invoice checked
			$vatNumber = $shippingAddressArray['vat_id'];
			$businessName = $result[0]['shipping_value'];
			$pec = $result[1]['shipping_value'];
			$sdi = $result[2]['shipping_value'];
		}

		// shipping cost (IV_FREIGHT = X or blank, IV_FREIGHT_AMOUNT)
		$shipping = "";
		$shippingAmount = "";

		$logger->info($order->getShippingAmount());
		$logger->info($order->getShippingMethod());

		if ($order->getShippingAmount() == 0 && $order->getShippingMethod() == "tablerate_bestway") {
			$shipping = "X";
			$shippingAmount = $order->getShippingAmount();
		} else if ($order->getShippingAmount() == 0) {
			$shipping = "";
			$shippingAmount = "";
		} else {
			$shipping = "X";
			$shippingAmount = $order->getShippingAmount();
		}

		$logger->info("order start");
		$couponCode = "";
		// coupon code
		if ($order->getCouponCode() != '') {
			$couponCode = $order->getCouponCode();
		}

		$itemArrray = array();
		$destinations = '';
		foreach ($order->getAllItems() as $item) {
			$ProdustIds[] = $item->getProductId();
			$proSku = $item->getSku(); // product sku
			//$proSku = '20004138';
			$proPrice = $item->getPrice() - $item->getDiscountAmount(); // product price
			$proQty = intval($item->getQtyOrdered()); // product qty
			$destinations .= '<item><MATNR xmlns="">0000000000' . $proSku . '</MATNR><KWMENG xmlns="">' . $proQty . '</KWMENG><ORIGINAL_PRICE xmlns="">' . $proPrice . '</ORIGINAL_PRICE><ACTUAL_PRICE xmlns="">' . $proPrice . '</ACTUAL_PRICE><PROMO_CODE xmlns="">' . $couponCode . '</PROMO_CODE></item>';
		}

		$input_xml = '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
               <Header/>
               <Body>
                <ZBLE_SALES_ORDER_CREATE xmlns="urn:sap-com:document:sap:rfc:functions">
                    <!--Optional:-->
                    <IS_CUSTOMER_INFO xmlns="">
                        <NAME_FIRST xmlns="">' . $firstname . '</NAME_FIRST>
                        <NAME_LAST xmlns="">' . $lastname . '</NAME_LAST>
                        <STREET xmlns="">' . $street . '</STREET>
                        <POST_CODE1 xmlns="">' . $postcode . '</POST_CODE1>
                        <CITY1 xmlns="">' . $city . '</CITY1>
                        <REGION xmlns="">' . $region_code . '</REGION>
                        <COUNTRY xmlns="">' . $country_id . '</COUNTRY>
                        <HOME_CITY xmlns=""></HOME_CITY>
                        <TEL_NUMBER xmlns="">' . $telephone . '</TEL_NUMBER>
                        <SMTP_ADDR xmlns="">' . $email . '</SMTP_ADDR>
                        <STCD1 xmlns="">' . $vatNumber . '</STCD1>
                        <SMTP_PEC xmlns="">' . $pec . '</SMTP_PEC>
                        <SDI_CODE xmlns="">' . $sdi . '</SDI_CODE>
                        <FPROF xmlns="">X</FPROF>
                    </IS_CUSTOMER_INFO>
                    <IV_INVOICE_REQUESTED xmlns="">' . $invoiceval . '</IV_INVOICE_REQUESTED>
                    <IT_MATERIAL_TAB xmlns="">
                       ' . "$destinations" . '
                    </IT_MATERIAL_TAB>
                    <IV_FREIGHT xmlns="">' . (int)$shipping . '</IV_FREIGHT>
                    <IV_FREIGHT_AMOUNT xmlns="">' . (int)$shippingAmount . '</IV_FREIGHT_AMOUNT>
                    <IV_PLTYP xmlns="">08</IV_PLTYP>
                    <!--Optional:-->
                    <IV_SOURCE xmlns="">E</IV_SOURCE>
                    <!--Optional:-->
                    <IV_SPART xmlns="">BL</IV_SPART>
                    <!--Optional:-->
                    <IV_VKORG xmlns="">Z001</IV_VKORG>
                    <!--Optional:-->
                    <IV_WAERS xmlns="">EUR</IV_WAERS>
                    <!--Optional:-->
                    <IV_WERKS xmlns="">Z001</IV_WERKS>
                    <!--Optional:-->
                    <IV_BSTKD xmlns="">' . $order_id . '</IV_BSTKD>
                </ZBLE_SALES_ORDER_CREATE>
               </Body>
            </Envelope>';

		$logger->info($input_xml);
		return $this->getSapOrderCall($input_xml);
	}

	public function getSapOrderCall($input_xml) {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://ASSVIL.DAMIANI.IT:8012/sap/bc/srt/rfc/sap/zble_sales_order_create/100/zble_sales_order_create/zble_sales_order_create_bind',
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
        		'Authorization: Basic bmljb2xwYW86MzNMZW9wZXJleg=='
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		header("Content-type: text/xml; charset=utf-8");
		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/DolphinSAPorder.log');
		$logger = new \Zend\Log\Logger();
		$logger->addWriter($writer);
		$logger->info("stilaliver3333");
		$logger->info($response);

		return $response;
	}

}
