<?php

namespace Ocolin\UISP\Tests;

use PHPUnit\Framework\TestCase;

use Ocolin\UISP\Client;

class TestSites extends TestCase
{
    public static Client $uisp;

    public static object $site;

    public static string $id;

/* TEST POST
----------------------------------------------------------------------------- */

    public function testCreateSite() : void
    {
        $output = self::$uisp->call(
              path: '/sites',
            method: 'POST',
              body: [
                'name' => 'PHPUnit Test Site',
            ]
        );
        self::$id = $output->id;
        self::$site = $output;
        $this->assertIsObject( actual: $output );
        $this->assertObjectHasProperty( propertyName: 'id', object:  $output );
        $this->assertEquals(
            expected: 'PHPUnit Test Site', actual: $output->identification->name
        );
        //print_r( $output );
    }


/* TEST PUT
----------------------------------------------------------------------------- */

    public function testUpdateSite() : void
    {
        self::$site->identification->name = 'PHPUnit Update Site';
        $output = self::$uisp->call(
               path: '/sites/{id}',
             method: 'PUT',
              query: [ 'id' => self::$id ],
               body: self::$site
        );
        $this->assertIsObject( actual: $output );
        $this->assertObjectHasProperty( propertyName: 'id', object:  $output );
        $this->assertEquals(
            expected: 'PHPUnit Update Site', actual: $output->identification->name
        );
        //print_r( $output );
    }


/* TEST GET
----------------------------------------------------------------------------- */

    public function testGetSite() : void
    {
        $output = self::$uisp->call(
            path: '/sites/{id}',
            query: [ 'id' => self::$id ],
        );
        $this->assertIsObject( actual: $output );
        $this->assertObjectHasProperty( propertyName: 'id', object:  $output );
        $this->assertEquals(
            expected: 'PHPUnit Update Site', actual: $output->identification->name
        );
        //print_r( $output );
    }


/* TEST DELETE
----------------------------------------------------------------------------- */

    public function testDeleteSite() : void
    {
        $output = self::$uisp->call(
              path: '/sites/{id}',
            method: 'DELETE',
            query: [ 'id' => self::$id ]
        );
        $this->assertIsObject( actual: $output );
        $this->assertObjectHasProperty( propertyName: 'result', object:  $output );
        $this->assertEquals( expected: 1, actual: $output->result );
        //print_r( $output );
    }


/* TEST SETUP
----------------------------------------------------------------------------- */

    public static function setUpBeforeClass() : void
    {
        self::$uisp = new Client();
    }
}