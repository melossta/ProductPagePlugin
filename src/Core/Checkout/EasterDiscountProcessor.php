<?php //declare(strict_types=1);
//
//namespace SwagExamplePlugin\Core\Checkout;
//use Shopware\Core\Checkout\Cart\Cart;
//use Shopware\Core\Checkout\Cart\CartBehavior;
//use Shopware\Core\Checkout\Cart\CartProcessorInterface;
//use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
//use Shopware\Core\Checkout\Cart\LineItem\LineItem;
//use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
//use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
//use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
//use Shopware\Core\Checkout\Cart\Rule\LineItemRule;
//use Shopware\Core\System\SalesChannel\SalesChannelContext;
//
//class EasterDiscountProcessor implements CartProcessorInterface
//{
//    private string $easterProductNumber = 'SW10002';
//    private PercentagePriceCalculator $calculator;
//    public function __construct(PercentagePriceCalculator $calculator)
//    {
//        $this->calculator = $calculator;
//    }
//    public function process(CartDataCollection $data, Cart $original, Cart $toCalculate, SalesChannelContext $context, CartBehavior $behavior): void
//    {
//        // Find Easter products in the cart
//        $easterProducts = $this->findExampleProducts($original);
//
//        if ($easterProducts->count() === 0) {
//            return; // No Easter product found — do nothing
//        }
//
//        // Create a discount line item
//        $discountLineItem = $this->createDiscount('easter_discount');
//
//        // Define a 20% discount on the Easter product(s)
//        $definition = new PercentagePriceDefinition(
//            -20,
//            new LineItemRule(LineItemRule::OPERATOR_EQ, $easterProducts->getKeys())
//        );
//
//        $discountLineItem->setPriceDefinition($definition);
//
//        // Calculate price (total discount amount)
//        $discountLineItem->setPrice(
//            $this->calculator->calculate($definition->getPercentage(), $easterProducts->getPrices(), $context)
//        );
//
//
//        // Add discount to the cart
//        $toCalculate->add($discountLineItem);
//    }
//    private function findExampleProducts(Cart $cart): LineItemCollection
//    {
//        return $cart->getLineItems()->filter(function (LineItem $item) {
//            if ($item->getType() !== LineItem::PRODUCT_LINE_ITEM_TYPE) {
//                return false;
//            }
//
//            // Check product number payload
//            $productNumber = $item->getPayloadValue('productNumber');
//
//            return $productNumber === $this->easterProductNumber;
//        });
//    }
//
//    private function createDiscount(string $name): LineItem
//    {
//        $discountLineItem = new LineItem($name, 'easter_discount', null, 1);
//
//        $discountLineItem->setLabel('Easter Egg discount!');
//        $discountLineItem->setGood(false);
//        $discountLineItem->setStackable(false);
//        $discountLineItem->setRemovable(false);
//
//        return $discountLineItem;
//    }
//}
declare(strict_types=1);

namespace SwagExamplePlugin\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Rule\LineItemRule;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class EasterDiscountProcessor implements CartProcessorInterface
{
    private string $easterProductNumber = 'SW10002';
    private PercentagePriceCalculator $calculator;

    public function __construct(PercentagePriceCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function process(
        CartDataCollection  $data,
        Cart                $original,
        Cart                $toCalculate,
        SalesChannelContext $context,
        CartBehavior        $behavior
    ): void
    {
        $easterProducts = $this->findExampleProducts($original);

        if (!$easterProducts->count()) {
            return;
        }



        $definition = new PercentagePriceDefinition(
            -20,
            new LineItemRule(LineItemRule::OPERATOR_EQ, $easterProducts->getKeys())
        );
        $discountLineItem = $this->createDiscount('easter_discount');

        $discountLineItem->setPriceDefinition($definition);
        $discountLineItem->setPrice(
            $this->calculator->calculate($definition->getPercentage(), $easterProducts->getPrices(), $context)
        );

        $toCalculate->add($discountLineItem);
    }

    private function findExampleProducts(Cart $cart): LineItemCollection
    {
        return $cart->getLineItems()->filter(function (LineItem $item) {
            if ($item->getType() !== LineItem::PRODUCT_LINE_ITEM_TYPE) {
                return false;
            }

            return $item->getPayloadValue('productNumber') === $this->easterProductNumber;
        });
    }
        private function createDiscount(string $name): LineItem
    {
        $discountLineItem = new LineItem($name, 'easter_discount', null, 1);

        $discountLineItem->setLabel('Easter Egg discount!');
        $discountLineItem->setGood(false);
        $discountLineItem->setStackable(false);
        $discountLineItem->setRemovable(false);

        return $discountLineItem;
    }
}
