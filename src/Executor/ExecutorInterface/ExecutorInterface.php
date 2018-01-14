<?php

namespace PhpUnitGen\Executor\ExecutorInterface;

/**
 * Interface ExecutorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface ExecutorInterface
{
    /**
     * Execute all PhpUnitGen tasks from parsing to code generation for a source code.
     *
     * @param string $code The php code to parse.
     *
     * @return string The generated unit tests skeleton.
     */
    public function execute(string $code): string;
}
