# Miinto Api SDK for PHP  
  
# Installation  
```  
composer require shwrm/miinto-sdk-php  
```  
# Getting Started  
## Client
###  1. Basic Client
This is a client that does not use authentication for queries
```php
$client = new Shwrm\Miinto\Client\BasicClient(
    new \GuzzleHttp\Client(),
    'url-to-api'
);
```
### 2. AuthenticatedClient
```php   
$authData = new Shwrm\Miinto\ValueObject\AuthData(
	'auth-api-url',
	'identifier',
	'secret',
);  

$client = new Shwrm\Miinto\Client\OrderClient(
    new \GuzzleHttp\Client(),
	$authData,
	'url-to-secured-api'
); 
```
## Repository
```php
$orderRepository = new Shwrm\Miinto\Repository\OrderRepository(
	$authenticatedClient
);

$orderRepository->getOrder(123, new Shwrm\Miinto\Filter\Order\ListFilter());
```
