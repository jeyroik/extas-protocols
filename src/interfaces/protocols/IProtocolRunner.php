<?php
namespace extas\interfaces\protocols;

use extas\interfaces\IItem;
use Psr\Http\Message\RequestInterface;

/**
 * Interface IProtocolRunner
 *
 * @package extas\interfaces\protocols
 * @author jeyroik@gmail.com
 */
interface IProtocolRunner extends IItem
{
    const SUBJECT = 'extas.protocol.runner';

    const HEADER__ACCEPT = 'accept';
    const HEADER__ANY = '*';

    /**
     * @param array $args
     * @param RequestInterface $request
     *
     * @return void
     */
    public static function run(array &$args, RequestInterface $request);
}
