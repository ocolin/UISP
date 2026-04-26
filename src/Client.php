<?php

declare( strict_types = 1 );

namespace Ocolin\UISP;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private HTTP $http;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param HTTP|null $http
     * @param array<string, string|int|float|bool> $options
     * @method PathBuilder __call(string $name, array $args)
     */
    public function __construct( ?HTTP $http = null, array $options = [] )
    {
        $this->http = $http ?? new HTTP( options: $options );
    }



/* GET REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, mixed>|object $params Query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function get(
               string $endpoint,
         array|object $params = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->get( endpoint: $endpoint, params: $params )
        );
    }



/* DELETE REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, mixed>|object $params Query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function delete(
              string $endpoint,
        array|object $params = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->delete( endpoint: $endpoint, params: $params )
        );
    }



/* POST REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, mixed>|object $params Body parameters.
     * @param array<string, mixed>|object $query Query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function post(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->post( endpoint: $endpoint, params: $params, query: $query )
        );
    }



/* PUT REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, mixed>|object $params Body parameters.
     * @param array<string, mixed>|object $query Query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function put(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->put( endpoint: $endpoint, params: $params, query: $query )
        );
    }



/* PATCH REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, mixed>|object $params Body parameters.
     * @param array<string, mixed>|object $query Query parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function patch(
              string $endpoint,
        array|object $params = [],
        array|object $query = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->patch( endpoint: $endpoint, params: $params, query: $query )
        );
    }



/* REQUEST API CALL
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param string $method HTTP method.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function request(
              string $endpoint,
              string $method = 'GET',
        array|object $params = [],
        array|object $query = [],
    ) : Response
    {
        return self::formatResponse(
            $this->http->send(
                  path: $endpoint,
                method: $method,
                params: $params,
                 query: $query
            )
        );
    }



/* REQUEST DATA ONLY
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param string $method HTTP method.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return mixed Body of HTTP response.
     * @throws GuzzleException
     */
    public function data(
              string $endpoint,
              string $method = 'GET',
        array|object $params = [],
        array|object $query = [],
    ) : mixed
    {
        return self::formatResponse(
            $this->http->send(
                  path: $endpoint,
                method: $method,
                params: $params,
                 query: $query
            )
        )->body;
    }



/* FORMAT HTTP RESPONSE
----------------------------------------------------------------------------- */

    /**
     * Format Guzzle Response object into a more basic HTTP response object.
     *
     * @param ResponseInterface $response Guzzle response object.
     * @return Response Formatted response object.
     */
    private static function formatResponse( ResponseInterface $response ): Response
    {
        return new Response(
                   status: $response->getStatusCode(),
            statusMessage: $response->getReasonPhrase(),
                  headers: $response->getHeaders(),
                     body: json_decode( json: $response->getBody()->getContents())
        );
    }


/*
----------------------------------------------------------------------------- */

    /**
     * @param string $method
     * @param array<mixed> $args
     * @return PathBuilder
     */
    public function __call( string $method, array $args = [] ): PathBuilder
    {
        return ( new PathBuilder( client: $this ))->__call( $method, $args );
    }

}