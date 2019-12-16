<?php

namespace bcmath\Math;

use bcmath\Format\Decimal;
use bcmath\Format\FormatInterface;
use bcmath\Format\Hexadecimal;

use function rtrim;
use function strlen;
use function substr;

/**
 * Trait Ceil
 *
 * @package bcmath\Math
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
trait Ceil
{
    /**
     * Ceil a big number
     *
     * Use precision to ceil to an fractional part
     *
     * @param int  $precision
     * @param bool $switchToFloorOnNegative
     *
     * @return void
     */
    public function ceil(int $precision = 0, bool $switchToFloorOnNegative = true): void
    {
        if ($switchToFloorOnNegative === true && $this->isNegative()) {
            $this->floor($precision, false);
            return;
        }

        $fraction = rtrim($this->getFraction() ?? '', '0');

        if (
            $precision < 0
            || $precision >= strlen($fraction)
        ) {
            // We don't have anything to do here
            return;
        }

        /** @var FormatInterface $fractionHelper */
        $fractionHelper = $this->createFractionHelper(
            substr($fraction, 0, $precision)
        );

        $decimal = $fractionHelper->convertToDecimal();
        $decimal->add(1);

        $previousState = $this->convertDecimalToPreviousState($decimal);

        if (strlen((string)$previousState) === $precision) {
            // We don't have to increase the number here
            $this->fraction = $previousState->getNumber();
            return;
        }

        /**
         * We have to increase the number and to reset the fraction
         *       f => 10
         *      11 => 100
         */

        $this->fraction = null;

        /** @var Decimal $calculationHelper */
        $calculationHelper = $this->convertToDecimal();
        $calculationHelper->add(1);

        $previousState = $this->convertDecimalToPreviousState($calculationHelper);
        $this->number = $previousState->getNumber();
    }

    /**
     * convert decimal back to previous state
     *
     * @param Decimal $decimal
     *
     * @return FormatInterface
     */
    private function convertDecimalToPreviousState(Decimal $decimal): FormatInterface
    {
        if ($this instanceof Decimal) {
            return $decimal;
        }

        if ($this instanceof Hexadecimal) {
            return $decimal->convertToHexadecimal();
        }

        return $decimal->convertToBinary();
    }
}
