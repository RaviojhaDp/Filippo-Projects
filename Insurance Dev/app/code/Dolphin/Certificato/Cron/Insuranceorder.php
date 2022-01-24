<?php

namespace Dolphin\Certificato\Cron;

use \Psr\Log\LoggerInterface;

class Insuranceorder {
    protected $logger;
    protected $orderRepository;
    protected $searchCriteriaBuilder;


    public function __construct(
        LoggerInterface $logger,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute() {
        $date = (new \DateTime())->modify('-14 day');

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                'status',
                'complete',
                'eq'
            )->addFilter(
                'created_at',
                $date->format('Y-m-d'),
                'gt'
            )->create();

        $orders = $this->orderRepository->getList($searchCriteria);
        foreach ($orders->getItems() as $order) {
            //Your Code Here
            $this->logger->info("Order# {$order->getIncrementId()} - Creation Date: {$order->getCreatedAt()}");
        }
    }

}