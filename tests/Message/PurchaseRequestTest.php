<?php

namespace Omnipay\Payeer\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{

    /**
     * @var PurchaseRequest
     */
    private $request;

    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setCurrency('USD');
        $this->request->setAmount('1.00');
        $this->request->setReturnUrl('https://url.com/return');
        $this->request->setCancelUrl('https://url.com/cancel');
        $this->request->setNotifyUrl('https://url.com/notify');
        $this->request->setTransactionId(1);
        $this->request->setDescription('Description');
        $this->request->setMerchantId('123');
        $this->request->setMerchantKey('merchantKey');
        $this->request->setParameterEncryptionKey('paramsKey');

    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $expectedData = [
            'm_shop' => '123',
            'm_orderid' => 1,
            'm_amount' => '1.00',
            'm_curr' => 'USD',
            'm_desc' => base64_encode('Description'),
            'm_sign' => '54BB2C968B9996206EE60D218F0BCCC84EB71EEF469B8F515CE599D7B231D30F',
            'm_params' => 'HuS9mcYla0C34yzaIyL8oI805cw6CmWrcEgAiBz3JdJF11m5rupJURFYMajy4zbGZHZj6CMCXtEJ7co6BNu0rUlXcKk5W%2FnoG1MCWMET0pVAz0N2qPXiQvy9EANjzEzcsYaalkbombvg6BzzwKYBd6viowYFnfqNP9XpCniwzysqx39mJDsOLuQ%2BqpCKKNt58we5epYdZmyte3kPp2vYqHSxjbqLrH4GpEgVnuFJgenrAmBjRvekcxbTEs0DLbiOhTxqinqhF9EMz59ngxVy7Q%3D%3D',
            'm_cipher_method' => 'AES-256-CBC'
        ];

        $this->assertEquals($expectedData, $data);
    }

    public function testSendSuccess()
    {
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://payeer.com/merchant/?m_shop=123&m_orderid=1&m_amount=1.00&m_curr=USD&m_desc=RGVzY3JpcHRpb24%3D&m_params=HuS9mcYla0C34yzaIyL8oI805cw6CmWrcEgAiBz3JdJF11m5rupJURFYMajy4zbGZHZj6CMCXtEJ7co6BNu0rUlXcKk5W%252FnoG1MCWMET0pVAz0N2qPXiQvy9EANjzEzcsYaalkbombvg6BzzwKYBd6viowYFnfqNP9XpCniwzysqx39mJDsOLuQ%252BqpCKKNt58we5epYdZmyte3kPp2vYqHSxjbqLrH4GpEgVnuFJgenrAmBjRvekcxbTEs0DLbiOhTxqinqhF9EMz59ngxVy7Q%253D%253D&m_cipher_method=AES-256-CBC&m_sign=54BB2C968B9996206EE60D218F0BCCC84EB71EEF469B8F515CE599D7B231D30F', $response->getRedirectUrl());
        $this->assertEquals('GET', $response->getRedirectMethod());
    }


}