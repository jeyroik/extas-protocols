<?php
namespace tests;

use extas\components\repositories\TSnuffRepositoryDynamic;
use extas\components\THasMagicClass;
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
use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolTest
 *
 * @package tests
 * @author jeyroik <jeyroik@gmail.com>
 */
class ProtocolTest extends TestCase
{
    use TSnuffRepositoryDynamic;
    use TSnuffHttp;
    use THasMagicClass;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->createSnuffDynamicRepositories([
            ['protocols', 'name', Protocol::class]
        ]);
        $this->registerSnuffRepos([
            'pluginRepository' => PluginRepository::class
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
        $protocol($data, $this->getRequest());
        $this->assertArrayHasKey('test', $data);
        $this->assertEquals('is ok', $data['test']);
    }

    public function testRunner()
    {
        $this->createWithSnuffRepo('pluginRepository', new Plugin([
            Plugin::FIELD__CLASS => ProtocolEmpty::class,
            Plugin::FIELD__STAGE => IStageProtocolRunAfter::NAME
        ]));
        $this->getMagicClass('protocols')->create(new Protocol([
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
        return $this->getPsrRequest(
            '',
            [
                'Content-type' => 'text/html',
                ProtocolParameterHeaderDefault::HEADER__PREFIX . 'test' => 'is ok'
            ],
            'test2=ok'
        );
    }
}
