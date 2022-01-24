<?php
namespace Dolphin\Iclients\Cron;

class SelloutInsert
{

    protected $logger;
    protected $_helper;
    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Dolphin\Iclients\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->_helper = $helper;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info("Cronjob SelloutInsert is executed.");
        $sellout = $this->_helper->selloutInsert();
        $this->logger->info("Cronjob SelloutInsert is finish.");
        //return $sellout;
    }
}
