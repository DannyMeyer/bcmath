<?php

namespace bcmath\Helper;

use bcmath\Format\Binary;
use bcmath\Format\Decimal;
use bcmath\Format\FormatInterface;
use bcmath\Format\Hexadecimal;

/**
 * Trait FractionHelper
 *
 * @package bcmath\Helper
 */
trait FractionHelper
{
    /**
     * Create fractionHelper
     *
     * @param string      $number
     * @param string|null $fraction
     *
     * @return FormatInterface
     */
    protected function createFractionHelper(string $number, string $fraction = null): FormatInterface
    {
        if ($this instanceof Decimal) {
            return new Decimal($number, $fraction);
        }

        if ($this instanceof Hexadecimal) {
            return new Hexadecimal($number, $fraction);
        }

        return new Binary($number, $fraction);
    }
}
