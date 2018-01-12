<?php

namespace PhpUnitGen\Configuration\ConfigurationInterface;

/**
 * Interface ConfigInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ConfigInterface
{
    /**
     * @return bool True if interfaces need to be parsed too.
     */
    public function hasInterfaceParsing(): bool;
}
