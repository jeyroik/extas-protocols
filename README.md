# extas-protocols

Пакет для поддержки протокол для Extas'a.

# Установка

`composer require jeyroik/extas-protocols`

# Использование

## Создаём протокол

```php
namespace my\extas\protocols;

use extas\components\protocols\Protocol;

class JsonProtocol extends Protocol
{
    public function __invoke(array &$args = [])
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
use extas\interafces\protocols\IProtocolRepository;
use extas\components\SystemContainer;

/**
 * @param Psr\Http\Message\RequestInterface $request
 * @param Psr\Http\Message\ResponseInterface $response
 * @param array $args
 */
function ($request, $response, $args) {
    /**
     * @var $repo IProtocolRepository
     * @var $protocols IProtocol[]
     */
    $repo = SystemContainer::getItem(IProtocolRepository::class);
    $protocols = $repo->all([
        IProtocol::FIELD__ACCEPT => [$response->getHeader('ACCEPT'), '*']
    ]);
    
    foreach ($protocols as $protocol) {
        $protocol($args);
    }
    
    print_r($args); // содержит данные из json
}
```