<?php

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Comment\Doc;
use PhpUnitGen\Annotation\AnnotationParser;
use PhpUnitGen\Exception\Exception;
use PhpUnitGen\Model\PropertyInterface\DocumentationInterface;
use PhpUnitGen\Model\PropertyInterface\NodeInterface;

/**
 * Class DocumentationNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class DocumentationNodeParser
{
    /**
     * @var AnnotationParser $annotationParser The annotation parser to use.
     */
    private $annotationParser;

    /**
     * DocumentationNodeParser constructor.
     *
     * @param AnnotationParser $annotationParser The annotation parser to use.
     */
    public function __construct(AnnotationParser $annotationParser)
    {
        $this->annotationParser = $annotationParser;
    }

    /**
     * Parse a node to update the parent node model.
     *
     * @param mixed         $node   The node to parse.
     * @param NodeInterface $parent The parent node.
     */
    public function invoke($node, NodeInterface $parent): void
    {
        if (! $node instanceof Doc || ! $parent instanceof DocumentationInterface) {
            throw new Exception('DocumentationNodeParser is made to parse a documentation node');
        }

        $documentation = $node->getText();
        $parent->setDocumentation($documentation);

        $this->annotationParser->invoke($parent, $documentation);
    }
}
