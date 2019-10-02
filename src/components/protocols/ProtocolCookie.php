<?php
namespace extas\components\protocols;

use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolCookie
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolCookie extends Protocol
{
    public function __invoke(array &$args = [], RequestInterface $request)
    {
        $args = array_merge($args, $_COOKIE);
    }
}
