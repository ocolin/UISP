<?php

declare( strict_types = 1 );

namespace Ocolin\UISP\Tests\Unit;

use Ocolin\UISP\HTTP;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class httpTest extends TestCase
{

    public function testFormatPathQueryParams() : void
    {
        $output = self::invokeFormatPath(
            path: "/devices/{id}", query: [ 'id' => 'A', 'other' => 'B' ]
        );
        $this->assertEquals( "devices/A", $output['path'] );
        $this->assertArrayNotHasKey( 'id', $output['query'] );
        $this->assertArrayHasKey( 'other', $output['query'] );
    }

    public function testFormatPathLeadingSlash() : void
    {
        $output = self::invokeFormatPath(
            path: "devices/{id}", query: [ 'id' => 'A' ]
        );
        $this->assertEquals( expected: "devices/A", actual: $output['path'] );
    }

    public function testFormatPathNoParams() : void
    {
        $output = self::invokeFormatPath( path: "devices/{id}", query: [] );
        $this->assertEmpty( $output['query'] );
        $this->assertSame( 'devices/{id}', $output['path'] );
    }

    public function testFormatPathNoQueryParams() : void
    {
        $output = self::invokeFormatPath(
            path: "/devices/", query: [ 'id' => 'A', 'other' => 'B' ]
        );
        $this->assertArrayHasKey( 'id', $output['query'] );
        $this->assertArrayHasKey( 'other', $output['query'] );
    }

    private static function invokeFormatPath( string $path, array $query ) : array
    {
        $method = ReflectionMethod::createFromMethodName( method: HTTP::class . '::formatPath' );
        return $method->invoke( null, $path, $query );
    }
}