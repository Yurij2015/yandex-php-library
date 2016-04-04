<?php

namespace Yandex\Tests\Metrica;

use Yandex\Metrica\Management\Models;
use Yandex\Metrica\Management\AccountsClient;
use Yandex\Tests\TestCase;
use Yandex\Tests\Metrica\Fixtures\Accounts;
use GuzzleHttp\Psr7\Response;

class AccountsClientTest extends TestCase
{

    public function testGetAccounts()
    {
        $fixtures = Accounts::$accountsFixtures;

        $mock = $this->getMock('Yandex\Metrica\Management\AccountsClient', ['sendGetRequest']);
        $mock->expects($this->any())
            ->method('sendGetRequest')
            ->will($this->returnValue($fixtures));

        $table = $mock->getAccounts();

        $this->assertEquals($fixtures["accounts"][0]["user_login"], $table->current()->getUserLogin());
        $this->assertEquals($fixtures["accounts"][0]["created_at"], $table->current()->getCreatedAt());
    }

    public function testUpdateAccounts()
    {
        $fixtures             = Accounts::$accountsFixtures;
        $token                = 'test';
        $response             = new Response(200, [], \GuzzleHttp\Psr7\stream_for(json_encode($fixtures)));
        $guzzleHttpClientMock = $this->getMock('GuzzleHttp\Client', ['request']);
        $guzzleHttpClientMock->expects($this->any())
            ->method('request')
            ->will($this->returnValue($response));
        /** @var AccountsClient $accountsClientMock */
        $accountsClientMock = $this->getMock('Yandex\Metrica\Management\AccountsClient', ['getClient'], [$token]);
        $accountsClientMock->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue($guzzleHttpClientMock));

        $accounts = new Models\Accounts();
        $accounts->fromArray($fixtures);
        $table = $accountsClientMock->updateAccounts($accounts);

        $this->assertEquals($fixtures["accounts"][0]["user_login"], $table->current()->getUserLogin());
        $this->assertEquals($fixtures["accounts"][0]["created_at"], $table->current()->getCreatedAt());
    }

    public function testDeleteAccounts()
    {
        $fixtures             = Accounts::$accountsFixtures;
        $token                = 'test';
        $response             = new Response(200, [], \GuzzleHttp\Psr7\stream_for(json_encode($fixtures)));
        $guzzleHttpClientMock = $this->getMock('GuzzleHttp\Client', ['request']);
        $guzzleHttpClientMock->expects($this->any())
            ->method('request')
            ->will($this->returnValue($response));
        /** @var AccountsClient $accountsClientMock */
        $accountsClientMock = $this->getMock('Yandex\Metrica\Management\AccountsClient', ['getClient'], [$token]);
        $accountsClientMock->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue($guzzleHttpClientMock));

        $accounts = new Models\Accounts();
        $accounts->fromArray($fixtures);
        $result = $accountsClientMock->deleteAccount('test');
        $this->assertArrayHasKey('accounts', $result);
    }
}
