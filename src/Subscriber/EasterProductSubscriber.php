<?php declare(strict_types=1);

namespace SwagExamplePlugin\Subscriber;

use Psr\Log\LoggerInterface;
use Shopware\Core\Checkout\Cart\Event\AfterLineItemAddedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class EasterProductSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private string $easterProductNumber = 'SW10002';
    private LoggerInterface $logger;

    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterLineItemAddedEvent::class => 'onProductAdded',
        ];
    }

    public function onProductAdded(AfterLineItemAddedEvent $event): void
    {
        foreach ($event->getLineItems() as $lineItem) {
            $this->logger->notice('Product added to cart: ' . $lineItem->getPayloadValue('productNumber'));

            if (
                $lineItem->getType() === 'product' &&
                $lineItem->getPayloadValue('productNumber') === $this->easterProductNumber
            ) {
                $this->logger->notice('Easter product added! 🎉');

                $session = $this->requestStack->getSession();

                // Easter egg flash message in blue "info" box
                $session->getFlashBag()->add('easter_success', '🥚 Special Easter product found!');

            }
        }
    }
}
