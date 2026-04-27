<?php

declare( strict_types = 1 );

namespace Ocolin\UISP;

use GuzzleHttp\Psr7\Query;
use InvalidArgumentException;
use Ocolin\GlobalType\ENV;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client AS GuzzleClient;

class HTTP
{
    private GuzzleClient $client;

/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param array<string, string|int|float|bool> $options HTTP client options
     */
    public function __construct( array $options = [] )
    {
        $defaults = [
            'base_uri'        => ENV::getStringNull( name: 'UISP_API_URL' ),
            'token'           => ENV::getStringNull( name: 'UISP_API_TOKEN' ),
            'timeout'         => 20,
            'connect_timeout' => 20,
            'verify'          => false,
        ];
        $config = array_merge( $defaults, $options );

        if( empty( $config['base_uri'] )) {
            throw new InvalidArgumentException(
                message: 'Missing required configuration: UISP_API_URL'
            );
        }

        if( empty( $config['token'] )) {
            throw new InvalidArgumentException(
                message: 'Missing required configuration: UISP_API_TOKEN'
            );
        }

        $config['base_uri'] = rtrim(
            string: (string)$config['base_uri'], characters: '/'
        ) . '/';;

        $this->client = new GuzzleClient([
            'base_uri'        => $config['base_uri'],
            'timeout'         => $config['timeout'],
            'connect_timeout' => $config['connect_timeout'],
            'verify'          => $config['verify'],
            'http_errors'     => false,
            'headers'         => [
                'x-auth-token'  => $config['token'],
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json; charset=utf-8',
                'User-Agent'    => 'UISP Rest Client 3.0',
            ]
        ]);
    }



/* GET METHOD
----------------------------------------------------------------------------- */

    /**
     * Send HTTP GET request.
     *
     * @param string $endpoint API end point to call.
     * @param array<string, mixed>|object $query Query and/or body parameters.
     * @return ResponseInterface HTTP response object.
     * @throws GuzzleException
     */
    public function get( string $endpoint, array|object $query = [] ) : ResponseInterface
    {
        return $this->send( path: $endpoint, query: $query );
    }



/* DELETE METHOD
----------------------------------------------------------------------------- */

    /**
     * HTTP DELETE request.
     *
     * @param string $endpoint API end point to call.
     * @param array<string, mixed>|object $query Query and/or body parameters.
     * @return ResponseInterface HTTP response object.
     * @throws GuzzleException
     */
    public function delete( string $endpoint, array|object $query = [] ) : ResponseInterface
    {
        return $this->send( path: $endpoint, method: 'DELETE', query: $query );
    }



/* POST METHOD
----------------------------------------------------------------------------- */

    /**
     * HTTP POST request.
     *
     * @param string $endpoint API end point to call.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return ResponseInterface HTTP response object.
     * @throws GuzzleException
     */
    public function post(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : ResponseInterface
    {
        return $this->send(
            path: $endpoint, method: 'POST', params: $params, query: $query
        );
    }



/* PUT METHOD
----------------------------------------------------------------------------- */

    /**
     * HTTP PUT request.
     *
     * @param string $endpoint API end point to call.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return ResponseInterface HTTP response object.
     * @throws GuzzleException
     */
    public function put(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : ResponseInterface
    {
        return $this->send( path: $endpoint, method: 'PUT', params: $params, query: $query );
    }



/* PATCH METHOD
----------------------------------------------------------------------------- */

    /**
     * HTTP PATCH request.
     *
     * @param string $endpoint API end point to call.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return ResponseInterface HTTP response object.
     * @throws GuzzleException
     */
    public function patch(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : ResponseInterface
    {
        return $this->send(
            path: $endpoint, method: 'PATCH', params: $params, query: $query
        );
    }



/* SEND HTTP REQUEST
----------------------------------------------------------------------------- */

    /**
     * HTTP Request.
     *
     * @param string $path API URI path.
     * @param string $method HTTP method.
     * @param array<string, mixed>|object $params Body and query parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function send(
              string $path,
              string $method = 'GET',
        array|object $params = [],
        array|object $query = [],
    ) : ResponseInterface
    {
        if( is_object( $params )) { $params = (array)$params; }
        if( is_object( $query )) { $query = (array)$query; }
        [ 'path' => $path, 'query' => $query ] =
            self::formatPath( path: $path, query: $query );

        array_walk( $query, function( &$value ) {
            if( is_bool( $value )) {
                $value = $value ? 'true' : 'false';
            }
        });

        $options = [];

        if( !empty( $params )) { $options['json'] = $params; }
        if( !empty( $query )) { $options['query'] = Query::build( $query ); }

        return $this->client->request( method: $method, uri: $path, options: $options );
    }



/* FORMAT URI PATH
----------------------------------------------------------------------------- */

    /**
     * Format the API URI path and insert variables.
     *
     * @param string $path
     * @param array<string, mixed> $query
     * @return array{ path: string, query: array<string, mixed> }
     */
    private static function formatPath( string $path, array $query ) : array
    {
        $output = [ 'path'   => '' ];
        $path = ltrim( string: $path, characters: '/' );

        if( !str_contains( haystack: $path, needle: '{' )) {
            $output['path']  .= $path;
            $output['query'] = $query;
            return $output;
        }

        foreach( $query as $key => $value ) {
            if(
                str_contains( haystack: $path, needle: '{' . $key . '}' ) AND
                (
                    is_string( value: $value ) OR
                    is_int(    value: $value ) OR
                    is_float(  value: $value )
                )
            ) {
                $path = str_replace(
                     search: '{' . $key . '}',
                    replace: (string)$value,
                    subject: $path
                );
                unset( $query[$key] );
            }
        }

        $output['path']  .= $path;
        $output['query'] = $query;

        return $output;
    }
}