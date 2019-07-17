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
    protected $itemClass = Protocol::class;
    protected $pk = Protocol::FIELD__NAME;
    protected $name = 'protocols';
    protected $scope = 'extas';
    protected $idAs = '';
}
