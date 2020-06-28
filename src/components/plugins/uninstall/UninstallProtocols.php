<?php
namespace extas\components\plugins\uninstall;

use extas\components\protocols\Protocol;

/**
 * Class UninstallProtocols
 *
 * @package extas\components\plugins\uninstall
 * @author jeyroik@gmail.com
 */
class UninstallProtocols extends UninstallSection
{
    protected string $selfItemClass = Protocol::class;
    protected string $selfRepositoryClass = 'protocolRepository';
    protected string $selfUID = Protocol::FIELD__NAME;
    protected string $selfSection = 'protocols';
    protected string $selfName = 'protocol';
}
