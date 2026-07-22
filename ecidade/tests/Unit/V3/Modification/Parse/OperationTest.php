<?php

namespace ECidade\V3\Modification\Parse;

use DOMDocument;
use DOMNode;
use PHPUnit_Framework_TestCase;

/**
 * Class OperationTest
 * @package ECidade\V3\Modification\Parse
 * @todo Implementar testes para offset e limit
 */
class OperationTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testPositionReplaceWithIgnore()
    {
        $operation = new Operation($this->getFixtureIgnoreGlobal());

        $text = "ẃẂẀẁýÝtestPositionReplaceWithIgnoreỲỳṕṔŕŔabcdeftestPositionReplaceWithIgnoreghijklmtestPosition";
        $text .= "ReplaceWithIgnoreẃẂẀẁýÝtestPositionReplaceWithIgnoreỲỳṕṔŕŔtestPositionReplaceWithIgnoreuvwxyztest";
        $text .= "PositionReplaceWithIgnore";

        $content = $operation->convertEncoding($text);
        $search = 'testPositionReplaceWithIgnore';
        $replace = 'test';

        $operation->search()->content = $search;
        $operation->add()->content = $replace;
        $operation->ignore()->type('');
        $operation->ignore()->content($search);
        $this->assertEquals($content, $operation->execute($content));

        $operation->search()->regex = true;
        $this->assertEquals($content, $operation->execute($content));
    }

    /**
     *
     */
    public function testPositionReplaceRegexLimit()
    {
        $operation = new Operation($this->getFixtureIgnoreGlobal());

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexLimitỲỳṕṔŕŔabcdeftestPositionReplaceRegexLimitghijklmtestPosition";
        $text .= "ReplaceRegexLimitẃẂẀẁýÝtestPositionReplaceRegexLimitỲỳṕṔŕŔtestPositionReplaceRegexLimituvwxyztest";
        $text .= "PositionReplaceRegexLimit";

        $content = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestỲỳṕṔŕŔabcdeftestPositionReplaceRegexLimitghijklmtestPositionReplaceRegexLimitẃẂẀẁýÝtest";
        $text .= "PositionReplaceRegexLimitỲỳṕṔŕŔtestPositionReplaceRegexLimituvwxyztestPositionReplaceRegexLimit";

        $expected1 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestỲỳṕṔŕŔabcdeftestghijklmtestPositionReplaceRegexLimitẃẂẀẁýÝtestPositionReplaceRegexLimit";
        $text .= "ỲỳṕṔŕŔtestPositionReplaceRegexLimituvwxyztestPositionReplaceRegexLimit";

        $expected2 = $operation->convertEncoding($text);

        $search = 'testPositionReplaceRegexLimit';
        $replace = 'test';

        $operation->search()->content = $search;
        $operation->add()->content = $replace;

        $operation->search()->regex = true;
        $operation->search()->limit = 1;
        $this->assertEquals($expected1, $operation->execute($content));

        $operation->search()->limit = 2;
        $this->assertEquals($expected2, $operation->execute($content));
    }

    /**
     *
     */
    public function testPositionReplaceRegexOffset()
    {

        $operation = new Operation($this->getFixtureIgnoreGlobal());

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetỲỳṕṔŕŔabcdeftestPositionReplaceRegexOffsetghijklmtestPosition";
        $text .= "ReplaceRegexOffsetẃẂẀẁýÝtestPositionReplaceRegexOffsetỲỳṕṔŕŔtestPositionReplaceRegexOffsetuvwxyztest";
        $text .= "PositionReplaceRegexOffset";

        $content = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestỲỳṕṔŕŔabcdeftestghijklmtestẃẂẀẁýÝtestỲỳṕṔŕŔtestuvwxyztest";

        $expected1 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetỲỳṕṔŕŔabcdeftestghijklmtestẃẂẀẁýÝtestỲỳṕṔŕŔtestuvwxyztest";

        $expected2 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetỲỳṕṔŕŔabcdeftestPositionReplaceRegexOffsetghijklmtestPosition";
        $text .= "ReplaceRegexOffsetẃẂẀẁýÝtestPositionReplaceRegexOffsetỲỳṕṔŕŔtestPositionReplaceRegexOffsetuvwxyztest";
        $text .= "PositionReplaceRegexOffset";

        $expected3 = $operation->convertEncoding($text);
        $search = 'testPositionReplaceRegexOffset';
        $replace = 'test';

        $operation->search()->content = $search;
        $operation->add()->content = $replace;

        $operation->search()->regex = true;
        $this->assertEquals($expected1, $operation->execute($content));

        $operation->search()->offset = 1;
        $this->assertEquals($expected2, $operation->execute($content));

        $operation->search()->offset = 999;
        $this->assertEquals($expected3, $operation->execute($content));
    }

    /**
     *
     */
    public function testPositionReplaceRegexOffsetLimit()
    {

        $operation = new Operation($this->getFixtureIgnoreGlobal());

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔabcdeftestPositionReplaceRegexOffsetLimitghijklmtest";
        $text .= "PositionReplaceRegexOffsetLimitẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔtestPositionReplace";
        $text .= "RegexOffsetLimituvwxyztestPositionReplaceRegexOffsetLimit";

        $content = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestỲỳṕṔŕŔabcdeftestghijklmtestẃẂẀẁýÝtestỲỳṕṔŕŔtestuvwxyztest";

        $expected1 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔabcdeftestghijklmtestPositionReplaceRegexOffsetLimit";
        $text .= "ẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔtestPositionReplaceRegexOffsetLimituvwxyztestPosition";
        $text .= "ReplaceRegexOffsetLimit";

        $expected2 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔabcdeftestPositionReplaceRegexOffsetLimitghijklmtest";
        $text .= "ẃẂẀẁýÝtestỲỳṕṔŕŔtestPositionReplaceRegexOffsetLimituvwxyztestPositionReplaceRegexOffsetLimit";

        $expected3 = $operation->convertEncoding($text);

        $text = "ẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔabcdeftestPositionReplaceRegexOffsetLimitghijklmtest";
        $text .= "PositionReplaceRegexOffsetLimitẃẂẀẁýÝtestPositionReplaceRegexOffsetLimitỲỳṕṔŕŔtestPositionReplace";
        $text .= "RegexOffsetLimituvwxyztestPositionReplaceRegexOffsetLimit";

        $expected4 = $operation->convertEncoding($text);
        $search = 'testPositionReplaceRegexOffsetLimit';
        $replace = 'test';

        $operation->search()->content = $search;
        $operation->add()->content = $replace;

        $operation->search()->regex = true;
        $this->assertEquals($expected1, $operation->execute($content));

        $operation->search()->limit = 1;
        $operation->search()->offset = 1;
        $this->assertEquals($expected2, $operation->execute($content));

        $operation->search()->limit = 2;
        $operation->search()->offset = 2;
        $this->assertEquals($expected3, $operation->execute($content));

        $operation->search()->limit = 999;
        $operation->search()->offset = 999;
        $this->assertEquals($expected4, $operation->execute($content));
    }

    /**
     *
     */
    public function testPositionManyReplaces()
    {
        $operation = new Operation($this->getFixtureVazio());

        $text = "ẃẂẀẁýÝtestPositionManyReplacesỲỳṕṔŕŔabcdeftestPositionManyReplacesghijklmtestPositionManyReplaces";
        $text .= "ẃẂẀẁýÝtestPositionManyReplacesỲỳṕṔŕŔtestPositionManyReplacesuvwxyztestPositionManyReplaces";
        $content = $operation->convertEncoding($text);
        $search = 'testPositionManyReplaces';
        $replace = 'test';

        $operation->search()->content = $search;
        $operation->add()->content = $replace;

        $this->assertEquals(str_replace($search, $replace, $content), $operation->execute($content));

        $operation->search()->regex = true;
        $operation->search()->content = $search;
        $this->assertEquals(str_replace($search, $replace, $content), $operation->execute($content));
    }

  /**
   * @dataProvider provideMultipleContents
   */
    public function testPositionReplace($content, $search)
    {
        $operation = new Operation($this->getFixtureVazio());
        $operation->search()->content = $search;
        $operation->add()->content = 'test';

        $this->assertEquals(str_replace($search, 'test', $content), $operation->execute($content));
    }

  /**
   * @dataProvider provideMultipleContents
   */
    public function testPositionBeforeAndAfter($content, $search)
    {
        $operation = new Operation($this->getFixturePositionBeforeAndAfter());
        $operation->search()->content = $search;

      // position before
        $operation->add()->position = 'before';
        $this->assertEquals(
            str_replace($search, $operation->add()->content . $search, $content),
            $operation->execute($content)
        );

      // position after
        $operation->add()->position = 'after';
        $this->assertEquals(
            str_replace($search, $search . $operation->add()->content, $content),
            $operation->execute($content)
        );
    }

  /**
   * @dataProvider provideMultipleContents
   */
    public function testPositionTopAndBottom($content)
    {
        $operation = new Operation($this->getFixturePositionTopAndBottom());

      // position top
        $operation->add()->position = 'top';
        $this->assertEquals($operation->add()->content . $content, $operation->execute($content));

      // position bottom
        $operation->add()->position = 'bottom';
        $this->assertEquals($content . $operation->add()->content, $operation->execute($content));
    }

  /**
   * @dataProvider provideMultipleContents
   */
    public function testIgnoreGlobal($content, $search)
    {
        $operation = new Operation($this->getFixtureIgnoreGlobal());

        $this->assertInstanceOf('\ECidade\V3\Modification\Parse\Operation\Ignore', $operation->ignore());

        $this->assertEquals($content, $operation->execute($content));
        $operation->ignore()->regex(true);
        $operation->ignore()->content($search);
        $this->assertEquals($content, $operation->execute($content));
        $this->assertNotEquals($content, $operation->execute('invalid'));
    }

    /**
     * @return array
     */
    public function provideMultipleContents()
    {
        return array(
        array('search', bin2hex('search')),
        array('áááàààsearchéééèèè', bin2hex('search')),
        array(mb_convert_encoding('áàíìésearchèóòúù', 'ISO-8859-1'), bin2hex('search')),
        array('áàíìésearchèóòúù', bin2hex('search')),
        array(mb_convert_encoding('çÇãÃẽẼsearchĩĨõÕũŨ', 'ISO-8859-1'), bin2hex('search')),
        array('çÇãÃẽẼsearchĩĨõÕũŨ', bin2hex('search')),
        array(mb_convert_encoding('ẃẂẀẁýÝsearchỲỳṕṔŕŔ', 'ISO-8859-1'), bin2hex('search')),
        array('ẃẂẀẁýÝsearchỲỳṕṔŕŔ', bin2hex('search'))
        );
    }

    /**
     *
     */
    public function testErrorCodes()
    {
        $operation = new Operation($this->getFixtureVazio());

        $this->assertEquals(Operation::ERROR_SKIP, $operation->createError('invalid'));
        $this->assertEquals(Operation::ERROR_SKIP, $operation->createError('skip'));
        $this->assertEquals(Operation::ERROR_ABORT, $operation->createError('abort'));
    }


    /**
     * @return array
     */
    public function provideStringToConvertEncoding()
    {
        return array(
        array('áàíìéèóòúù', mb_convert_encoding('áàíìéèóòúù', 'ISO-8859-1')),
        array(mb_convert_encoding('áàíìéèóòúù', 'ISO-8859-1'), mb_convert_encoding('áàíìéèóòúù', 'ISO-8859-1')),
        array('çÇãÃẽẼĩĨõÕũŨ', mb_convert_encoding('çÇãÃẽẼĩĨõÕũŨ', 'ISO-8859-1')),
        array(mb_convert_encoding('çÇãÃẽẼĩĨõÕũŨ', 'ISO-8859-1'), mb_convert_encoding('çÇãÃẽẼĩĨõÕũŨ', 'ISO-8859-1')),
        array('ẃẂẀẁýÝỲỳṕṔŕŔ', mb_convert_encoding('ẃẂẀẁýÝỲỳṕṔŕŔ', 'ISO-8859-1')),
        array(mb_convert_encoding('ẃẂẀẁýÝỲỳṕṔŕŔ', 'ISO-8859-1'), mb_convert_encoding('ẃẂẀẁýÝỲỳṕṔŕŔ', 'ISO-8859-1')),
        );
    }

    /**
     * @return string
     */
    public function getFixturesPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return DOMNode|null
     */
    private function getFixtureVazio()
    {
        return $this->loadOperationXml('operation_vazio.xml');
    }

    /**
     * @return DOMNode|null
     */
    private function getFixtureIgnoreGlobal()
    {
        return $this->loadOperationXml('operation_ignore_global.xml');
    }

    /**
     * @return DOMNode|null
     */
    private function getFixturePositionTopAndBottom()
    {
        return $this->loadOperationXml('operation_position_top_bottom.xml');
    }

    /**
     * @return DOMNode|null
     */
    private function getFixturePositionBeforeAndAfter()
    {
        return $this->loadOperationXml('operation_position_before_after.xml');
    }

    /**
     * @param $name
     * @return DOMNode|null
     */
    private function loadOperationXml($name)
    {
        $doc = new DOMDocument();
        $doc->load($this->getFixturesPath() . $name);
        return $doc->getElementsByTagName('operation')->item(0);
    }

    /**
     *
     */
    public function testComportamentoBasico()
    {
        $operation = new Operation($this->getFixtureVazio());

      // testa search
        $search = $operation->search();
        $expectedSearch = (object) array(
        'regex' => false,
        'flag' => '',
        'offset' => '',
        'limit' => '',
        'content' => ''
        );
        $this->assertEquals($expectedSearch, $search, "objeto search");

      // testa add
        $add = $operation->add();
        $expectedAdd = (object) array(
        'position' => '',
        'content' => ''
        );
        $this->assertEquals($expectedAdd, $add, "objeto add");

      //testa ignore
        $ignore = $operation->ignore();
        $this->assertFalse($ignore, "value ignore");

      //testa error
        $error = $operation->error();
        $this->assertEquals(Operation::ERROR_SKIP, $error, 'value error');

      // testa label
        $this->assertNull($operation->label());

      // code coverage whore
        $operation->search('');
        $operation->add('');
        $operation->ignore('');
        $operation->error('');
        $operation->label('');
    }
}
