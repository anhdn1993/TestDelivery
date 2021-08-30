<?php

namespace Test\Delivery\Api\Data;

/**
 * Interface ShippingDeliveryInterface
 * @package Test\Delivery\Api\Data
 */
interface ShippingDeliveryInterface
{

    const DELIVERY_ID = 'delivery_id';
    const QUOTE_ID = 'quote_id';
    const ORDER_ID = 'order_id';
    const DELIVERY_DATE = 'delivery_date';
    const DELIVERY_TIME = 'delivery_time';
    const DELIVERY_COMMENT = 'delivery_comment';

    /**
     * Get Get Id

     * @return int|null
     */
    public function getId();
    /**
     * Get Get Delivery Id

     * @return int|null
     */
    public function getDeliveryId();

    /**
     * Set  Delivery Id
     *
     * @param string $delivery_id
     * @return $this
     */
    public function setDeliveryId($delivery_id);

    /**
     * Get Get Quote Id

     * @return int|null
     */
    public function getQuoteId();

    /**
     * Set  Quote Id
     *
     * @param string $quote_id
     * @return $this
     */
    public function setQuoteId($quote_id);

    /**
     * Get Get Order Id

     * @return int|null
     */
    public function getOrderId();

    /**
     * Set Order Id
     *
     * @param string $order_id
     * @return $this
     */
    public function setOrderId($order_id);

    /**
     * Get Get Delivery Date

     * @return int|null
     */
    public function getDeliveryDate();

    /**
     * Set Delivery Date
     *
     * @param string $delivery_date
     * @return $this
     */
    public function setDeliveryDate($delivery_date);

    /**
     * Get Get Delivery Time

     * @return int|null
     */
    public function getDeliveryTime();

    /**
     * Set Delivery Time
     *
     * @param string $delivery_time
     * @return $this
     */
    public function setDeliveryTime($delivery_time);

    /**
     * Get Get Delivery Comment

     * @return int|null
     */
    public function getDeliveryComment();

    /**
     * Set Delivery Comment
     *
     * @param string $delivery_comment
     * @return $this
     */
    public function setDeliveryComment($delivery_comment);
}
