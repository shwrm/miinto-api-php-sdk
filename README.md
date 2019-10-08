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

$client = new Shwrm\Miinto\Client\AuthenticatedClient(
    new \GuzzleHttp\Client(),
    $authData,
    'url-to-secured-api'
); 
```
### 3. PermaChannelClient
```php   
$mcc = new Shwrm\Miinto\ValueObject\MiintoCommunicationChannel(
    'tocken',
    'chennel-id'
);  

$client = new Shwrm\Miinto\Client\PermaChannelClient(
    new \GuzzleHttp\Client(),
    $mcc,
    'url-to-secured-api'
); 
```
## Repository
```php
$orderRepository = new Shwrm\Miinto\Repository\OrderRepository(
    $authenticatedClient
);

$orderRepository->getOrder(123, Shwrm\Miinto\Repository\OrderRepository::PL);
```
