<?php
namespace extas\components\protocols;

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
     */
    public function __invoke(array &$args = [])
    {
        array_merge($args, $_REQUEST);
    }
}
