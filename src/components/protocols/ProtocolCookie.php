<?php
namespace extas\components\protocols;

/**
 * Class ProtocolCookie
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolCookie extends Protocol
{
    public function __invoke(array &$args = [])
    {
        array_merge($args, $_COOKIE);
    }
}
