<?php

namespace UnitTests\PhpUnitGen\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Exception\ParseException;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Parser\NodeParser\PhpFileNodeParser;
use PhpUnitGen\Parser\PhpParser;

/**
 * Class PhpParserTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\PhpParser
 */
class PhpParserTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\PhpParser::invoke()
     */
    public function testWithAndWithoutException(): void
    {
        $parser            = $this->createMock(Parser::class);
        $phpFileNodeParser = $this->createMock(PhpFileNodeParser::class);
        $nodes             = [$this->createMock(Node\Stmt\Use_::class), $this->createMock(Node\Stmt\Class_::class)];

        $phpParser = new PhpParser($parser, $phpFileNodeParser);

        $parser->expects($this->exactly(2))->method('parse')
            ->withConsecutive(['<?php some php code'], ['<?php invalid php code'])
            ->willReturnOnConsecutiveCalls(
                $nodes,
                $this->throwException(new Error('Invalid php code ...'))
            );

        $phpFileNodeParser->expects($this->once())->method('preParseUses')
            ->with($nodes, $this->isInstanceOf(PhpFileModel::class));
        $phpFileNodeParser->expects($this->once())->method('parseSubNodes')
            ->with($nodes, $this->isInstanceOf(PhpFileModel::class));

        $this->assertInstanceOf(PhpFileModel::class, $phpParser->invoke('<?php some php code'));

        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unable to parse given php code (maybe your code contains errors)');

        $phpParser->invoke('<?php invalid php code');
    }
}
