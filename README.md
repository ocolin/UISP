# UISP

This UISP plugin is a lightweight PHP HTTP client specifically for Ubiquiti's UISP server. 

It is designed so you don't have to worry about setting up or paying attention to any of the HTTP mechanism. All you need is to provide the endpoint, the method, and the data. 

---

## TOC

- [Requirements](#Requirements)
- [Installation](#Installation)
- [Configuration](#Configuration)
  - [Environment Variable Configuration](#Environment-Variable-Configuration)
  - [Instantiation Configuration](#Instantiation-Configuration)
- [Usage](#Usage)
  - [Concepts](#Concepts)
  - [Output](#Output)
  - [Instantiation](#Instantiation)
- [Method Calls](#Method-Calls)
  - [GET](#GET)
  - [POST](#POST)
  - [PUT](#PUT)
  - [PATCH](#PATCH)
  - [DELETE](#DELETE)
  - [REQUEST](#REQUEST)
  - [DATA](#DATA)
- [Fluent Interface](#Fluent-Interface)

## Requirements

- PHP >= 8.3
- guzzlehttp/guzzle ^7.10
- ocolin/globaltypes ^2.0

---

## Installation

```
composer require ocolin/uisp
```

---

## Configuration

### Environment Variable Configuration

This plugin was designed to use environment variables for ease of use. You can use .env.example as a template for the variable names:

|Variable| Description          | Example                              |
|--------|----------------------|--------------------------------------|
|UISP_API_TOKEN| Authentication token | 24a1a5eg-dfa9-5679-a51f-b9eg43c65862 |
|UISP_API_URL| Server URI| https://myserver.com/nms/api/v2.1/   |

### Instantiation Configuration

The Client constructor can also take an array of optional information including the server URI and authentication token. Here are the optional configuration settings that can be used:

|Name| Description                         | Default                |
|----|-------------------------------------|------------------------|
|base_uri| URI to API server| UISP_API_URL env var   |
|token| Authentication token for API server | UISP_API_TOKEN env var |
|timeout| HTTP timeout| 20 seconds             |
|connect_timeout| Timeout for connection| 20 seconds             |
|verify| Verify SSL connection| false                  |

---

## Usage

### Concepts

Here are the arguments you need when making an API call:

- endpoint - Copy/paste the end point from your API docs. Any variables in the endpoint will be replaced with variables of the same name in your parameters.
- method - You specify an HTTP methods. This is either done by choosing the method function name, or specifying the method name depending on which function call is used.
- params - These are the variables that are used in the HTTP body. Not used for GET or DELETE.
- query - Path and query parameters. Any property names that match variable tokens in your path will be removed and inserted into your path name. See GET example below.

Below you can find examples for each scenario.

### Output

Most methods will output an object with 4 properties:

|Property|Desdcription|Type|
|--------|------------|----|
|status|HTTP status numeric code|int|
|statusMessage|HTTP text status|string|
|headers|HTTP response headers|string[]|
|body|API response data|mixed|

The exception to this is the data() function which returns ONLY the HTTP body (See example below).

### Instantiation

#### Using Environment Variables

```php
// Manually creating for demonstration
$_ENV['UISP_API_TOKEN'] = 'myauthtoken';
$_ENV['UISP_API_URL']   = 'https://myserver.com/nms/api/v2.1/';

$client = new Ocolin\UISP\Client();
```

#### Setting Options parameters

```php
$options = [
    'base_uri' => 'https://myserver.com/nms/api/v2.1/',
    'token'    => 'myauthtoken',
    'timeout'  => 60
];

$client = new Ocolin\UISP\Client( options: $options );
```
---

## Method Calls

### GET

Get data.

```php
// {id} will be replaced with 6c2f7697-8c5c-43df-9873-d8819d76a2d2
$output = $client->get(
    endpoint: '/devices/{id}',
       query: [ 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2']
);
```

### POST

Create data.

```php
$output = $client->post(
    endpoint: '/sites',
      params: [ 'name' => 'My New Site' ]
);
```

### PUT

Update data.
```php
$output = $client->put(
    endpoint: '/sites/{id}',
      params: $site_object, // Object of a site from UISP
       query: [ 'isComposeRequest' => 'true', 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2' ]
);
```

### PATCH
Update data. 
```php
$output = $client->patch(
    endpoint: '/sites/{id}',
      params: $site_object, // Object of a site from UISP
       query: [ 'isComposeRequest' => 'true', 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2'  ]
);
```

### DELETE
Delete data.
```php
$output = $client->delete(
    endpoint: '/devices/{id}',
      query: [ 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2']
);
```

### REQUEST

Request is a more generic function where you specify the method rather than calling the specific method function.

```php
$output = $client->request(
    endpoint: '/devices/{id}',
       query: [ 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2'],
      method: 'GET'
);
```

### DATA

Data is much like the request() function, but it only returns the data. This is for situations where you don't need any of the other data and can assume problems based solely on the response body.

```php
// Output is mixed data type
$output = $client->data(
    endpoint: '/devices/{id}',
       query: [ 'id' => '6c2f7697-8c5c-43df-9873-d8819d76a2d2'],
      method: 'GET'
);
```
---

## Fluent Interface

Another means of making API calls is using the Fluent/Chaining interface. Instead of providing an endpoint string, you can build an endpoint with functions.

The last function of your call will be the HTTP method which will take the same arguments as running it directly, only without the endpoint string.

Any endpoint segments using dashes can be specified using camel case.

### Example

```php
$output = $client->airlink()->proxy()->airlink-be()->get();
```

Variable path parameters can be provided using the param() function. Notice the camel case to replicate "mac-table-refresh".
```php
$output = $client->deviced->param(123)->macTableRefresh()->get();
```

