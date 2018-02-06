<?php

namespace UnitTests\PhpUnitGen\Util;

use PHPUnit\Framework\TestCase;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;

/**
 * Class JsonTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers \PhpUnitGen\Util\Json
 */
class JsonTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Util\Json::decode()
     */
    public function testNoExceptionThrown(): void
    {
        $json = new Json();

        $this->assertSame(['name' => 'John'], $json->decode('{"name": "John"}'));
    }

    /**
     * @covers \PhpUnitGen\Util\Json::decode()
     */
    public function testThrowExceptionNotString(): void
    {
        $json = new Json();

        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Json decode parameter must be a string');

        $json->decode(['an array']);
    }

    /**
     * @covers \PhpUnitGen\Util\Json::decode()
     */
    public function testThrowExceptionInvalidJson(): void
    {
        $json = new Json();

        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Json decode fail');

        $json->decode('invalid json');
    }
}
