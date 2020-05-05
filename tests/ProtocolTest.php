<?php
namespace tests;

use extas\components\extensions\Extension;
use extas\components\extensions\ExtensionRepository;
use extas\components\extensions\ExtensionRepositoryGet;
use extas\components\plugins\Plugin;
use extas\components\plugins\PluginRepository;
use extas\components\protocols\Protocol;
use extas\components\protocols\ProtocolParameterHeaderDefault;
use extas\components\protocols\ProtocolRepository;
use extas\components\protocols\ProtocolRunner;
use extas\components\SystemContainer;
use extas\interfaces\extensions\IExtensionRepositoryGet;
use extas\interfaces\repositories\IRepository;
use extas\interfaces\stages\IStageProtocolRunAfter;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;
use Psr\Http\Message\RequestInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Stream;
use Slim\Http\Uri;

class ProtocolTest extends TestCase
{
    protected IRepository $pluginRepo;
    protected IRepository $protocolRepo;
    protected IRepository $extRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->protocolRepo = new ProtocolRepository();
        $this->extRepo = new ExtensionRepository();
        $this->pluginRepo = new class extends PluginRepository {
            public function reload()
            {
                parent::$stagesWithPlugins = [];
            }
        };
        SystemContainer::addItem('protocolRepository', ProtocolRepository::class);
    }

    protected function tearDown(): void
    {
        $this->pluginRepo->reload();
        $this->pluginRepo->delete([Plugin::FIELD__CLASS => [
            ProtocolEmpty::class
        ]]);
        $this->protocolRepo->delete([Protocol::FIELD__NAME => 'test']);
        $this->extRepo->delete([Extension::FIELD__CLASS => ExtensionRepositoryGet::class]);
    }

    public function testBasicMethods()
    {
        $protocol = new Protocol();
        $protocol->setAccept(['*']);
        $this->assertEquals(['*'], $protocol->getAccept());
        $protocol->setClass(ProtocolEmpty::class);
        $data = [];
        $protocol($data, $this->getRequest());
        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok', $data['test']);
    }

    public function testRunner()
    {
        $this->extRepo->create(new Extension([
            Extension::FIELD__CLASS => ExtensionRepositoryGet::class,
            Extension::FIELD__INTERFACE => IExtensionRepositoryGet::class,
            Extension::FIELD__SUBJECT => '*',
            Extension::FIELD__METHODS => ['protocolRepository']
        ]));
        $this->pluginRepo->create(new Plugin([
            Plugin::FIELD__CLASS => ProtocolEmpty::class,
            Plugin::FIELD__STAGE => IStageProtocolRunAfter::NAME
        ]));
        $this->protocolRepo->create(new Protocol([
            Protocol::FIELD__NAME => 'test',
            Protocol::FIELD__CLASS => ProtocolEmpty::class,
            Protocol::FIELD__ACCEPT => ['*']
        ]));

        $data = [];
        ProtocolRunner::run($data, $this->getRequest());

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
        $protocol($data, $this->getRequest());
        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok', $data['test']);

        /**
         * from parameters
         */
        $protocol = new ProtocolParameterHeaderDefault([
            ProtocolParameterHeaderDefault::FIELD__PROTOCOL_KEY => 'test2'
        ]);
        $data = [];
        $protocol($data, $this->getRequest());
        $this->assertArrayHasKey('test2', $data);
        $this->assertEquals('ok', $data['test2']);

        /**
         * default
         */
        $protocol = new ProtocolParameterHeaderDefault([
            ProtocolParameterHeaderDefault::FIELD__PROTOCOL_KEY => 'test3'
        ]);
        $data = [];
        $protocol($data, $this->getRequest());
        $this->assertArrayHasKey('test3', $data);
        $this->assertEmpty($data['test3']);
    }

    /**
     * @return RequestInterface
     */
    protected function getRequest(): RequestInterface
    {
        return new Request(
            'GET',
            new Uri('http', 'localhost', 80, '/', 'test2=ok'),
            new Headers([
                'Content-type' => 'text/html',
                ProtocolParameterHeaderDefault::HEADER__PREFIX . 'test' => 'is ok'
            ]),
            [],
            [],
            new Stream(fopen('php://input', 'r'))
        );
    }
}
