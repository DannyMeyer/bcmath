<?php

namespace BCMath\Math;

use BCMath\Format\FormatInterface;

use function bccomp;
use function substr;

/**
 * Trait Round
 *
 * @package BCMath\Math
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
trait Round
{
    /**
     * Round a big number
     *
     * Use precision to round to an fractional part
     *
     * @param int $precision
     *
     * @return void
     */
    public function round(int $precision = 0): void
    {
        if ($precision >= $this->getFractionAmount()) {
            return;
        }

        $fraction = $this->getFraction();
        $fractionAfterPrecision = substr($fraction, $precision);

        /** @var FormatInterface $fractionHelper */
        $fractionHelper = $this->createFractionHelper('0', $fractionAfterPrecision);
        $decimal = $fractionHelper->convertToDecimal();

        $compare = bccomp((string)$decimal, '0.5', 1);

        if ($compare >= 0) {
            $this->ceil($precision, false);
            return;
        }

        $this->floor($precision, false);
    }
}
