<?php

namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\Logger;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

    protected $logger;

    public function setUp()
    {
        $this->logger = new Logger('php://output', Logger::DEBUG);
    }

    public function tearDown()
    {
        $this->logger = null;
    }

  //expectOutputString

  /**
   * @dataProvider provideMessages
   */
    public function testMessageLevels($message)
    {

        $template = '['. date('Y-m-d H:i:s') .'] %s: ' . $message . "\n";

        $expect = str_repeat($template, 5);

        $this->logger->info($message);
        $this->logger->notice($message);
        $this->logger->warning($message);
        $this->logger->error($message);
        $this->logger->debug($message);

        $this->expectOutputString(sprintf($expect, 'INFO', 'NOTICE', 'WARNING', 'ERROR', 'DEBUG'));
    }

    public function provideMessages()
    {
        return array(
        array('Mensagem 1'),
        array('Teste de mensagem'),
        array(123456789)
        );
    }

    public function testLogWithWrongLevel()
    {

        $logger = new Logger('php://output', Logger::INFO);

        $this->assertFalse($logger->verbose('test', Logger::DEBUG));
    }

  /**
   * @expectedException \Exception
   * @expectedExceptionMessage Não foi possível abrir o arquivo para escrita
   */
    public function testErrorOnNonExistingFile()
    {
        $logger = new Logger('/path/to/invalid/file.txt', Logger::INFO);
    }
}
