<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Model\InterfaceModel;
use PhpUnitGen\Model\ModelInterface\PhpFileModelInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\InterfaceNodeParserInterface;
use PhpUnitGen\Parser\NodeParser\NodeParserInterface\MethodNodeParserInterface;
use PhpUnitGen\Parser\NodeParserUtil\ClassLikeNameTrait;

/**
 * Class InterfaceNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class InterfaceNodeParser extends AbstractNodeParser implements InterfaceNodeParserInterface
{
    use ClassLikeNameTrait;

    /**
     * @var ConfigInterface $config The configuration to use.
     */
    private $config;

    /**
     * InterfaceNodeParser constructor.
     *
     * @param ConfigInterface           $config           The configuration to use.
     * @param MethodNodeParserInterface $methodNodeParser The method node parser to use.
     */
    public function __construct(
        ConfigInterface $config,
        MethodNodeParserInterface $methodNodeParser
    ) {
        $this->config = $config;

        $this->nodeParsers[Node\Stmt\ClassMethod::class] = $methodNodeParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param Node\Stmt\Interface_  $node   The node to parse.
     * @param PhpFileModelInterface $parent The parent node.
     *
     * @return PhpFileModelInterface The updated parent.
     */
    public function invoke(Node\Stmt\Interface_ $node, PhpFileModelInterface $parent): PhpFileModelInterface
    {
        if ($this->config->hasInterfaceParsing()) {
            $interface = new InterfaceModel();
            $interface->setParentNode($parent);
            $interface->setName($this->getName($node));
            $parent->addConcreteUse($parent->getFullNameFor($interface->getName()), $interface->getName());

            $interface = $this->parseSubNodes($node->stmts, $interface);

            $parent->addInterface($interface);
        }

        return $parent;
    }
}
