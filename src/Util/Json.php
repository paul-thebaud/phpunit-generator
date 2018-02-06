<?php

namespace PhpUnitGen\Util;

use PhpUnitGen\Exception\JsonException;

/**
 * Class Json.
 *
 * @author     Paul Thébaud <paul.thebaud29@gmail.com>.
 * @copyright  2017-2018 Paul Thébaud <paul.thebaud29@gmail.com>.
 * @license    https://opensource.org/licenses/MIT The MIT license.
 * @link       https://github.com/paul-thebaud/phpunit-generator
 * @since      Class available since Release 2.0.0.
 */
class Json
{
    /**
     * Decode a Json string to return an array content.
     *
     * @param mixed $string The string to decode.
     *
     * @return mixed The decoded json.
     *
     * @throws JsonException If the string can not be decoded.
     */
    public static function decode($string)
    {
        if (! is_string($string)) {
            throw new JsonException('Json decode parameter must be a string');
        }

        $result = json_decode($string, true);

        if ($result === null) {
            $error = error_get_last();
            throw new JsonException('Json decode fail');
        }
        return $result;
    }
}
