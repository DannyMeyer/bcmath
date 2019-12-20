<?php

namespace BCMath\Helper;

use BCMath\Format\Binary;
use BCMath\Format\Decimal;
use BCMath\Format\FormatInterface;
use BCMath\Format\Hexadecimal;

/**
 * Trait FractionHelper
 *
 * @package BCMath\Helper
 * @author  Danny Meyer <danny.meyer@ravenc.de>
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
