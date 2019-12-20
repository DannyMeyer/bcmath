<?php

namespace BCMath\Math;

use function substr;

/**
 * Trait Floor
 *
 * @package BCMath\Math
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
trait Floor
{
    /**
     * Floor a big number
     *
     * Use precision to floor to an fractional part
     *
     * @param int  $precision Precision to floor to decimals
     * @param bool $switchToCeilOnNegative
     *
     * @return void
     */
    public function floor(int $precision = 0, bool $switchToCeilOnNegative = true): void
    {
        if ($switchToCeilOnNegative === true && $this->isNegative()) {
            $this->ceil($precision, false);
            return;
        }

        if ($precision < 0) {
            return;
        }

        $this->fraction = substr($this->fraction, 0, $precision);
    }
}
