<?php
namespace tests;

use extas\components\protocols\Protocol;
use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolEmpty
 *
 * @package tests
 * @author jeyroik@gmail.com
 */
class ProtocolEmpty extends Protocol
{
    /**
     * @param array $args
     * @param RequestInterface|null $request
     */
    public function __invoke(array &$args = [], RequestInterface $request = null): void
    {
        $args['test'] = isset($args['test']) ? 'is ok again' : 'is ok';
    }
}
