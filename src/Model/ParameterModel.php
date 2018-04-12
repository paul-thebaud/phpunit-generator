<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Model;

use PhpUnitGen\Model\ModelInterface\ParameterModelInterface;
use PhpUnitGen\Model\PropertyTrait\NameTrait;
use PhpUnitGen\Model\PropertyTrait\NodeTrait;
use PhpUnitGen\Model\PropertyTrait\TypeTrait;
use PhpUnitGen\Model\PropertyTrait\ValueTrait;

/**
 * Class ParameterModel.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ParameterModel implements ParameterModelInterface
{
    use NameTrait;
    use TypeTrait;
    use ValueTrait;
    use NodeTrait;

    /**
     * @var bool $isVariadic A boolean describing if it is variadic.
     */
    private $isVariadic = false;

    /**
     * {@inheritdoc}
     */
    public function setIsVariadic(bool $isVariadic): void
    {
        $this->isVariadic = $isVariadic;
    }

    /**
     * {@inheritdoc}
     */
    public function isVariadic(): bool
    {
        return $this->isVariadic;
    }
}
