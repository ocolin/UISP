# UISP

## Description

This is a basic REST client for a UISP server. Version 2 is not compatible with version 1.

This was designed with the mindset of copy/pasting from the API docs into your code. Copy/past the path from the docs, create simply arrays with parameters from the docs with your own values in them. No worrying about the HTTP or credentials work.

## Usage

### Environment variables

You can specify parameters in the constructor, but if any are left out, these environment variables will be used instead.

UISP_API_TOKEN - Authentication token for API

UISP_API_URL - URL of API server.

### Instantiate

Create an instance of the UISP object.

```
$uisp = new Ocolin\UISP\Client();
```

#### Parameters

$host: Name of the UISP host server. If null, will use .env field.

$token: Authentication token for server. If null, will use .env field.

$timeout: HTTP Timeout. Defaults to 20 seconds.

$verify: Verify SSL credentials. Defaults to off.

### Making a call

```php
$output = $uisp->call( 
     path: '/devices/airmaxes/{id}/config/wireless',
    query: [
        'id' => 'f700f200-f27f-442b-b086-c6ea128953b7',
        'withStations' => 'true'
    ] 
);
```

This returns ONLY the HTTP body from the API server.

#### Parameters

$path: REQUIRED - Endpoint call path, including named parameters.

$query: Array/object of parameters name/values to use for URI path or body.

$method: HTTP method. Defaults to GET.

$body: Array/object of parameters for PUT/POST/PATCH HTTP body.

### Making a full call.

This will return an object with 4 properties:

* status - HTTP status code (200, 400, etc)
* statusMessage - HTTP status message (OK, Bad Request, etc)
* headers - HTTP response headers
* body - HTTP response body

```php
$output = $uisp->full(
      path: '/sites',
    method: 'POST',
      body: [
        'name' => 'Test Site',
    ]   
); 
```