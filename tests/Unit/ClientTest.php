<?php

declare( strict_types = 1 );

namespace Ocolin\UISP\Tests\Unit;

use Ocolin\UISP\Client;
use Ocolin\UISP\HTTP;
use Ocolin\UISP\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;


class ClientTest extends TestCase
{
    public function testGetReturnsResponse() : void
    {
        // 1. Create a fake response to return
        $mockResponse = $this->createMockResponse( status: 200 );

        // 2. Create mock HTTP, tell it what to return when get() is called
        $mockHttp = $this->createMock( type: HTTP::class );
        $mockHttp->expects( $this->once() )
            ->method( 'get' )
            ->with(
                endpoint: '/sites/{id}',
                params: [ 'id' => 'abc' ]
            )
            ->willReturn( $mockResponse );

        // 3. Inject mock HTTP into Client
        $client = new Client( http: $mockHttp );

        // 4. Call the method and assert
        $result = $client->get( endpoint: '/sites/{id}', params: [ 'id' => 'abc' ] );

        $this->assertInstanceOf( Response::class, $result );
        //$this->assertEquals( 200, $result->status );
    }

    public function testPostReturnsResponse() : void
    {
        $mockResponse = $this->createMockResponse( status: 200 );
        $mockHttp = $this->createMock( type: HTTP::class );

        $mockHttp->expects( $this->once() )
            ->method( 'post' )
            ->with(
                endpoint: '/sites',
                  params: [ 'name' => 'abc' ],
                   query: [ 'isComposeRequest' => 'true' ]
            )
            ->willReturn( $mockResponse );

        $client = new Client( http: $mockHttp );

        $result = $client->post(
            endpoint: '/sites',
              params: [ 'name' => 'abc' ],
               query: [ 'isComposeRequest' => 'true' ]
        );
        $this->assertInstanceOf( Response::class, $result );
    }


    public function testPutReturnsResponse() : void
    {
        $mockResponse = $this->createMockResponse( status: 200 );
        $mockHttp = $this->createMock( type: HTTP::class );

        $mockHttp->expects( $this->once() )
            ->method( 'put' )
            ->with(
                endpoint: '/sites/{id}',
                params: [ 'id' => 'myid', 'name' => 'abc' ],
                query: [ 'isComposeRequest' => 'true' ]
            )
            ->willReturn( $mockResponse );

        $client = new Client( http: $mockHttp );

        $result = $client->put(
            endpoint: '/sites/{id}',
            params: [ 'id' => 'myid', 'name' => 'abc' ],
            query: [ 'isComposeRequest' => 'true' ]
        );
        $this->assertInstanceOf( Response::class, $result );
    }


    public function testPatchReturnsResponse() : void
    {
        $mockResponse = $this->createMockResponse( status: 200 );
        $mockHttp = $this->createMock( type: HTTP::class );

        $mockHttp->expects( $this->once() )
            ->method( 'patch' )
            ->with(
                endpoint: '/sites/{id}',
                params: [ 'id' => 'myid', 'name' => 'abc' ],
                query: [ 'isComposeRequest' => 'true' ]
            )
            ->willReturn( $mockResponse );

        $client = new Client( http: $mockHttp );

        $result = $client->patch(
            endpoint: '/sites/{id}',
            params: [ 'id' => 'myid', 'name' => 'abc' ],
            query: [ 'isComposeRequest' => 'true' ]
        );
        $this->assertInstanceOf( Response::class, $result );
    }


    public function testDeleteReturnsResponse() : void
    {
        // 1. Create a fake response to return
        $mockResponse = $this->createMockResponse( status: 200 );

        // 2. Create mock HTTP, tell it what to return when get() is called
        $mockHttp = $this->createMock( type: HTTP::class );
        $mockHttp->expects( $this->once() )
            ->method( 'delete' )
            ->with(
                endpoint: '/sites/{id}',
                params: [ 'id' => 'abc' ]
            )
            ->willReturn( $mockResponse );

        // 3. Inject mock HTTP into Client
        $client = new Client( http: $mockHttp );

        // 4. Call the method and assert
        $result = $client->delete( endpoint: '/sites/{id}', params: [ 'id' => 'abc' ] );

        $this->assertInstanceOf( Response::class, $result );
        //$this->assertEquals( 200, $result->status );
    }

    public function testDataResponse() : void
    {
        $mockResponse = $this->createMockResponse( contents: '{"name": "Test Site"}' );
        $mockHttp = $this->createMock( type: HTTP::class );
        $mockHttp->expects( $this->once() )
            ->method( 'send' )
            ->with(
                  path: '/sites/{id}',
                method: 'POST',
                params: [ 'id' => 'abc' ],
                 query: [ 'isComposeRequest' => 'true' ]
            )
            ->willReturn( $mockResponse );

        $client = new Client( http: $mockHttp );
        $result = $client->data(
            endpoint: '/sites/{id}',
              method: 'POST',
              params: [ 'id' => 'abc' ],
               query: [ 'isComposeRequest' => 'true' ]
        );

        $this->assertSame( 'Test Site', $result->name );
    }


    public function testRequestResponse() : void
    {
        $mockResponse = $this->createMockResponse( status: 200 );
        $mockHttp = $this->createMock( type: HTTP::class );
        $mockHttp->expects( $this->once() )
            ->method( 'send' )
            ->with(
                  path: '/sites/{id}',
                method: 'POST',
                params: [ 'id' => 'abc' ],
                 query: [ 'isComposeRequest' => 'true' ]
            )
            ->willReturn( $mockResponse );

        $client = new Client( http: $mockHttp );
        $result = $client->request(
            endpoint: '/sites/{id}',
              method: 'POST',
              params: [ 'id' => 'abc' ],
               query: [ 'isComposeRequest' => 'true' ]
        );

        $this->assertInstanceOf( Response::class, $result );
    }




    private function createMockStream( string $contents = '{}' ) : StreamInterface
    {
        $mockStream = $this->createStub( StreamInterface::class );
        $mockStream->method( 'getContents' )->willReturn( $contents );

        return $mockStream;
    }

    private function createMockResponse(
           int $status   = 200,
        string $reason   = 'OK',
        string $contents = '{}',
         array $headers  = [],
    ) : ResponseInterface
    {
        $mockResponse = $this->createStub( ResponseInterface::class );
        $mockResponse->method( 'getStatusCode' )->willReturn( $status );
        $mockResponse->method( 'getReasonPhrase' )->willReturn( $reason );
        $mockResponse->method( 'getHeaders' )->willReturn( $headers );
        $mockResponse->method( 'getBody' )->willReturn(
            $this->createMockStream( contents: $contents )
        );

        return $mockResponse;
    }
}