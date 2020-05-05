<?php
namespace extas\components\protocols;

use extas\interfaces\protocols\IProtocolRepository;
use extas\components\repositories\Repository;

/**
 * Class ProtocolRepository
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolRepository extends Repository implements IProtocolRepository
{
    protected string $itemClass = Protocol::class;
    protected string $pk = Protocol::FIELD__NAME;
    protected string $name = 'protocols';
    protected string $scope = 'extas';
}
