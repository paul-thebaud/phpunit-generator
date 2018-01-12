<?php

namespace PhpUnitGen\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Parser;
use PhpUnitGen\Exception\ParsingException;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PhpFileModel;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParser\InterfaceNodeParser;
use PhpUnitGen\Parser\NodeParser\NamespaceNodeParser;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface;
use PhpUnitGen\Parser\NodeParser\UseNodeParser;
use PhpUnitGen\Parser\ParserInterface\PhpFileParserInterface;
use Respect\Validation\Validator;

/**
 * Class PhpFileParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class PhpFileParser implements PhpFileParserInterface
{
    /**
     * @var Parser $phpParser A parser to parse php code as a string.
     */
    private $phpParser;

    /**
     * @var NodeParserInterface[] $nodeParsers Mapping array between node class and node parser.
     */
    private $nodeParsers = [];

    /**
     * @var string[] $nodeChildrenParsingNeeded Array containing node type that need .
     */
    private $nodeChildrenParsingNeeded = [];

    /**
     * PhpFileParser constructor.
     *
     * @param Parser $phpParser
     */
    public function __construct(
        Parser $phpParser
    ) {
        $this->phpParser = $phpParser;

        $this->nodeParsers = [
            Node\Stmt\Namespace_::class => new NamespaceNodeParser(),
            Node\Stmt\Use_::class       => new UseNodeParser(),
            Node\Stmt\Function_::class  => null,
            Node\Stmt\Interface_::class => new InterfaceNodeParser(),
            Node\Stmt\Trait_::class     => null,
            Node\Stmt\Class_::class     => null
        ];
        $this->nodeChildrenParsingNeeded = [
            Node\Stmt\Namespace_::class,
            Node\Stmt\Interface_::class,
            Node\Stmt\Trait_::class,
            Node\Stmt\Class_::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $code): PhpFileModelInterface
    {
        try {
            $nodes = $this->phpParser->parse($code);
        } catch (Error $error) {
            throw new ParsingException("Unable to parse given php code (maybe your code contains errors).");
        }

        /** @var PhpFileModelInterface $phpFileModel */
        $phpFileModel = new PhpFileModel();
        $phpFileModel = $this->parseNodes($nodes, $phpFileModel);

        return $phpFileModel;
    }

    /**
     * Parse nodes recursively if $nodes is validate as an array.
     *
     * @param mixed         $nodesToParse Nodes to parse.
     * @param NodeInterface $node         The node to inject parsed information in.
     *
     * @return NodeInterface The modified php file model.
     */
    private function parseNodes($nodesToParse, NodeInterface $node): NodeInterface
    {
        if (Validator::arrayType()->length(1, null)->validate($nodesToParse)) {
            foreach ($nodesToParse as $nodeToParse) {
                $node = $this->parseNode($nodeToParse, $node);
            }
        }
        return $node;
    }

    /**
     * Parse a node and statements recursively and inject information if possible.
     *
     * @param Node          $nodeToParse The node to parse.
     * @param NodeInterface $node        The node to inject parsed information in.
     *
     * @return NodeInterface The modified php file model.
     */
    private function parseNode(Node $nodeToParse, NodeInterface $node): NodeInterface
    {
        $children = [];

        $resultingNode = $node;

        // Find the type of the node
        $class = get_class($nodeToParse);

        if (Validator::key($class, Validator::instance(NodeParserInterface::class))
            ->validate($this->nodeParsers)
        ) {
            $resultingNode = ($this->nodeParsers[$class])->parse($nodeToParse, $resultingNode);
            // Check if we need to parse children of the node.
            if (Validator::contains($class)->validate($this->nodeChildrenParsingNeeded)) {
                return $this->parseNodes($children, $resultingNode);
            }
        }

        return $resultingNode;
    }
}
