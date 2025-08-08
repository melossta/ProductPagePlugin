<?php declare(strict_types=1);

namespace SwagExamplePlugin\Service;

use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Checkout\Order\OrderDeliveryDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\StateMachine\StateMachineRegistry;
use Shopware\Core\System\StateMachine\Transition;

class OrderStateService
{
    private StateMachineRegistry $stateMachineRegistry;
    private EntityRepositoryInterface $orderRepository;

    public function __construct(
        StateMachineRegistry $stateMachineRegistry,
        EntityRepositoryInterface $orderRepository
    ) {
        $this->stateMachineRegistry = $stateMachineRegistry;
        $this->orderRepository = $orderRepository;
    }

    // Change order state to "in_progress"
    public function setOrderToInProgress(string $orderId, Context $context): void
    {
        $this->stateMachineRegistry->transition(new Transition(
            OrderDefinition::ENTITY_NAME,
            $orderId,
            'process',
            'stateId'
        ), $context);
    }

    // Change first delivery state to "shipped"
    public function shipFirstDelivery(string $orderId, Context $context): void
    {
        $criteria = new Criteria([$orderId]);
        $criteria->addAssociation('deliveries');

        $order = $this->orderRepository->search($criteria, $context)->first();

        if (!$order) {
            throw new \RuntimeException('Order not found');
        }

        $delivery = $order->getDeliveries()->first();

        if (!$delivery) {
            throw new \RuntimeException('No delivery found');
        }

        $this->stateMachineRegistry->transition(new Transition(
            OrderDeliveryDefinition::ENTITY_NAME,
            $delivery->getId(),
            'ship',
            'stateId'
        ), $context);
    }
}
