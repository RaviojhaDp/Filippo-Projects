<?php
namespace Dolphin\Iclients\Block;

use Magento\Framework\View\Element\Template;

/**
 * Dolphin Iclients Page block
 */
class Iclients extends Template
{

    protected $_storeManager;
    protected $_helper;
    protected $request;
    /**
     * @var \Dolphin\Iclients\Model\Iclients
     */
    protected $iclients;

    /**
     * Iclients factory
     *
     * @var \Dolphin\Iclients\Model\IclientsFactory
     */
    protected $iclientsFactory;

    /**
     * @var \Dolphin\Iclients\Model\ResourceModel\Iclients\CollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @var \Dolphin\Iclients\Model\ResourceModel\Iclients\Collection
     */
    protected $items;

    /**
     * Iclients constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Dolphin\Iclients\Model\Iclients $iclients
     * @param \Dolphin\Iclients\Model\IclientsFactory $iclientsFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Dolphin\Iclients\Model\Iclients $iclients,
        \Dolphin\Iclients\Model\IclientsFactory $iclientsFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Dolphin\Iclients\Model\ResourceModel\Iclients\CollectionFactory $itemCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request,
        array $data = [],
        \Dolphin\Iclients\Helper\Data $helper
    ) {
        $this->iclients = $iclients;
        $this->iclientsFactory = $iclientsFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->request = $request;
        parent::__construct($context, $data);
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }
    public function getMediaUrl($path)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $path;
    }
    public function getClientData()
    {
        if (!$this->hasData('iclients')) {
            $client = $this->request->getParam('client');
            if (($client == '') && $this->_helper->getCookie('client_name')) {
                $client = $this->_helper->getCookie('client_name');
            }
            $store_id = $this->getStoreId();
            //echo $client;
            //exit;
            if ($client) {
                /** @var \Dolphin\Iclients\Model\ResourceModel\Iclients\CollectionFactory $itemCollectionFactory */
                $iclients = $this->itemCollectionFactory->create();
                $iclients->addFieldToFilter('url_param', $client);
                //$iclients->addFieldToFilter('store_id', array('in' => $store_id));
                $iclients = $iclients->getFirstItem();
            } else {
                $iclients = $this->iclients;
            }
            $this->setData('iclients', $iclients);

            //echo $this->_storeManager->getStore()->getId();
        }
        return $this->getData('iclients');
    }

    public function getClientDataById($id)
    {
        if (!$this->hasData('iclients')) {
            /** @var \Dolphin\Iclients\Model\ResourceModel\Iclients\CollectionFactory $itemCollectionFactory */
            $iclients = $this->itemCollectionFactory->create();
            $iclients->addFieldToFilter('ic_id', $id);
            $iclients = $iclients->getFirstItem();
            return $iclients;
        }
    }
}
