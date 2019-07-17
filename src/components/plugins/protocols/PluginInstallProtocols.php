<?php
namespace extas\components\plugins\protocols;

use extas\components\plugins\PluginInstallDefault;
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
    protected $selfItemClass = Protocol::class;
    protected $selfRepositoryClass = IProtocolRepository::class;
    protected $selfUID = Protocol::FIELD__NAME;
    protected $selfSection = 'protocols';
    protected $selfName = 'protocol';
}
