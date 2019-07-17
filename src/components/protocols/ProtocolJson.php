<?php
namespace extas\components\protocols;

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
     */
    public function __invoke(array &$args = [])
    {
        $data = file_get_contents('php://input');
        if ($data) {
            try {
                $decoded = json_decode($data, true);
                array_merge($args, $decoded);
            } catch (\Exception $e) {
            }
        }
    }
}
