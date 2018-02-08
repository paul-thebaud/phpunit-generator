<?php

namespace PhpUnitGen\Renderer\Helper;

/**
 * Class ParametersHelper.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class ParametersHelper
{
    /**
     * Convert an array of string parameters in string, with each parameters separated with a ','.
     *
     * @param string[] $parameters The parameters array.
     *
     * @return string The rendered parameters string.
     */
    public function invoke(array $parameters): string
    {
        $string = '';
        foreach ($parameters as $parameter) {
            $string .= $parameter . ', ';
        }
        return substr($string, 0, -2);
    }
}