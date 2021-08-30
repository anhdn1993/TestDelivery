<?php

namespace Test\Delivery\Plugin\Checkout;

use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class Validation validates the agreement based on the payment method
 */
class Validation
{
    private $saveDelivery;
    /**
     * GuestValidation constructor.
     * @param SaveDelivery $saveDelivery
     */
    public function __construct(
        SaveDelivery $saveDelivery
    ) {
        $this->saveDelivery = $saveDelivery;
    }

    /**
     * Validates agreements before save payment information and  order placing.
     *
     * @param \Magento\Checkout\Api\PaymentInformationManagementInterface $subject
     * @param int $cartId
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface|null $billingAddress
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Api\PaymentInformationManagementInterface $subject,
        $cartId,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ) {
        $this->saveDelivery->process($cartId, $paymentMethod, false);
    }


}
