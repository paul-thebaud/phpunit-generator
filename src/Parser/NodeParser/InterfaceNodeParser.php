<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\InterfaceModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;
use PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameHelper;

/**
 * Class InterfaceNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class InterfaceNodeParser extends AbstractNodeParser
{
    /**
     * @var ConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * @var DocumentationNodeParser $documentationNodeParser The documentation node parser to use.
     */
    private $documentationNodeParser;

    /**
     * InterfaceNodeParser constructor.
     *
     * @param ConfigInterface         $config                  The configuration to use.
     * @param MethodNodeParser        $methodNodeParser        The method node parser to use.
     * @param DocumentationNodeParser $documentationNodeParser The documentation node parser to use.
     */
    public function __construct(
        ConfigInterface $config,
        MethodNodeParser $methodNodeParser,
        DocumentationNodeParser $documentationNodeParser
    ) {
        $this->config                  = $config;
        $this->documentationNodeParser = $documentationNodeParser;

        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     *
     * @throws AnnotationParseException If an annotation can not be parsed.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if ($this->config->hasInterfaceParsing()) {
            if (! $node instanceof Node\Stmt\Interface_ || ! $parent instanceof PhpFileModelInterface) {
                throw new Exception('InterfaceNodeParser is made to parse an interface node');
            }

            $interface = new InterfaceModel();
            $interface->setParentNode($parent);
            $interface->setName(ClassLikeNameHelper::getName($node));
            $parent->addConcreteUse($parent->getFullNameFor($interface->getName()), $interface->getName());

            if (($documentation = $node->getDocComment()) !== null) {
                $this->documentationNodeParser->invoke($documentation, $interface);
            }

            $this->parseSubNodes($node->stmts, $interface);

            $parent->addInterface($interface);
        }
    }
}
