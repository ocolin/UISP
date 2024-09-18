# UISP

## Description

This is a basic client for the UISP API. It uses the EasySwagger library, but contains the UISP swagger JSON file.

## Usage

### Environment variables

You can specify parameters in the constructor, but if any are left out, these environment variables will be used instead.

UISP_API_TOKEN - Authentication token for API

UISP_HOST - URL of server API

UISP_BASE_URI - URI path on API server

### Instantiate

Create an instance of the UISP object.

```
$uisp = new Ocolin\UISP\UISP();
```

#### Parameters

$host: Name of the UISP host server. If null, will use .env field.

$base_uri: URI path for API on server. If null, will use .env field.

$api_key: Authentication token for server. If null, will use .env field.

$api_file: Path to Swagger JSON file. If null, will use included file.

$local: If true, it will try to load .env file in root directory. 

### Making a call

```
$output = $uisp->path( 
    path: '/devices/airmaxes/{id}/config/wireless',
    data: [
        'id' => 'f700f200-f27f-442b-b086-c6ea128953b7',
        'withStations' => 'true'
    ] 
);
```

#### Parameters

$path: REQUIRED - Swagger call path, including named parameters.

$data: Array of parameters name/values to use for URI path or body.

$method: HTTP method. Defaults to GET.