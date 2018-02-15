<?php

/**
 * This file is part of PHPUnit Generator.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Annotation;

use PhpUnitGen\Annotation\AnnotationInterface\AnnotationInterface;
use PhpUnitGen\Exception\AnnotationParseException;
use PhpUnitGen\Exception\JsonException;
use PhpUnitGen\Util\Json;

/**
 * Class SetAnnotation.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class SetAnnotation extends AbstractAnnotation
{
    /**
     * @var string $property The name of the property to get.
     */
    private $property;

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return AnnotationInterface::TYPE_SET;
    }

    /**
     * {@inheritdoc}
     */
    public function compile(): void
    {
        if (strlen($this->getStringContent()) > 0) {
            // Decode JSON content
            try {
                $decoded = Json::decode($this->getStringContent());
            } catch (JsonException $exception) {
                throw new AnnotationParseException('"setter" annotation content is invalid (invalid JSON content)');
            }
            if (! is_string($decoded)) {
                throw new AnnotationParseException(
                    '"setter" annotation content is invalid (property name must be a string)'
                );
            }
            $this->property = $decoded;
        } else {
            $this->property = preg_replace(
                '/^set/',
                '',
                $this->getParentNode()->/** @scrutinizer ignore-call */
                getName()
            );
            $this->property = lcfirst($this->property);
        }
    }

    /**
     * @return string The name of the property to get.
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
