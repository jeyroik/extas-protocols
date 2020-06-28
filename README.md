![tests](https://github.com/jeyroik/extas-protocols/workflows/PHP%20Composer/badge.svg?branch=master&event=push)
![codecov.io](https://codecov.io/gh/jeyroik/extas-protocols/coverage.svg?branch=master)
<a href="https://github.com/phpstan/phpstan"><img src="https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat" alt="PHPStan Enabled"></a> 
<a href="https://codeclimate.com/github/jeyroik/extas-protocols/maintainability"><img src="https://api.codeclimate.com/v1/badges/a2eaabdf60b4b987179a/maintainability" /></a>
[![Latest Stable Version](https://poser.pugx.org/jeyroik/extas-protocols/v)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Total Downloads](https://poser.pugx.org/jeyroik/extas-protocols/downloads)](//packagist.org/packages/jeyroik/extas-q-crawlers)
[![Dependents](https://poser.pugx.org/jeyroik/extas-protocols/dependents)](//packagist.org/packages/jeyroik/extas-q-crawlers)

# Описание

Пакет для поддержки протоколов для Extas'a.

# Использование

## Создаём протокол

```php
namespace my\extas\protocols;

use extas\components\protocols\Protocol;use Psr\Http\Message\RequestInterface;

class JsonProtocol extends Protocol
{
    public function __invoke(array &$args = [], RequestInterface $request = null) : void{
    {
        $json = file_get_contents('php://input');
        if ($json) {
            $jsonData = json_decode($json, true);
            $args = array_merge($args, $jsonData);
        }
    }
}
```

## Установка протокола

### В extas-совместимой конфигурации

```json
{
  "protocols": [
    {
      "name": "json",
      "title": "JSON protocol",
      "description": "JSON protocol, extracting from php://input",
      "accept": ["application/json", "json"],
      "class": "my\\extas\\protocols\\JsonProtocol"
    }
  ]
}
```

### Установка

`/vendor/bin/extas i`

## Применение

```php
use extas\interafces\protocols\IProtocol;
use extas\components\SystemContainer;

/**
 * @param Psr\Http\Message\RequestInterface $request
 * @param Psr\Http\Message\ResponseInterface $response
 * @param array $args
 */
function ($request, $response, $args) {
    /**
     * @var $protocols IProtocol[]
     */
    $protocols = $this->protocolRepository()->all([
        IProtocol::FIELD__ACCEPT => [$request->getHeader('ACCEPT'), '*']
    ]);
    
    foreach ($protocols as $protocol) {
        $protocol($args, $request);
    }
    
    print_r($args); // содержит данные из json
}
```