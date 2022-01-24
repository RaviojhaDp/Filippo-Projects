<?php

namespace Dolphin\Iclients\Helper;

use Dolphin\Iclients\Model\Iclients;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

//use Mageplaza\Affiliate\Helper\Calculation;

class Data extends AbstractHelper {

	/**
	 * Name of Cookie that holds private content version
	 */
	const COOKIE_NAME = 'client_name';

	/**
	 * Cookie life time
	 */
	const COOKIE_LIFE = 31556952;

	/**
	 * @var \Magento\Framework\Stdlib\CookieManagerInterface
	 */
	protected $cookieManager;

	/**
	 * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
	 */
	protected $cookieMetadataFactory;

	/**
	 * @var $scopeConfigInterface
	 */
	private $scopeConfigInterface;

	/**
	 * @var \Magento\Framework\Session\SessionManagerInterface
	 */
	protected $sessionManager;

	/**
	 * [$orderRepository description]
	 * @var [type]
	 */
	protected $orderRepository;

	/**
	 * [$searchCriteriaBuilder description]
	 * @var [type]
	 */
	protected $searchCriteriaBuilder;

	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		ScopeConfigInterface $scopeConfigInterface,
		CookieManagerInterface $cookieManager,
		CookieMetadataFactory $cookieMetadataFactory,
		SessionManagerInterface $sessionManager,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
		Iclients $iclients
		//Calculation $calculation
	) {

		$this->scopeConfigInterface = $scopeConfigInterface;
		$this->cookieManager = $cookieManager;
		$this->cookieMetadataFactory = $cookieMetadataFactory;
		$this->sessionManager = $sessionManager;
		$this->orderRepository = $orderRepository;
		$this->searchCriteriaBuilder = $searchCriteriaBuilder;
		$this->iclients = $iclients;
		//$this->calculation = $calculation;
		parent::__construct($context);
	}

	/**
	 * Get data from cookie set in remote address
	 *
	 * @return value
	 */
	public function getCookie($name) {
		return $this->cookieManager->getCookie($name);
	}

	/**
	 * Set data to cookie in remote address
	 *
	 * @param [string] $value    [value of cookie]
	 * @param integer $duration [duration for cookie] 7 Days
	 *
	 * @return void
	 */
	public function setCookie($value, $duration = 31556952) {
		$metadata = $this->cookieMetadataFactory
			->createPublicCookieMetadata()
			->setDuration($duration)
			->setPath($this->sessionManager->getCookiePath())
			->setDomain($this->sessionManager->getCookieDomain());

		$this->cookieManager->setPublicCookie(self::COOKIE_NAME, $value, $metadata);

	}

	/**
	 * delete cookie remote address
	 *
	 * @return void
	 */
	public function delete($name) {
		$this->cookieManager->deleteCookie(
			$name,
			$this->cookieMetadataFactory
				->createCookieMetadata()
				->setPath($this->sessionManager->getCookiePath())
				->setDomain($this->sessionManager->getCookieDomain())
		);
	}

	/**
	 * @return var
	 */
	public function getCookielifetime() {
		return self::COOKIE_LIFE;
	}
	public function getClientData() {
		return "ccc";
	}

	public function getClientDataById() {

	}

// 	public function selloutInsert() {

// 		$returnData = array("status" => false, "msg" => __("Error ECORNER_SELLOUT_INSERT to SAP. Please try again later"));
	// 		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/EcornerSelloutInsertApi.log');
	// 		$logger = new \Zend\Log\Logger();
	// 		$logger->addWriter($writer);

// 		$ID_SOURCE = $this->scopeConfigInterface->getValue('ecorner/id_source/source', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	// 		$apiUrl = $this->scopeConfigInterface->getValue('ecorner/id_source/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

// 		$searchCriteria = $this->searchCriteriaBuilder
	// 			->addFilter('affiliate_key', '', 'neq')
	// 			->addFilter('url_param', '', 'neq')
	// 			->addFilter('ecorner_sellout', '0', 'eq')
	// 			->addFilter('status', 'canceled', 'neq')
	// 		/*->addFilter('status', 'closed', 'neq')*/
	// 			->create();
	// 		$orders = $this->orderRepository->getList($searchCriteria);
	// 		//echo $orders->getSelect();exit;
	// 		$SAPPost = false;
	// 		$json = array();
	// 		foreach ($orders->getItems() as $order) {
	// 			//echo "Order# " . $order->getIncrementId() . '<br>';
	// 			/*if ($order->getIncrementId() != '000000958') {
	// 	            continue;
	// */
	// 			$_ShippingObject = $order->getShippingAddress();

// 			$IDENTIFY = $order->getIncrementId();
	// 			$DOC_DATE = $order->getCreatedAt();
	// 			$CUSTOMER_EMAIL = $order->getCustomerEmail();
	// 			$CUSTOMER_NAME = $_ShippingObject->getData('firstname') . ' ' . $_ShippingObject->getData('lastname');
	// 			//$RESELLER_CODE = $order->getAffiliateKey();
	// 			$VALUE = $order->getBaseGrandTotal();
	// 			//$COMMISSION = $order->getBaseAffiliateDiscountAmount();
	// 			$affiliateCommission = $this->calculation->unserialize($order->getAffiliateCommission());
	// 			$itemCommission = 0;
	// 			if (is_array($affiliateCommission) && count($affiliateCommission)) {
	// 				foreach ($affiliateCommission as $campaign) {
	// 					foreach ($campaign as $tierId => $commission) {
	// 						$itemCommission += $commission;
	// 					}
	// 				}
	// 			}
	// 			$COMMISSION = $itemCommission;
	// 			$items = $order->getAllVisibleItems();

// 			$RESELLER_CODE = '';
	// 			if ($order->getUrlParam()) {
	// 				$collectionIclient = $this->iclients->getCollection()->addFieldToFilter('url_param', $order->getUrlParam())->getFirstItem();
	// 				if (!empty($collectionIclient)) {
	// 					$RESELLER_CODE = $collectionIclient->getSapCode();
	// 				}
	// 			}

// 			foreach ($items as $item) {
	// 				$PRODUCT_REFERENCE = $item->getSku();
	// 				$PAP = $item->getBasePrice();
	// 				$QTY = $item->getQtyOrdered();
	// 				//$COMMISSION = $item->getBaseAffiliateDiscountAmount();

// 				/* api call */
	// 				$token = $this->_authenticate();
	// 				if ($token['success'] == 0) {
	// 					//return array("success"=>0,"message"=>$token['message']);
	// 					$logger->info("----_authenticate----");
	// 					$logger->info($order->getIncrementId() . " : " . $token['message']);
	// 					$order->setSapError(1);
	// 					$order->save();
	// 					return $returnData;
	// 				}
	// 				$api_params['ID_SOURCE'] = $ID_SOURCE; //'MAGENTO_CALDERONI'
	// 				$api_params['IDENTIFY'] = $IDENTIFY;
	// 				$api_params['CUSTOMER_EMAIL'] = $CUSTOMER_EMAIL;
	// 				$api_params['CUSTOMER_NAME'] = $CUSTOMER_NAME;
	// 				$api_params['DOC_DATE'] = $DOC_DATE;
	// 				$api_params['RESELLER_CODE'] = $RESELLER_CODE;
	// 				//$api_params['RESELLER_CODE'] = '0030055044';
	// 				$api_params['PRODUCT_REFERENCE'] = $PRODUCT_REFERENCE;
	// 				$api_params['QTY'] = $QTY;
	// 				$api_params['PAP'] = $PAP;
	// 				$api_params['VALUE'] = $VALUE;
	// 				$api_params['COMMISSION'] = $COMMISSION;
	// 				/*echo "<pre>";
	// 					                print_r($api_params);
	// 				*/
	// 				$url1 = $apiUrl . '/wsservice/services/damiani-json/execute?action=ECORNER_SELLOUT_INSERT&';
	// 				$url1 .= "parameter=" . (json_encode($api_params));

// 				//echo $url1; exit;

// 				$urlquotes = str_replace('"', '%22', $url1);
	// 				$finalurl = str_replace(" ", '%20', $urlquotes);

// 				$ch = curl_init();
	// 				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	// 				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// 				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// 				curl_setopt($ch, CURLOPT_COOKIEFILE, $token['message']);
	// 				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	// 				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	// 				//curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
	// 				curl_setopt($ch, CURLOPT_URL, $finalurl); //Url together with parameters
	// 				$response = curl_exec($ch);
	// 				/*echo "<pre>";
	// 					                print_r($response);
	// 				*/
	// 				if (curl_errno($ch)) {
	// 					return $returnData;
	// 				}

// 				if (strpos($response, '<ns:return>') !== false) {
	// 					$response = $this->extractString($response, '<ns:return>', '</ns:return>');
	// 				}
	// 				$json = json_decode($response, true);
	// 				if ($json['return'] == "OK") {
	// 					$SAPPost = true;
	// 				}
	// 				//$this->selloutUpdate($order, 'ACTIVE');exit;
	// 			}
	// 			if ($SAPPost) {
	// 				$order->setEcornerSellout(1);
	// 				$order->save();
	// 				$logger->info("----ECORNER_SELLOUT_INSERT SUCCESS----");
	// 				$logger->info($order->getIncrementId() . " : " . $response);
	// 				//return $returnData;
	// 			} else {
	// 				$order->setSapError(1);
	// 				$order->save();
	// 				$logger->info($response);
	// 				$logger->info("----ECORNER_SELLOUT_INSERT ERROR----");
	// 				$logger->info($order->getIncrementId() . " : " . $response);
	// 				//return $returnData;
	// 			}
	// 		}

// 	}

// 	public function selloutUpdate($order, $status) {
	// 		$returnData = array("status" => false, "msg" => __("Error ECORNER_SELLOUT_UPDATE to SAP. Please try again later"));

// 		$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/EcornerSelloutUpdateApi.log');
	// 		$logger = new \Zend\Log\Logger();
	// 		$logger->addWriter($writer);

// 		$IDENTIFY = $order->getIncrementId();
	// 		//$COMMISSION = $this->affiliateHelper->unserialize($order->getAffiliateCommission());

// 		$affiliateCommission = $this->calculation->unserialize($order->getAffiliateCommission());
	// 		$itemCommission = 0;
	// 		if (is_array($affiliateCommission) && count($affiliateCommission)) {
	// 			foreach ($affiliateCommission as $campaign) {
	// 				foreach ($campaign as $tierId => $commission) {
	// 					$itemCommission += $commission;
	// 				}
	// 			}
	// 		}
	// 		$COMMISSION = $itemCommission;

// 		$ID_SOURCE = $this->scopeConfigInterface->getValue('ecorner/id_source/source', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	// 		$apiUrl = $this->scopeConfigInterface->getValue('ecorner/id_source/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

// 		/* api call */
	// 		$token = $this->_authenticate();
	// 		/*echo "<pre>";
	// 			        print_r($token);
	// 		*/
	// 		if ($token['success'] == 0) {
	// 			//return array("success"=>0,"message"=>$token['message']);
	// 			$logger->info("----_authenticate----");
	// 			$logger->info($order->getIncrementId() . " : " . $token['message']);
	// 			return $returnData;
	// 		}

// 		$api_params['ID_SOURCE'] = $ID_SOURCE; //'MAGENTO_CALDERONI'
	// 		$api_params['IDENTIFY'] = $IDENTIFY;
	// 		$api_params['COMMISSION'] = $COMMISSION;
	// 		$api_params['STATUS'] = $status;
	// 		/*echo "<pre>";
	// 			        print_r($api_params);
	// 		*/
	// 		//https://testapp.damianigroup.com
	// 		$url1 = $apiUrl . '/wsservice/services/damiani-json/execute?action=ECORNER_SELLOUT_UPDATE&';
	// 		$url1 .= "parameter=" . (json_encode($api_params));

// 		$urlquotes = str_replace('"', '%22', $url1);
	// 		$finalurl = str_replace(" ", '%20', $urlquotes);
	// 		//echo $finalurl . "<br>";
	// 		$ch = curl_init();
	// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// 		curl_setopt($ch, CURLOPT_COOKIEFILE, $token['message']);
	// 		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	// 		//curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
	// 		curl_setopt($ch, CURLOPT_URL, $finalurl); //Url together with parameters
	// 		$response = curl_exec($ch);
	// 		/*echo "<pre>";
	//         print_r($response);exit;*/
	// 		if (curl_errno($ch)) {
	// 			return $returnData;
	// 		}

// 		if (strpos($response, '<ns:return>') !== false) {
	// 			$response = $this->extractString($response, '<ns:return>', '</ns:return>');
	// 		}
	// 		$json = json_decode($response, true);
	// 		if ($json['return'] == "OK") {
	// 			$logger->info($status . ' || ' . $response);
	// 			$logger->info("----ECORNER_SELLOUT_" . $status . " SUCCESS----");
	// 			$logger->info($status . ' || ' . $order->getIncrementId() . " : " . $response);
	// 		} else {
	// 			$order->setSapError(1);
	// 			$order->save();
	// 			$logger->info($status . ' || ' . $response);
	// 			$logger->info("----ECORNER_SELLOUT_" . $status . " ERROR----");
	// 			$logger->info($status . ' || ' . $order->getIncrementId() . " : " . $response);
	// 		}
	// 	}

// 	public function _authenticate() {
	// 		$data = [
	// 			'login' => 'magento',
	// 			'password' => 'arGK!eR5',
	// 		];

// 		$url = "https://testapp.damianigroup.com/wsservice/authenticate.do";
	// 		$params = '';
	// 		foreach ($data as $key => $value) {
	// 			$params .= $key . '=' . $value . '&';
	// 		}

// 		$params = trim($params, '&');

// 		$ch = curl_init();
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// 		$token = tempnam('/tmp', 'cookie');
	// 		curl_setopt($ch, CURLOPT_COOKIEJAR, $token);

// 		// helpful options
	// 		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// 		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

// 		curl_setopt($ch, CURLOPT_URL, $url . '?' . $params); //Url together with parameters

// 		$response = curl_exec($ch);
	// 		curl_close($ch);

// 		if (trim($response) != 'Succesful') {
	// 			//echo 'Curl error: ' . curl_error($ch);
	// 			return array("success" => 0, "message" => "Authentication Failed");
	// 		}

// 		return array("success" => 1, "message" => $token);
	// 	}
	// 	public function extractString($string, $start, $end) {
	// 		$string = " " . $string;
	// 		$ini = strpos($string, $start);
	// 		if ($ini == 0) {
	// 			return "";
	// 		}

// 		$ini += strlen($start);
	// 		$len = strpos($string, $end, $ini) - $ini;
	// 		return substr($string, $ini, $len);
	// 	}
}
