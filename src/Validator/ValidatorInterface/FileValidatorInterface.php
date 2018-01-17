<?php

namespace PhpUnitGen\Validator\ValidatorInterface;

use PhpUnitGen\Exception\FileNotFoundException;

/**
 * Interface FileValidatorInterface.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
interface FileValidatorInterface
{
    /**
     * Validate a file.
     *
     * @param string $path The file path.
     *
     * @return bool True if the file can be parsed.
     *
     * @throws FileNotFoundException If the file does not exists.
     */
    public function validate(string $path): bool;
}
