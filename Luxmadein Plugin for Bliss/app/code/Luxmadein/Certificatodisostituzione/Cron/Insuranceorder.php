<?php

namespace Luxmadein\Certificatodisostituzione\Cron;

class Insuranceorder {
	protected $logger;
	private $_transportBuilder;
	protected $escaper;
	protected $_objectManager;
	protected $_scopeConfig;

	public function __construct(
		\Psr\Log\LoggerInterface $loggerInterface,
		\Magento\Framework\ObjectManagerInterface $objectmanager,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder,
		\Magento\Framework\Escaper $_escaper
	) {
		$this->logger = $loggerInterface;
		$this->_escaper = $_escaper;
		$this->_objectManager = $objectmanager;
		$this->scopeConfig = $scopeConfig;
		$this->_transportBuilder = $_transportBuilder;

	}

	public function execute() {
		$Enable = $this->scopeConfig->getValue("certificatodisostituzione/general/active", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if ($Enable == '0') {
			return true;
		}
		$model = $this->scopeConfig->getValue("certificatodisostituzione/general/model", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$serial = '';
		$equipment = '';
		$day = $this->scopeConfig->getValue("certificatodisostituzione/general/day_differ", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$brand = $this->scopeConfig->getValue("certificatodisostituzione/general/select_brand", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$senderEmail = $this->scopeConfig->getValue("certificatodisostituzione/general/sender_email", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if (strtolower($brand) == "damiani") {
			$logo_name = "logo-damiani-new.svg";
		}
		if (strtolower($brand) == "salvini") {
			$logo_name = "logo-salvini-new.svg";
		}
		if (strtolower($brand) == "rocca") {
			$logo_name = "logo-rocca-new.svg";
		}
		if (strtolower($brand) == "bliss") {
			$logo_name = "logo-bliss-new.svg";
		}

		$logo_url = "https://www.damianigroupcustomercare.com/media/" . $logo_name;

		$orderDatamodel = $this->_objectManager->get('Magento\Sales\Model\Order')->getCollection();
		$date = (new \DateTime())->modify('-' . $day . ' day');	
		//$date = (new \DateTime())->modify('-1 day');	
		$createdAt = $date->format('Y-m-d');	
		//$createdAt = "2019-08-26";	
		$orderDatamodel->addFieldToFilter('main_table.updated_at', ['lteq' => $date->format('Y-m-d 23:59:59')])	
			->addFieldToFilter('main_table.updated_at', ['gteq' => $date->format('Y-m-d 00:00:01')])	
			->addFieldToFilter('main_table.status', ['eq' => "complete"]);	
		$orderDatamodel->getSelect()	
			->join(	
				['sales_item' => 'sales_order_item'],	
				'main_table.entity_id = sales_item.order_id'	
			)	
			->where('`sales_item`.`name` NOT LIKE '."'%".'ADMIRAL'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'BULLONE'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'LIFE'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'MYWORDS'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'PREMIERE'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'ROYALE'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'SILVER STONE'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'SPEEDWAY 2.0'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'URBAN TAG'."%'".' AND `sales_item`.`name` NOT LIKE '."'%".'LOVE LETTERS'."%'"	
			)	
			->columns("sales_item.product_options");	
				
		//$this->logger->info($orderDatamodel->getSelect());

		foreach ($orderDatamodel->getData() as $orderData) {

			//$this->logger->info("-----------------------------START MASIL-------------------------------------".print_r($orderData));
			$orderItenDatamodel = $this->_objectManager->get('Magento\Sales\Model\Order')->load($orderData['entity_id']);
			$productLoad = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($orderData['product_id']);

			$items = $orderItenDatamodel->getAllVisibleItems();
			$sid = $orderData['store_id'];
			if ($sid == '1') {
				$language = "it";
				$etemp = "luxmadein_certificatodisostituzione_email_template_it";
			} else if ($sid == '2') {
				$language = "eu";
				$etemp = "luxmadein_certificatodisostituzione_email_template";
			} else {
				$language = "ch";
				$etemp = "luxmadein_certificatodisostituzione_email_template";
			}
			foreach ($items as $item) {
				$options = $item->getProductOptions();
				if (isset($options['options']) && !empty($options['options'])) {
					foreach ($options['options'] as $option) {

						if ($option['label'] == "Equipment Code") {
							$equipment = $option['option_value'];
						}
						if ($option['label'] == "Serial Number") {
							$serial = $option['option_value'];
						}
					}
				}
			}

			$IncrementId = $orderItenDatamodel->getData("increment_id");
			$customerID = $orderItenDatamodel->getData("customer_id");

			$customer_is_guest = $orderItenDatamodel->getData("customer_is_guest");
			if ($customer_is_guest == '1') {
				$BillingAddress = $orderItenDatamodel->getBillingAddress()->getData();
				$postcertiArray['flag'] = '1';
				$postcertiArray['customer_id'] = $orderItenDatamodel->getData("customer_id");
				$postcertiArray['firstname'] = $BillingAddress['firstname'];
				$postcertiArray['lastname'] = $BillingAddress['lastname'];
				$postcertiArray['gender'] = $orderItenDatamodel->getData('customer_gender');
				$postcertiArray['dob'] = $orderItenDatamodel->getData('customer_dob');
				$postcertiArray['telephone'] = $BillingAddress['telephone'];
				$postcertiArray['street'] = $BillingAddress['street'];
				$postcertiArray['city'] = $BillingAddress['city'];
				$postcertiArray['region'] = $BillingAddress['region'];
				$postcertiArray['country_id'] = $BillingAddress['country_id'];
				$postcertiArray['postcode'] = $BillingAddress['postcode'];
				$postcertiArray['region'] = $BillingAddress['region'];
				$postcertiArray['email'] = $orderItenDatamodel->getData('customer_email');
				$postcertiArray['brand'] = $brand;
				$postcertiArray['equipment'] = $equipment;
				//$postcertiArray['serial'] =  $serial;
				$postcertiArray['model'] = $productLoad->getSku();
				$postcertiArray['created_at'] = $orderItenDatamodel->getData("created_at");
				$postcertiArray['order_id'] = $orderItenDatamodel->getData("increment_id");
				$postcertiArray['logo_url'] = $logo_url;
			} else {

				$customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load($customerID);
				$BillingAddress = $orderItenDatamodel->getBillingAddress()->getData();
				$postcertiArray['flag'] = '1';
				$postcertiArray['customer_id'] = $orderItenDatamodel->getData("customer_id");
				$postcertiArray['firstname'] = $customerObj->getData("firstname");
				$postcertiArray['lastname'] = $customerObj->getData("lastname");
				$postcertiArray['gender'] = $customerObj['gender'];
				$postcertiArray['dob'] = $customerObj['dob'];
				$postcertiArray['telephone'] = $BillingAddress['telephone'];
				$postcertiArray['street'] = $BillingAddress['street'];
				$postcertiArray['city'] = $BillingAddress['city'];
				$postcertiArray['region'] = $BillingAddress['region'];
				$postcertiArray['country_id'] = $BillingAddress['country_id'];
				$postcertiArray['postcode'] = $BillingAddress['postcode'];
				$postcertiArray['region'] = $BillingAddress['region'];
				$postcertiArray['email'] = $customerObj['email'];
				$postcertiArray['brand'] = $brand;
				$postcertiArray['equipment'] = $equipment;
				//$postcertiArray['serial'] =  $serial;
				$postcertiArray['model'] = $productLoad->getSku();
				$postcertiArray['created_at'] = $orderItenDatamodel->getData("created_at");
				$postcertiArray['order_id'] = $orderItenDatamodel->getData("increment_id");
				$postcertiArray['logo_url'] = $logo_url;
			}

			$this->logger->debug(print_r($postcertiArray));

			$query = http_build_query($postcertiArray);
			$postcertiArray['urla'] = "https://www.damianigroupcustomercare.com/" . $language . '/' . $postcertiArray['brand'] . "/assicurazione.html?" . $query;
			$postObject = new \Magento\Framework\DataObject();
			$postObject->setData($postcertiArray);
			$error = false;

			$sender = [
				'name' => $this->_escaper->escapeHtml("Damiani Group"),
				'email' => $this->_escaper->escapeHtml($senderEmail),
			];

			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			$transport = $this->_transportBuilder
				->setTemplateIdentifier($etemp) // this code we have mentioned in the email_templates.xml
				->setTemplateOptions(
					[
						'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
						'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
					]
				)
				->setTemplateVars(['customer' => $postObject])
				->setFrom($sender)
				->addTo($postcertiArray['email'])
				//->addCc("damianicheck@mailinator.com")
				->getTransport();
			$transport->sendMessage();

		}
	}
}