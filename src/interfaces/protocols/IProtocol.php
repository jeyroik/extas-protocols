<?php
namespace extas\interfaces\protocols;

use extas\interfaces\IHasClass;
use extas\interfaces\IHasDescription;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;
use Psr\Http\Message\RequestInterface;

/**
 * Interface IProtocol
 *
 * @package extas\interfaces\protocols
 * @author jeyroik@gmail.com
 */
interface IProtocol extends IItem, IHasName, IHasDescription, IHasClass
{
    const SUBJECT = 'extas.protocol';

    const FIELD__ACCEPT = 'accept';

    /**
     * @return array
     */
    public function getAccept(): array;

    /**
     * @param array $accept
     *
     * @return $this
     */
    public function setAccept(array $accept);

    /**
     * @param array $args
     * @param RequestInterface $request
     *
     * @return void
     */
    public function __invoke(array &$args = [], RequestInterface $request);
}
