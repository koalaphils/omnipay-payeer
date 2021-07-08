<?php

namespace Omnipay\Payeer\Message;

use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;

abstract class AbstractRequest extends OmnipayRequest
{
    protected $liveMerchantEndpoint = 'https://payeer.com/merchant/';

    protected function getMerchantEndpoint()
    {
        return $this->liveMerchantEndpoint;
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

    public function getTransactionDetails()
    {
        return $this->getParameter('transaction');
    }

    public function setTransactionDetails($value)
    {
        $this->setParameter('transaction', $value);
    }

    public function getParameterEncryptionKey(){
        return $this->getParameter('merchantParameterKey');
    }

    public function setParameterEncryptionKey($value){
        $this->setParameter('merchantParameterKey', $value);
    }

    protected function encryptParameters($payload)
    {
        $key = hash('md5', $this->getParameterEncryptionKey() . $this->getTransactionId());
        return @base64_encode(openssl_encrypt(json_encode($payload), 'AES-256-CBC', $key, OPENSSL_RAW_DATA));
    }

    protected function decryptParameters(string $m_params){
        $decoded = base64_decode($m_params);
        $key = hash('md5', $this->getParameterEncryptionKey() . $this->getTransactionId());
        return @json_decode(openssl_decrypt($decoded, 'AES-256-CBC', $key, OPENSSL_RAW_DATA), true);
    }
}
