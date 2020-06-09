<?php
/*
 * This file is part of wialon-php-sdk.
 *
 * (c) Valentin Bondarenko <bvv1988@gmail.com>
 */
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Exception\TransferException;
use valentinbv\Wialon\Request\Action;
use valentinbv\Wialon\Exception\WialonRequestException;
use valentinbv\Wialon\Exception\ReadOnlyException;
use valentinbv\Wialon\Exception\InexistentPropertyException;

class ActionTest extends TestCase
{
    private $source;
    private $testSid;
    private $testHost;
    private $requestDataSuccess;

    protected function setUp(): void
    {
        $this->testHost = 'example.com';
        $this->testSid = 'testSid';
        $this->requestDataSuccess = json_encode(['result' => 'success']);

        //stubs
        $stubClient = $this->createMock(Client::class);
        $stubResultQueryBody = $this->createMock(MessageInterface::class);
        $stubResultQueryContents = $this->createMock(StreamInterface::class);

        $stubClient->method('request')
            ->willReturn($stubResultQueryBody);
        $stubResultQueryBody->method('getBody')
            ->willReturn($stubResultQueryContents);
        $stubResultQueryContents->method('getContents')
            ->willReturn($this->requestDataSuccess);
        
        $this->source = new Action($stubClient, $this->testHost);
    }

    public function testSetterSuccess()
    {
        $this->source->sid = $this->testSid;
        $this->assertEquals($this->source->sid, $this->testSid);
    }

    public function testSetterReadOnlyException()
    {
        try {
            $this->source->host = $this->testHost;
        } catch (ReadOnlyException $e) {
            $this->assertInstanceOf(ReadOnlyException::class, $e);
        }        
    }

    public function testSetterInexistentPropertyException()
    {
        try {
            $this->source->someVar = 'some value';
        } catch (InexistentPropertyException $e) {
            $this->assertInstanceOf(InexistentPropertyException::class, $e);
        }        
    }

    public function testGetterSuccess()
    {
        $this->source->sid = $this->testSid;
        $this->assertEquals($this->source->sid, $this->testSid);
    }

    public function testGetterInexistentPropertyException()
    {
        try {
            $someVar = $this->source->someVar;
        } catch (InexistentPropertyException $e) {
            $this->assertInstanceOf(InexistentPropertyException::class, $e);
        }
    }

    public function testRequestSuccess()
    {
        $this->assertEquals(
            $this->source->request('testSvc', ['result' => 'success']), 
            \json_decode($this->requestDataSuccess, true)
        );
    }

    public function testRequestException()
    {
        $stubClient = $this->createMock(Client::class);
        $stubClient->method('request')
            ->will($this->throwException(new TransferException));
        $this->source = new Action($stubClient, $this->testHost);

        try {
            $this->source->request('testSvc', ['result' => 'success']);
        } catch (WialonRequestException $e) {
            $this->assertInstanceOf(WialonRequestException::class, $e);
        }
    }

    public function testDecodeBodySuccess()
    {
        $this->assertEquals(
            $this->source->decodeBody($this->requestDataSuccess), 
            \json_decode($this->requestDataSuccess, true)
        );
    }

    public function testDecodeBodyError()
    {
        $this->assertEquals($this->source->decodeBody('error string'), []);
    }
}
