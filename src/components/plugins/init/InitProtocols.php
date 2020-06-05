<?php
namespace extas\components\plugins\init;

use extas\components\protocols\Protocol;
use extas\interfaces\protocols\IProtocolRepository;

/**
 * Class InitProtocols
 *
 * @package extas\components\plugins\init
 * @author jeyroik@gmail.com
 */
class InitProtocols extends InitSection
{
    protected string $selfItemClass = Protocol::class;
    protected string $selfRepositoryClass = IProtocolRepository::class;
    protected string $selfUID = Protocol::FIELD__NAME;
    protected string $selfSection = 'protocols';
    protected string $selfName = 'protocol';
}
