<?php
namespace Dolphin\Sap\Cron;

use Magento\Framework\Controller\ResultFactory;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;

class Tracklink
{
    protected $logger;
    protected $storeManager;
    protected $_pageFactory;

    public function __construct(\Psr\Log\LoggerInterface $logger, StoreManagerInterface $storeManager, \Magento\Framework\View\Result\PageFactory $pageFactory, TransportBuilder $_transportBuilder, Escaper $_escaper)
    {

        $this->logger = $logger;
        $this->_pageFactory = $pageFactory;
        $this->storeManager = $storeManager;
        $this->_escaper = $_escaper;
        $this->_transportBuilder = $_transportBuilder;
    }

    public function execute()
    {

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/tracklink.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('order query.........');

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderInterface = $objectManager->create('Magento\Sales\Model\Order');
        $order = $orderInterface->getCollection()
            ->addAttributeToFilter('trackflag', '0')
            ->addAttributeToFilter('sap_order_id', array('notnull' => true));
           $logger->info($order->getSelect());

        foreach ($order as $orderValue)
        {
            //if ($orderValue['trackflag'] == '0')
            //{
                $orderId = @$orderValue['entity_id'];
                $SapOrderId = @$orderValue['sap_order_id'];
                $TrackNumber = $this->callOrderDetailApi($SapOrderId);
                if(!$TrackNumber){
                    continue;
                }
                $order = $orderInterface->load($orderId);
                $order->setTracknumber($TrackNumber)->setTrackflag('1');
                try
                {
                    $orderSave = $order->save();
                    if($orderSave){
                        /*AUOTMATICALLY SHIPMENT: ORDER AUTHROIZE & CAPTURE*/
                        $order = $objectManager->create('Magento\Sales\Model\Order')->load($orderId);
                        // Check if order can be shipped or has already shipped
                            if (! $order->canShip()) {
                                throw new \Magento\Framework\Exception\LocalizedException(
                                                __('You can\'t create an shipment.')
                                            );
                            }

                            // Initialize the order shipment object
                            $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
                            $shipment = $convertOrder->toShipment($order);

                            // Loop through order items
                            foreach ($order->getAllItems() as $orderItem) {
                                // Check if order item has qty to ship or is virtual
                                if (! $orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                                    continue;
                                }

                                $qtyShipped = $orderItem->getQtyToShip();

                                // Create shipment item with qty
                                $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

                                // Add shipment item to shipment
                                $shipment->addItem($shipmentItem);
                            }

                            // Register shipment
                            $shipment->register();

                            $shipment->getOrder()->setIsInProcess(true);

                            try {
                                // Save created shipment and order
                                $shipment->save();
                                $shipment->getOrder()->save();

                                // Send email
                                $objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
                                    ->notify($shipment);

                                $shipment->save();
                            } catch (\Exception $e) {
                                throw new \Magento\Framework\Exception\LocalizedException(
                                                __($e->getMessage())
                                            );
                            }

                        /*AUOTMATICALLY SHIPMENT: ORDER AUTHROIZE & CAPTURE*/
                    }
                    $postObject = new \Magento\Framework\DataObject();
                    $postObject->setData(array(
                        "track_url" => "https://www.dhl.com/it-it/home/tracking/tracking-express.html?submit=1&tracking-id=".$TrackNumber,
                         "track" =>$TrackNumber ? $TrackNumber : '',
                        "customer_name" => $order->getCustomerName()
                    ));
                    $error = false;
                    $sender = ['name' => $this->_escaper->escapeHtml("Bliss") , 'email' => $this->_escaper->escapeHtml("noreply@bliss.it") ];

                    if($order->getStoreId() == '1'){
                        $storeScope = '1';
                        $template_code = "tracklink_email_template_it";
                    }else{
                        $storeScope = '2';
                        $template_code = "tracklink_email_template";
                    }
                    
                    //$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                    $transport = $this->_transportBuilder->setTemplateIdentifier($template_code) // this code we have mentioned in the email_templates.xml
                        ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                    'store' => $storeScope, ])->setTemplateVars(['order' => $postObject])->setFrom($sender)->addTo($order->getCustomerEmail())->addBcc('raviozha07@yopmail.com')->getTransport();
                    //$transport->sendMessage();
                    try
                    {
                        $transport->sendMessage();
                    }
                    catch(\Magento\Framework\Exception\NoSuchEntityException $e)
                    {
                        echo $e;
                    }

                    $logger->info("released from cart");
                    $logger->info('Cron Finished...');
                    return $this;
                }
                catch(\Exception $e)
                {
                    echo $e;
                }
            //}
        }
    }

    public function callOrderDetailApi($SapOrderId)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://ASSVIL.DAMIANI.IT:8012/sap/bc/srt/rfc/sap/zble_sales_order_getdetail/100/zble_sales_order_getdetail/zble_sales_order_getdetail_bind',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
		    <Body>
		        <ZBLE_SALES_ORDER_GETDETAIL xmlns="urn:sap-com:document:sap:rfc:functions">
		            <IV_VBELN xmlns="">'.$SapOrderId.'</IV_VBELN>
		        </ZBLE_SALES_ORDER_GETDETAIL>
		    </Body>
		</Envelope>',
            CURLOPT_HTTPHEADER => array(
               'Content-Type: text/xml',
                'Authorization: Basic bmljb2xwYW86MzNMZW9wZXJleg=='
            ) ,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        header("Content-type: text/xml; charset=utf-8");
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
        $xml = new \SimpleXMLElement($response);
        $body = $xml->xpath('//soap-env:Body');
        $response = json_decode(json_encode((array)$body) , true);
        if (!empty($response[0]["n0ZBLE_SALES_ORDER_GETDETAILResponse"]["EV_TRACK_NUMB"]))
        {
            return $response[0]["n0ZBLE_SALES_ORDER_GETDETAILResponse"]["EV_TRACK_NUMB"];
        }

    }
}

