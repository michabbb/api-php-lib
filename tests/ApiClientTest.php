<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH.

class ApiClientTest extends TestCase
{

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1005
     */
    public function testWrongProtocol()
    {
        $packet = $this->_client->getPacket('100.0.0');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testUnknownOperator()
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('unknown');
        $this->_client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testInvalidXmlRequest()
    {
        $this->_client->request('<packet><wrongly formatted xml</packet>');
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1001
     */
    public function testInvalidCredentials()
    {
        $host = getenv('REMOTE_HOST');
        $client = new PleskX\Api\Client($host);
        $client->setCredentials('bad-login', 'bad-password');
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 11003
     */
    public function testInvalidSecretKey()
    {
        $host = getenv('REMOTE_HOST');
        $client = new PleskX\Api\Client($host);
        $client->setSecretKey('bad-key');
        $packet = $this->_client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    public function testLatestMajorProtocol()
    {
        $packet = $this->_client->getPacket('1.6');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    public function testLatestMinorProtocol()
    {
        $packet = $this->_client->getPacket('1.6.5');
        $packet->addChild('server')->addChild('get_protos');
        $this->_client->request($packet);
    }

    public function testRequestShortSyntax()
    {
        $response = $this->_client->request('server.get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorPlainRequest()
    {
        $response = $this->_client->server()->request('get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
        $this->assertEquals(36, strlen($response->getValue('server_guid')));
    }

    public function testRequestArraySyntax()
    {
        $response = $this->_client->request([
            'server' => [
                'get' => [
                    'gen_info' => '',
                ],
            ],
        ]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorArraySyntax()
    {
        $response = $this->_client->server()->request(['get' => ['gen_info' => '']]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

}
