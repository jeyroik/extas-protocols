<?php
namespace extas\components\protocols;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class ProtocolSession
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolSession extends Protocol
{
    /**
     * @param array $args
     */
    public function __invoke(array &$args = [])
    {
        $session = new Session();
        $data = $session->all();

        array_merge($args, $data);
    }
}
