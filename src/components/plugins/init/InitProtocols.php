<?php
namespace extas\components\plugins\init;

use extas\components\protocols\Protocol;

/**
 * Class InitProtocols
 *
 * @package extas\components\plugins\init
 * @author jeyroik@gmail.com
 */
class InitProtocols extends InitSection
{
    protected string $selfItemClass = Protocol::class;
    protected string $selfRepositoryClass = 'protocolRepository';
    protected string $selfUID = Protocol::FIELD__NAME;
    protected string $selfSection = 'protocols';
    protected string $selfName = 'protocol';
}
