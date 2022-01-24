<?php
namespace Dolphin\Sap\Model\Checkout\Cart;

use Magento\Framework\Exception\LocalizedException;

class Plugin {

	protected $blissApi;
	protected $resultFactory;
	protected $redirect;
	protected $response;
	protected $product;
	/**
	 * Plugin constructor.
	 *
	 * @param \Magento\Checkout\Model\Session $checkoutSession
	 */
	public function __construct(
		\Dolphin\Sap\Model\BlissApi $blissApi,
		\Magento\Framework\Controller\ResultFactory $resultFactory,
		\Magento\Framework\App\Response\RedirectInterface $redirect,
		\Magento\Framework\App\Response\Http $response,
		\Magento\Catalog\Model\Product $product
	) {
		$this->blissApi = $blissApi;
		$this->resultFactory = $resultFactory;
		$this->redirect = $redirect;
		$this->response = $response;
		$this->product = $product;
	}

	/**
	 * beforeAddProduct
	 *
	 * @param      $subject
	 * @param      $productInfo
	 * @param null $requestInfo
	 *
	 * @return array
	 * @throws LocalizedException
	 */
	public function beforeAddProduct($subject, $productInfo, $requestInfo = null) {

		$productId = $productInfo->getId();
		// for configurable product
		if (isset($_POST['selected_configurable_option']) && $_POST['selected_configurable_option'] != '') {

			$configproductId = $_POST['selected_configurable_option'];
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$simpleproduct = $objectManager->create('Magento\Catalog\Model\Product')->load($configproductId);
			//$productSku = '000000000020063970'; //right now passed static sku, use this after product import
			$productSku = '0000000000' . $simpleproduct->getSku();

			$status = $this->blissApi->getstockStatus($productSku);

			if ($status[0]['n0ZBLE_MATERIAL_STATUSResponse']['EV_ESITO'] != 'OK') {
				// get result ok
				throw new LocalizedException(__('Something wrong loading stock data'));
			}
			if ($status[0]['n0ZBLE_MATERIAL_STATUSResponse']['EV_STATO_MAT'] == "UNLO") {
				// enabled
				// if status is unlock means enabled, and set is_locked 1 in our system
				$simpleproduct->setData('is_locked', 1);
				$simpleproduct->save();
				return [$simpleproduct, $requestInfo];
			} else {
				// if lock that means disable throw error
				throw new LocalizedException(__('We are sorry, this product is out of stock in this moment'));
			}
		} else {
			// for simple single product
			$productSku = '0000000000' . $productInfo->getSku();
			$status = $this->blissApi->getstockStatus($productSku);

			if ($status[0]['n0ZBLE_MATERIAL_STATUSResponse']['EV_ESITO'] != 'OK') {
				// get result ok
				throw new LocalizedException(__('Something wrong loading stock data'));
			}
			if ($status[0]['n0ZBLE_MATERIAL_STATUSResponse']['EV_STATO_MAT'] == "UNLO") {
				// enabled
				// if status is unlock means enabled,and set is_locked 1 in our system
				$productInfo->setData('is_locked', 1);
				$productInfo->save();
				return [$productInfo, $requestInfo];
			} else {
				// if lock that means disable throw error
				throw new LocalizedException(__('We are sorry, this product is out of stock in this moment'));
			}
		}
	}
}