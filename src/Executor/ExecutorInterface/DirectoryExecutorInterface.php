<?php

namespace PhpUnitGen\Executor\ExecutorInterface;

/**
 * Interface DirectoryExecutorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface DirectoryExecutorInterface
{
    /**
     * Execute all PhpUnitGen tasks from parsing to code generation for a source directory.
     *
     * @param string $sourcePath The source directory path.
     * @param string $targetPath The target directory path.
     */
    public function execute(string $sourcePath, string $targetPath): void;
}