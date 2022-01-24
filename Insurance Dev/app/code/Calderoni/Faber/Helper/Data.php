<?php
namespace Calderoni\Faber\Helper;

use Magento\Framework\Registry;
use Magento\Directory\Model\CurrencyFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $storeManager;
	protected $objectManager;
    protected $filesystem;
    protected $configFactory;
    protected $moduleReader;
    protected $customerSession;
    protected $jsonHelper;
    protected $blockFactory;
    protected $blockResource;
	
	public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Config\Model\Config\Factory $configFactory,
		\Magento\Framework\App\Filesystem\DirectoryList $filesystem,
		\Magento\Framework\Module\Dir\Reader $moduleReader,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\Json\Helper\Data $jsonHelper,
		\Magento\Cms\Model\BlockFactory $blockFactory,
		\Magento\Cms\Model\ResourceModel\Block $blockResource,
		CurrencyFactory $currencyFactory
    ) {
        $this->storeManager = $storeManager;
        $this->objectManager = $objectManager;
		$this->filesystem = $filesystem;
		$this->moduleReader = $moduleReader;
        $this->configFactory = $configFactory;
        $this->customerSession = $customerSession;
        $this->jsonHelper = $jsonHelper;
        $this->blockFactory = $blockFactory;
        $this->blockResource = $blockResource;
		$this->currencyCode = $currencyFactory->create();
        parent::__construct($context);
    }
	
	public function isCustomerLoggedIn(){
		return $this->customerSession->isLoggedIn();
	}
	public function getCustomerId(){
		return $this->customerSession->getId();
	}
	public function getCurrencySymbol()
	{
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrencyCode();
        $currency = $this->currencyCode->load($currentCurrency);
        return $currency->getCurrencySymbol();
    }
	public function getCurrencyCode()
	{
        return $currentCurrency = $this->storeManager->getStore()->getCurrentCurrencyCode();
    }
	
	public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
	public function getStoreId()
    {
        return $this->getCurrentStore()->getId();
    }
	public function getCurrentStore() {
        return $this->storeManager->getStore();
    }
	public function getWebsiteId()
    {
        return $this->getCurrentWebsite()->getId();
    }
	public function getCurrentWebsite() {
        return $this->storeManager->getWebsite();
    }
	public function getBaseUrl() {
        return $this->getCurrentStore()->getBaseUrl();
    }
	
	public function getMediaDirectoryPath()
    {
		return $this->storeManager->getStore()->getBaseMediaDir();
    }
	public function getMediaUrl($append="")
	{
		$mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		return $mediaUrl.$append;
	}
	
	public function getModuleImagesDirectory()
    {
        $viewDir = $this->moduleReader->getModuleDir(
            \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
            'Calderoni_Diamondsearch'
        );
        return $viewDir . '/frontend/web/images/';
    }
	public function getShapeImageUrl($shape)
    {
        return $this->getMediaUrl() . $shape;
    }
	
	public function getStaticBlock($identifier){
        try {
            $block = $this->blockFactory->create();
			$block->setStoreId($this->getStoreId());
			$this->blockResource->load($block, $identifier);
			if (!$block->getId()) {
				throw new NoSuchEntityException(__('CMS Block with identifier "%1" does not exist.', $identifier));
			}
			return $block;
        } catch(\Exception $e){
            //$this->logger->warning($e->getMessage());
        }
        return false;
    }

    public function getCmsBlockContent($identifier){
		
		$staticBlock = $this->getStaticBlock($identifier);
		
		if($staticBlock && $staticBlock->isActive()){
            return $staticBlock->getContent();
        }

        /* Optional ->setVariables(['number'=>213452345234] Usage in wysiwyfg 
{{var number}} */

        return __('Static block content not found');
    }

	public function jsonEncode($valueToEncode){
		return $this->jsonHelper->jsonEncode($valueToEncode);
		
	}
	public function jsonDecode($encodedValue){
		return $this->jsonHelper->jsonDecode($encodedValue);
	}

	public function isMobile()
    {
		if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)))
			return true;
		else return false;
    }
	
}