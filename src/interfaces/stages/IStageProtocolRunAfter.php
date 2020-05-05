<?php
namespace extas\interfaces\stages;

use Psr\Http\Message\RequestInterface;

/**
 * Interface IStageProtocolRunAfter
 *
 * @package extas\interfaces\stages
 * @author jeyroik@gmail.com
 */
interface IStageProtocolRunAfter
{
    public const NAME = 'extas.protocol.run.after';

    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args, RequestInterface $request): void;
}
