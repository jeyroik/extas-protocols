<?php
namespace extas\components\plugins;

use extas\components\protocols\Protocol;
use extas\interfaces\protocols\IProtocolRepository;

/**
 * Class ProtocolPluginextasServiceInstall
 *
 * @package extas\components\plugins\protocols
 * @author jeyroik@gmail.com
 */
class PluginInstallProtocols extends PluginInstallDefault
{
    protected string $selfItemClass = Protocol::class;
    protected string $selfRepositoryClass = IProtocolRepository::class;
    protected string $selfUID = Protocol::FIELD__NAME;
    protected string $selfSection = 'protocols';
    protected string $selfName = 'protocol';
}
