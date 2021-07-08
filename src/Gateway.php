<?php

namespace Omnipay\Payeer;

use Omnipay\Common\AbstractGateway;
use Omnipay\Payeer\Message\CompletePurchaseRequest;
use Omnipay\Payeer\Message\PurchaseRequest;

/**
 * Gateway Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Payeer';
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchantKey', $value);
    }

    public function getMerchantParameterKey()
    {
        return $this->getParameter('merchantParameterKey');
    }

    public function setMerchantParameterKey($value)
    {
        return $this->setParameter('merchantParameterKey', $value);
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'merchantKey' => '',
            'merchantParameterKey' => '',
        );
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(CompletePurchaseRequest::class, $parameters);
    }
}
