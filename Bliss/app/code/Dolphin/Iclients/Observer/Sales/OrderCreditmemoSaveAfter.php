<?php

namespace Dolphin\Iclients\Observer\Sales;

class OrderCreditmemoSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    protected $logger;
    protected $_helper;
    protected $orderFactory;

    /**
     * [__construct description]
     * @param \Psr\Log\LoggerInterface                      $logger       [description]
     * @param \Calderoni\Ecorner\Helper\Data                $helper       [description]
     * @param \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory [description]
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Dolphin\Iclients\Helper\Data $helper,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory
    ) {
        $this->logger = $logger;
        $this->_helper = $helper;
        $this->orderFactory = $orderFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();
        //$order = $this->orderFactory->create()->loadByIncrementId($order->getIncrementId());
        if (($order->getAffiliateKey() != '') && ($order->getUrlParam() != '')) {
            $this->_helper->selloutUpdate($order, 'RETURN'); //30 - RETURN
        }
    }
}
