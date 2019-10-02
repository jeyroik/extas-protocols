<?php
namespace extas\components\protocols;

use extas\components\Item;
use extas\components\SystemContainer;
use extas\interfaces\protocols\IProtocol;
use extas\interfaces\protocols\IProtocolRepository;
use extas\interfaces\protocols\IProtocolRunner;
use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolRunner
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolRunner extends Item implements IProtocolRunner
{
    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public static function run(array &$args, RequestInterface $request)
    {
        /**
         * @var $protocolRepo IProtocolRepository
         * @var $protocols IProtocol[]
         */
        $protocolRepo = SystemContainer::getItem(IProtocolRepository::class);
        $protocols = $protocolRepo->all([
            IProtocol::FIELD__ACCEPT => array_merge(
                [static::HEADER__ANY],
                $request->getHeader(static::HEADER__ACCEPT)
            )
        ]);

        foreach ($protocols as $protocol) {
            $protocol($args, $request);
        }

        $static = new static();

        foreach ($static->getPluginsByStage(static::STAGE__PROTOCOL_RUN_AFTER) as $plugin) {
            $plugin($args, $request);
        }
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
