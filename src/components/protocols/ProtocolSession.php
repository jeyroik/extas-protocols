<?php
namespace extas\components\protocols;

use Psr\Http\Message\RequestInterface;
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
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request = null)
    {
        $session = new Session();
        $data = $session->all();

        $args = array_merge($args, $data);
    }
}
