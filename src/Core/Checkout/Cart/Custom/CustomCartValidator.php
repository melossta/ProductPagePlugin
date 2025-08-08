<?php declare(strict_types=1);

namespace SwagExamplePlugin\Core\Checkout\Cart\Custom;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartValidatorInterface;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use SwagExamplePlugin\Core\Checkout\Cart\Custom\Error\CustomCartBlockedError;

class CustomCartValidator implements CartValidatorInterface
{
    public function validate(Cart $cart, ErrorCollection $errorCollection, SalesChannelContext $salesChannelContext): void
    {
        foreach ($cart->getLineItems()->getFlat() as $lineItem) {
            if (stripos($lineItem->getLabel(), 'iphone') !== false && !$lineItem->hasPayloadValue('warrantyAccepted')) {
                $errorCollection->add(new CustomCartBlockedError($lineItem->getId()));

                return;
            }
        }
    }
}