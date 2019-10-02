<?php
namespace extas\components\protocols;

use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolForm
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolForm extends Protocol
{
    const FIELD__ACTION = 'action';

    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request = null)
    {
        $args = array_merge($args, $_REQUEST);
    }
}
