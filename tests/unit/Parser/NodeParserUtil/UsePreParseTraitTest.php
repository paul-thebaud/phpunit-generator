<?php

namespace UnitTests\PhpUnitGen\Parser\NodeParserUtil;

use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\TestCase;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\ClassNodeParser;
use PhpUnitGen\Parser\NodeParser\FunctionNodeParser;
use PhpUnitGen\Parser\NodeParser\GroupUseNodeParser;
use PhpUnitGen\Parser\NodeParser\InterfaceNodeParser;
use PhpUnitGen\Parser\NodeParser\NamespaceNodeParser;
use PhpUnitGen\Parser\NodeParser\PhpFileNodeParser;
use PhpUnitGen\Parser\NodeParser\TraitNodeParser;
use PhpUnitGen\Parser\NodeParser\UseNodeParser;

/**
 * Class UsePreParseTraitTest.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 *
 * @covers     \PhpUnitGen\Parser\NodeParserUtil\UsePreParseTrait
 */
class UsePreParseTraitTest extends TestCase
{
    /**
     * @covers \PhpUnitGen\Parser\NodeParserUtil\UsePreParseTrait::preParseUses()
     */
    public function testPreParseUses(): void
    {
        $useNodeParser      = $this->createMock(UseNodeParser::class);
        $groupUseNodeParser = $this->createMock(GroupUseNodeParser::class);

        $usePreParseTrait = new PhpFileNodeParser(
            $this->createMock(NamespaceNodeParser::class),
            $useNodeParser,
            $groupUseNodeParser,
            $this->createMock(FunctionNodeParser::class),
            $this->createMock(ClassNodeParser::class),
            $this->createMock(TraitNodeParser::class),
            $this->createMock(InterfaceNodeParser::class)
        );

        $phpFile = $this->createMock(PhpFileModelInterface::class);

        $useNode1      = $this->createMock(Use_::class);
        $useNode2      = $this->createMock(Use_::class);
        $useNode3      = $this->createMock(Use_::class);
        $groupUseNode1 = $this->createMock(GroupUse::class);
        $groupUseNode2 = $this->createMock(GroupUse::class);

        $nodes = [$useNode1, $groupUseNode1, $useNode2, $useNode3, $groupUseNode2];

        $useNodeParser->expects($this->exactly(3))->method('invoke')
            ->withConsecutive([$useNode1], [$useNode2], [$useNode3]);
        $groupUseNodeParser->expects($this->exactly(2))->method('invoke')
            ->withConsecutive([$groupUseNode1], [$groupUseNode2]);

        $usePreParseTrait->preParseUses($nodes, $phpFile);
    }
}