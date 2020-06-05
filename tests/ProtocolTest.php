<?php
namespace tests;

use extas\interfaces\stages\IStageProtocolRunAfter;

use extas\components\extensions\ExtensionRepository;
use extas\components\http\TSnuffHttp;
use extas\components\plugins\Plugin;
use extas\components\plugins\PluginRepository;
use extas\components\protocols\Protocol;
use extas\components\protocols\ProtocolParameterHeaderDefault;
use extas\components\protocols\ProtocolRepository;
use extas\components\protocols\ProtocolRunner;
use extas\components\repositories\TSnuffRepository;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

/**
 * Class ProtocolTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class ProtocolTest extends TestCase
{
    use TSnuffRepository;
    use TSnuffHttp;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->registerSnuffRepos([
            'protocolRepository' => ProtocolRepository::class,
            'pluginRepository' => PluginRepository::class,
            'extensionRepository' => ExtensionRepository::class
        ]);
    }

    protected function tearDown(): void
    {
        $this->unregisterSnuffRepos();
    }

    public function testBasicMethods()
    {
        $protocol = new Protocol();
        $protocol->setAccept(['*']);
        $this->assertEquals(['*'], $protocol->getAccept());
        $protocol->setClass(ProtocolEmpty::class);
        $data = [];
        $protocol($data, $this->getPsrRequest());
        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok', $data['test']);
    }

    public function testRunner()
    {
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => ProtocolEmpty::class,
            Plugin::FIELD__STAGE => IStageProtocolRunAfter::NAME
        ]));
        $this->createWithSnuffRepo('protocolRepository', new Protocol([
            Protocol::FIELD__NAME => 'test',
            Protocol::FIELD__CLASS => ProtocolEmpty::class,
            Protocol::FIELD__ACCEPT => ['*']
        ]));

        $data = [];
        ProtocolRunner::run($data, $this->getPsrRequest());

        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok again', $data['test']);
    }

    public function testParametersFromHeadersDefault()
    {
        /**
         * from header
         */
        $protocol = new ProtocolParameterHeaderDefault([
            ProtocolParameterHeaderDefault::FIELD__PROTOCOL_KEY => 'test'
        ]);
        $data = [];
        $protocol($data, $this->getPsrRequest());
        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok', $data['test']);

        /**
         * from parameters
         */
        $protocol = new ProtocolParameterHeaderDefault([
            ProtocolParameterHeaderDefault::FIELD__PROTOCOL_KEY => 'test2'
        ]);
        $data = [];
        $protocol($data, $this->getPsrRequest());
        $this->assertArrayHasKey('test2', $data);
        $this->assertEquals('ok', $data['test2']);

        /**
         * default
         */
        $protocol = new ProtocolParameterHeaderDefault([
            ProtocolParameterHeaderDefault::FIELD__PROTOCOL_KEY => 'test3'
        ]);
        $data = [];
        $protocol($data, $this->getPsrRequest());
        $this->assertArrayHasKey('test3', $data);
        $this->assertEmpty($data['test3']);
    }
}
