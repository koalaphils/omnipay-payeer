<?php

namespace Omnipay\Payeer\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\Rijndael;

class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('currency', 'amount', 'description', 'transactionId', 'merchantId', 'merchantKey');

        $arHash = [
            $this->getMerchantId(),
            $this->getTransactionId(),
            $this->getAmount(),
            $this->getCurrency(),
            base64_encode($this->getDescription()),
        ];

        if($this->getTransactionDetails() || $this->hasCustomURL()){
            $arParams = array_filter(array(
                'success_url' => $this->getReturnUrl(),
                'fail_url' => $this->getCancelUrl(),
                'status_url' => $this->getNotifyUrl(),
            ));

            if(!$this->parameters->get('merchantParameterKey')){
                throw new InvalidRequestException("The merchantParameterKey is required.");
            }
            $arParams['reference'] = [
                'details' => base64_encode(bzcompress(json_encode($this->getTransactionDetails())))
            ];
            $m_params = urlencode($this->encryptParameters($arParams));
            $arHash[] = $m_params;
        }

        $arHash[] = $this->getMerchantKey();
        $sign = strtoupper(hash('sha256', implode(":", $arHash)));

        $data['m_shop'] = $this->getMerchantId();
        $data['m_orderid'] = $this->getTransactionId();
        $data['m_amount'] = $this->getAmount();
        $data['m_curr'] = $this->getCurrency();
        $data['m_desc'] = base64_encode($this->getDescription());
        $data['m_params'] = $m_params ?? null;
        $data['m_cipher_method'] = ($m_params ?? false) ? 'AES-256-CBC' : null;
        $data['m_sign'] = $sign;

        return array_filter($data);
    }

    protected function hasCustomURL(){
        return $this->parameters->get('returnUrl', false) || $this->parameters->get('notifyUrl', false) | $this->parameters->get('cancelUrl', false);
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getMerchantEndpoint());
    }
}
