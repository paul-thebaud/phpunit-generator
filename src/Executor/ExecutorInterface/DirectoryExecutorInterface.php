<?php

/**
 * This file is part of PhpUnitGen.
 *
 * (c) 2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PhpUnitGen\Executor\ExecutorInterface;

use PhpUnitGen\Exception\Exception;

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
     *
     * @throws Exception If an error occurred during process.
     */
    public function invoke(string $sourcePath, string $targetPath): void;
}
