<?php
/**
 * @package   construct
 */


namespace Construct;


class Ecommerce
{
    public function subscriptionPaymentUrl($options)
    {
        if (empty($options['item'])) {
            throw new \Construct\Exception('"item" setting is missing in subscriptionPaymentUrl function');
        }
        $paymentUrl = ipJob('ipSubscriptionPaymentUrl', $options);
        return $paymentUrl;
    }

    public function subscriptionCancelUrl($options)
    {
        if (empty($options['item'])) {
            throw new \Construct\Exception('"item" setting is missing in subscriptionCancelUrl function');
        }
        $cancelUrl = ipJob('ipSubscriptionCancelUrl', $options);
        return $cancelUrl;
    }

    public function paymentUrl($options)
    {
        if (empty($options['id'])) {
            throw new \Construct\Exception('"id" setting is missing in paymentUrl function');
        }
        if (empty($options['price'])){
            throw new \Construct\Exception('"price" setting is missing in paymentUrl function');
        }
        if (empty($options['currency'])) {
            throw new \Construct\Exception('"currency" setting is missing in paymentUrl function');
        }
        $paymentUrl = ipJob('ipPaymentUrl', $options);
        return $paymentUrl;
    }

}
