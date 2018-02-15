<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Parser\NodeParser;

use PhpParser\Node\FunctionLike;
use PhpUnitGen\Configuration\ConfigurationInterface\ConfigInterface;
use PhpUnitGen\Model\ModelInterface\FunctionModelInterface;
use PhpUnitGen\Model\ReturnModel;

/**
 * Class AbstractFunctionNodeParser.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
abstract class AbstractFunctionNodeParser extends AbstractNodeParser
{
    /**
     * @var ConfigInterface $config The configuration.
     */
    protected $config;

    /**
     * @var ParameterNodeParser $parameterNodeParser The parameter node parser.
     */
    protected $parameterNodeParser;

    /**
     * @var TypeNodeParser $typeNodeParser The type node parser.
     */
    protected $typeNodeParser;

    /**
     * @var DocumentationNodeParser $documentationNodeParser The documentation node parser.
     */
    protected $documentationNodeParser;

    /**
     * FunctionNodeParser constructor.
     *
     * @param ConfigInterface         $config                  The configuration.
     * @param ParameterNodeParser     $parameterNodeParser     The parameter node parser.
     * @param TypeNodeParser          $typeNodeParser          The type node parser.
     * @param DocumentationNodeParser $documentationNodeParser The documentation node parser.
     */
    public function __construct(
        ConfigInterface $config,
        ParameterNodeParser $parameterNodeParser,
        TypeNodeParser $typeNodeParser,
        DocumentationNodeParser $documentationNodeParser
    ) {
        $this->config                  = $config;
        $this->parameterNodeParser     = $parameterNodeParser;
        $this->typeNodeParser          = $typeNodeParser;
        $this->documentationNodeParser = $documentationNodeParser;
    }

    /**
     * Parse a function like node to update a function model.
     *
     * @param FunctionLike           $node     The node to parse.
     * @param FunctionModelInterface $function The model to update.
     */
    protected function parseFunction(FunctionLike $node, FunctionModelInterface $function): void
    {
        foreach ($node->getParams() as $param) {
            $this->parameterNodeParser->invoke($param, $function);
        }

        $return = new ReturnModel();
        $return->setParentNode($function);
        if ($node->getReturnType() !== null) {
            $this->typeNodeParser->invoke($node->getReturnType(), $return);
        }
        $function->setReturn($return);
    }
}
