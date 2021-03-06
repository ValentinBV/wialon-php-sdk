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
    private $requestDataSuccess;

    protected function setUp(): void
    {
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
        
        $this->source = new Action($stubClient);
    }

    public function testExecuteSuccess()
    {
        $this->assertEquals(
            $this->source->execute('testSvc', ['result' => 'success']), 
            \json_decode($this->requestDataSuccess, true)
        );
    }

    public function testExecuteException()
    {
        $stubClient = $this->createMock(Client::class);
        $stubClient->method('request')
            ->will($this->throwException(new TransferException));
        $this->source = new Action($stubClient);

        try {
            $this->source->execute('testSvc', ['result' => 'success']);
        } catch (WialonRequestException $e) {
            $this->assertInstanceOf(WialonRequestException::class, $e);
        }
    }
}
