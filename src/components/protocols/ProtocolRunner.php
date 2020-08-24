<?php
namespace extas\components\protocols;

use extas\components\Item;
use extas\interfaces\protocols\IProtocol;
use extas\interfaces\protocols\IProtocolRunner;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageProtocolRunAfter;
use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolRunner
 *
 * @method IRepository protocols()
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
         * @var $protocols IProtocol[]
         */
        $static = new static();
        $protocols = $static->protocols()->all([
            IProtocol::FIELD__ACCEPT => array_merge(
                [static::HEADER__ANY],
                $request->getHeader(static::HEADER__ACCEPT)
            )
        ]);

        foreach ($protocols as $protocol) {
            $protocol($args, $request);
        }

        $static = new static();

        foreach ($static->getPluginsByStage(IStageProtocolRunAfter::NAME) as $plugin) {
            /**
             * @var IStageProtocolRunAfter $plugin
             */
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
