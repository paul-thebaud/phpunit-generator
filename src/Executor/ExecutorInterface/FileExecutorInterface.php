<?php

namespace PhpUnitGen\Executor\ExecutorInterface;

use PhpUnitGen\Exception\Exception;

/**
 * Interface FileExecutorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface FileExecutorInterface
{
    /**
     * Execute all PhpUnitGen tasks from parsing to code generation for a source file.
     *
     * @param string $sourcePath The source file path.
     * @param string $targetPath The target file path.
     * @param string $name       The php tests class name (optional, default is 'Generated').
     *
     * @return bool True if all tasks were correctly executed.
     *
     * @throws Exception If an error occurred during process.
     */
    public function invoke(string $sourcePath, string $targetPath, string $name): bool;
}
