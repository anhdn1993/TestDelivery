<?php

namespace Test\Delivery\Plugin\Checkout;

/**
 * Guest checkout agreements validation.
 *
 * Plugin that checks if checkout agreement enabled and validates all agreements.
 * Current plugin is duplicate from Magento\CheckoutAgreements\Model\Checkout\Plugin\Validation due to different
 * interfaces of payment information and makes check before processing of payment information.
 */
class GuestValidation
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
     * @param \Magento\Checkout\Api\GuestPaymentInformationManagementInterface $subject
     * @param string $cartId
     * @param string $email
     * @param \Magento\Quote\Api\Data\PaymentInterface $paymentMethod
     * @param \Magento\Quote\Api\Data\AddressInterface|null $billingAddress
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Api\GuestPaymentInformationManagementInterface $subject,
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ) {
        $this->saveDelivery->process($cartId, $paymentMethod, true);
    }
}
