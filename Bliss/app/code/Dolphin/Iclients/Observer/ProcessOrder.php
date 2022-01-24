<?php
namespace Dolphin\Iclients\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class ProcessOrder implements ObserverInterface
{

    protected $_helper;
    protected $request;

    public function __construct(
        OrderRepositoryInterface $OrderRepositoryInterface,
        \Magento\Framework\App\Request\Http $request,
        \Dolphin\Iclients\Helper\Data $helper
    ) {
        $this->orderRepository = $OrderRepositoryInterface;
        $this->request = $request;
        $this->_helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $order = $observer->getEvent()->getOrder();
        $order_number = $order->getIncrementId();
        $url_param = $order->getUrlParam();
        $client = $this->_helper->getCookie('client_name');
        if ($client) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/clienttest.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);

            $logger->info('client iframe');
            $logger->info($client);
            $logger->info($order_number);
            if ($this->_helper->getCookie('client_name')) {
                $order->setData('url_param', $client);
                $order->save();
            }
            $logger->info('client iframe done');
            //$this->_helper->delete('client_name');
            //$this->_helper->setCookie('client_name', '');
        }

        //exit;
    }
}
