<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnitGenerator\Parser;

use PHPUnitGenerator\Config\ConfigInterface\ConfigInterface;
use PHPUnitGenerator\Model\AnnotationBaseModel;
use PHPUnitGenerator\Model\AnnotationGetModel;
use PHPUnitGenerator\Model\AnnotationSetModel;
use PHPUnitGenerator\Model\ModelInterface\AnnotationModelInterface;
use PHPUnitGenerator\Model\ModelInterface\MethodModelInterface;
use PHPUnitGenerator\Parser\ParserInterface\DocumentationParserInterface;

/**
 * Class CodeParser
 *
 *      An implementation of DocumentationParserInterface
 *
 * @package PHPUnitGenerator\Parser
 */
class DocumentationParser implements DocumentationParserInterface
{
    /**
     * @var string IS_GET_OR_SET_REGEX A regex to check if method is a getter
     *      or setter
     */
    const IS_GET_OR_SET_REGEX = '/^(get|set){1}([a-zA-Z0-9]+){1}$/';

    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * DocumentationParser constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(MethodModelInterface $methodModel): array
    {
        $annotations = [];
        if ($methodModel->getDocumentation()) {
            $strings = explode('@', $methodModel->getDocumentation());
            foreach ($strings as $string) {
                // Parse annotation if possible
                if (($annotation = $this->parseAnnotation($methodModel, $string)) !== null) {
                    if ($annotation->getType() === AnnotationModelInterface::TYPE_PHPUNIT_METHOD) {
                        $annotations[] = $annotation;
                    } else {
                        $annotations[$annotation->getType()] = $annotation;
                    }
                }
            }
        }

        // If auto option is set to true and method is maybe a getter or a setter
        if ($this->config->getOption(ConfigInterface::OPTION_AUTO) === true
            && preg_match(self::IS_GET_OR_SET_REGEX, $methodModel->getName(), $matches) > 0
            && $methodModel->getParentClass()->hasProperty(lcfirst($matches[2]))
        ) {
            $class = '\PHPUnitGenerator\Model\Annotation' . ucfirst($matches[1]) . 'Model';
            /**
             * @var AnnotationModelInterface $annotation
             */
            $annotation = new $class($methodModel);
            $annotations[$annotation->getType()] = $annotation;
        }
        return $annotations;
    }

    /**
     * Parse an annotation and return it if valid (else return null)
     *
     * @param MethodModelInterface $methodModel
     * @param string               $string
     *
     * @return AnnotationModelInterface|null
     */
    protected function parseAnnotation(
        MethodModelInterface $methodModel,
        string $string
    ) {
        // Delete invalid char "*" from string
        $string = str_replace('*', '', $string);
        if (preg_match(AnnotationModelInterface::ANNOTATION_REGEX, $string, $matches) > 0) {
            // Parse annotation
            if (strtolower($matches[1]) === AnnotationModelInterface::TYPE_GET) {
                return new AnnotationGetModel($methodModel);
            } elseif (strtolower($matches[1]) === AnnotationModelInterface::TYPE_SET) {
                return new AnnotationSetModel($methodModel);
            }
            return new AnnotationBaseModel(
                $matches[1],
                $this->parseContent($matches[2] ?? ''),
                $methodModel
            );
        }
        return null;
    }

    /**
     * Parse the annotation to get the content between $openingChar and
     * $closingChar
     *
     * @param string $content
     * @param string $openingChar
     * @param string $closingChar
     *
     * @return string
     */
    protected function parseContent(
        string $content,
        string $openingChar = '(',
        string $closingChar = ')'
    ): string {
        $openingCharCount = 0;
        $closingCharCount = 0;
        for ($i = 0; $i < strlen($content); $i++) {
            $openingCharCount += $content[$i] === $openingChar;
            $closingCharCount += $content[$i] === $closingChar;
            if ($closingCharCount > $openingCharCount) {
                return substr($content, 0, $i);
            }
        }
        if ($openingCharCount === 0) {
            return '';
        }
        return $content;
    }
}
