<?php

declare( strict_types = 1 );

namespace Ocolin\UISP\Tests\Integration;

use Ocolin\UISP\Client;
use PHPUnit\Framework\TestCase;

class SiteTest extends TestCase
{
    private static Client $client;

    private static ?string $siteId = null;

    private static object $site;



    public function testMultiQueryParams() : void
    {
        $output = self::$client->get(
            endpoint: '/devices',
            params: [ 'type' => ['airMax', 'airFiber'] ]
        );
        $this->assertEquals( 200, $output->status );
    }



    public function testCreateSite(): void
    {
        self::$site = self::$client->post(
            endpoint: '/sites',
            params: [ 'name' => 'PHPUnit Test Site' ]
        );
        self::$siteId = self::$site->body->id;
        $this->assertEquals( 200, self::$site->status );
        $this->assertNotNull( self::$site->body );
        $this->assertObjectHasProperty( 'id', self::$site->body );
        $this->assertNotEmpty( self::$site->body->id );
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f-]{36}$/',
            self::$site->body->id
        );
    }



    /** @depends testCreateSite */
    public function testPutSite(): void
    {
        self::$site->body->identification->name = 'PHPUnit UPDATE Site';
        $output = self::$client->put(
            endpoint: '/sites/{id}',
            params: self::$site->body
        );
        $this->assertEquals( 200, self::$site->status );
        $this->assertIsObject( $output );
        $this->assertObjectHasProperty( 'body', $output );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'identification', $output->body );
        $this->assertIsObject( $output->body->identification );
        $this->assertObjectHasProperty( 'name', $output->body->identification );
        $this->assertEquals( 'PHPUnit UPDATE Site', $output->body->identification->name );
    }



    /** @depends testCreateSite */
    public function testGetSite(): void
    {
        $output = self::$client->get(
            endpoint: '/sites/{id}',
            params: [ 'id' => self::$siteId ]
        );
        $this->assertEquals( 200, self::$site->status );
        $this->assertIsObject( $output );
        $this->assertObjectHasProperty( 'body', $output );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'id', $output->body );
        $this->assertEquals( self::$siteId, $output->body->id );
    }



    /** @depends testCreateSite */
    public function testDeleteSite(): void
    {
        $output = self::$client->delete(
            endpoint: '/sites/{id}',
              params: [ 'id' => self::$siteId ]
        );
        $this->assertEquals( 200, self::$site->status );
        $this->assertIsObject( $output );
        $this->assertObjectHasProperty( 'body', $output );
        $this->assertIsObject( $output->body );
        $this->assertObjectHasProperty( 'result', $output->body );
        $this->assertEquals( true, $output->body->result );
    }

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client();
    }

    public static function tearDownAfterClass(): void
    {
        if( self::$siteId !== null ) {
            self::$client->delete(
                endpoint: '/sites/{id}',
                params: [ 'id' => self::$siteId ]
            );
        }
        self::$siteId = null;
    }
}

