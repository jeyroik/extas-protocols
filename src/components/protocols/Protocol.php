<?php
namespace extas\components\protocols;

use extas\interfaces\protocols\IProtocol;
use extas\components\Item;
use extas\components\THasClass;
use extas\components\THasDescription;
use extas\components\THasName;
use Psr\Http\Message\RequestInterface;

/**
 * Class Protocol
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class Protocol extends Item implements IProtocol
{
    use THasName;
    use THasDescription;
    use THasClass;

    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request)
    {
        $class = $this->getClass();
        $protocol = new $class();
        $protocol($args, $request);
    }

    /**
     * @return array
     */
    public function getAccept(): array
    {
        return $this->config[static::FIELD__ACCEPT] ?? [];
    }

    /**
     * @param array $accept
     *
     * @return $this
     */
    public function setAccept(array $accept)
    {
        $this->config[static::FIELD__ACCEPT] = $accept;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
