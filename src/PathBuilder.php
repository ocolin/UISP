<?php

declare( strict_types = 1 );

namespace Ocolin\UISP;

use GuzzleHttp\Exception\GuzzleException;

class PathBuilder
{
    /**
     * @var array<int, string|int>
     */
    private array $segments = [];


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Client $client UISP API client.
     */
    public function __construct( private readonly Client $client ) {}


/* MAGIC CALL
----------------------------------------------------------------------------- */

    /**
     * Magic method catches function name and uses it as an endpoint segment.
     *
     * @param string $name Function name.
     * @param array<mixed> $args Function arguments.
     * @return $this PathBuilder instance.
     */
    public function __call( string $name, array $args ): static
    {
        $segment = strtolower(
            (string)preg_replace(
                    pattern: '/[A-Z]/',
                replacement: '-$0',
                    subject: lcfirst( $name )
            )
        );
        $this->segments[] = $segment;

        return $this;
    }



/* PARAMETER FUNCTION
----------------------------------------------------------------------------- */

    /**
     * Capture a variable endpoint segment.
     *
     * @param string|int $value Path variable.
     * @return $this PathBuilder instance.
     */
    public function param( string|int $value ): static
    {
        $this->segments[] = $value;

        return $this;
    }



/* GET METHOD
----------------------------------------------------------------------------- */

    /**
     * Execute GET method.
     *
     * @param array<string, mixed>|object $query Query parameters.
     * @return Response API client response object.
     * @throws GuzzleException HTTP errors.
     */
    public function get( array|object $query = [] ) : Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->get( endpoint: $endpoint, query: $query );
    }



/* DELETE METHOD
----------------------------------------------------------------------------- */

    /**
     * Execute DELETE method.
     *
     * @param array<string, mixed>|object $query Delete query parameters.
     * @return Response API client response object.
     * @throws GuzzleException HTTP errors.
     */
    public function delete( array|object $query = [] ) : Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->delete( endpoint: $endpoint, query: $query );
    }



/* POST METHOD
----------------------------------------------------------------------------- */

    /**
     * Execute POST method.
     *
     * @param array<string, mixed>|object $params POST body parameters.
     * @param array<string, mixed>|object $query URI query parameters.
     * @return Response API client response object.
     * @throws GuzzleException HTTP errors.
     */
    public function post(
        array|object $params = [], array|object $query = [],
    ): Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->post(
            endpoint: $endpoint, params: $params, query: $query
        );
    }



/* PUT METHOD
----------------------------------------------------------------------------- */

    /**
     * Execute PUT function.
     *
     * @param array<string, mixed>|object $params POST body parameters.
     * @param array<string, mixed>|object $query URI query parameters.
     * @return Response API client response object.
     * @throws GuzzleException HTTP errors.
     */
    public function put(
        array|object $params = [], array|object $query = [],
    ): Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->put(
            endpoint: $endpoint, params: $params, query: $query
        );
    }



/* PATCH METHOD
----------------------------------------------------------------------------- */

    /**
     * Execute PATCH method.
     *
     * @param array<string, mixed>|object $params POST body parameters.
     * @param array<string, mixed>|object $query URI query parameters.
     * @return Response API client response object.
     * @throws GuzzleException HTTP errors.
     */
    public function patch(
        array|object $params = [], array|object $query = [],
    ): Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->patch(
            endpoint: $endpoint, params: $params, query: $query
        );
    }



/* REQUEST METHOD
----------------------------------------------------------------------------- */

    /**
     * Make and API call with a specified HTTP method
     *
     * @param string $method HTTP method.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return Response HTTP response object.
     * @throws GuzzleException
     */
    public function request(
              string $method = 'GET',
        array|object $params = [],
        array|object $query = [],
    ) : Response
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->request(
            endpoint: $endpoint, method: $method, params: $params, query: $query
        );
    }



/* REQUEST DATA ONLY
----------------------------------------------------------------------------- */

    /**
     * Make and API call with a specified HTTP method that only returns the
     * payload data.
     *
     * @param string $method HTTP method.
     * @param array<string, mixed>|object $params Query and/or body parameters.
     * @param array<string, mixed>|object $query Query only parameters.
     * @return mixed Body of HTTP response.
     * @throws GuzzleException
     */
    public function data(
              string $method = 'GET',
        array|object $params = [],
        array|object $query = [],
    ) : mixed
    {
        $endpoint = $this->buildEndpoint();

        return $this->client->data(
            endpoint: $endpoint, method: $method, params: $params, query: $query
        );
    }


/* BUILD ENDPOINT FROM SEGMENTS
----------------------------------------------------------------------------- */

    /**
     * Convert segment array into an endpoint URI.
     *
     * @return string Endpoint URI.
     */
    private function buildEndpoint(): string
    {
        return '/' . implode( separator: '/', array: $this->segments );
    }
}