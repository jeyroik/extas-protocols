<?php
namespace extas\components\protocols;

use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolJson
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolJson extends Protocol
{
    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request)
    {
        $data = file_get_contents('php://input');
        if ($data) {
            try {
                $decoded = json_decode($data, true);
                is_array($decoded) && ($args = array_merge($args, $decoded));
            } catch (\Exception $e) {
            }
        }
    }
}
