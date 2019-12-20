<?php

namespace BCMath\Format;

use BCMath\Helper;
use BCMath\Math;

use function array_reverse;
use function bcadd;
use function bccomp;
use function bcdiv;
use function bcmod;
use function bcmul;
use function bcsub;
use function count;
use function dechex;
use function floor;
use function implode;
use function strlen;

/**
 * Class Decimal
 *
 * @package BCMath\Format
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
class Decimal extends Format
{
    use Helper\FractionHelper;
    use Math\Floor;
    use Math\Ceil;
    use Math\Round;
    use Math\Abs;

    public const FORMAT_VALIDATION = '/^\d*$/';

    /**
     * Receive Format
     *
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT_DECIMAL;
    }

    /**
     * Convert number into decimal number
     *
     * @return Decimal
     */
    public function convertToDecimal(): Decimal
    {
        return $this;
    }

    /**
     * Convert decimal to binary
     *
     * @param int|null $fractionPrecision
     *
     * @return Binary
     */
    public function convertToBinary(?int $fractionPrecision = 16): Binary
    {
        $hexadecimal = $this->convertToHexadecimal($fractionPrecision);
        return $hexadecimal->convertToBinary();
    }

    /**
     * Convert to hexadecimal
     *
     * @param int|null $fractionPrecision
     *
     * @return Hexadecimal
     */
    public function convertToHexadecimal(?int $fractionPrecision = 16): Hexadecimal
    {
        $decimal = $this->number;
        $hexadecimals = [];

        do {
            $leftover = bcmod($decimal, 16);
            $hexadecimals[] = dechex($leftover);
            $decimal = bcdiv($decimal, 16, 0);
        } while (bccomp($decimal, 0) > 0);

        $number = implode('', array_reverse($hexadecimals));

        if ($fractionPrecision === null) {
            $fractionPrecision = strlen($this->fraction) + 10;
        }

        if ($this->fraction !== null) {
            $fraction = $this->convertFractionToHexadecimal($fractionPrecision);
        }

        $prefix = '';

        if ($this->isNegative()) {
            $prefix = self::NEGATIVE_MARK;
        }

        return new Hexadecimal(
            $prefix . $number,
            $fraction ?? null
        );
    }

    /**
     * Convert given decimal fractions to hexadecimal
     *
     * @param int $fractionPrecision
     *
     * @return string
     */
    private function convertFractionToHexadecimal(int $fractionPrecision): string
    {
        $number = '0.' . $this->fraction;
        $scale = strlen($number);
        $hex = [];

        do {
            $result = bcmul($number, '16', $scale);
            $value = floor($result);
            $number = bcsub($result, $value, $scale);

            $hex[] = dechex($value);
        } while (count($hex) < $fractionPrecision && bccomp($number, '0', $scale) > 0);

        return implode('', $hex);
    }

    /**
     * Add number
     *
     * @param string $operand
     * @param int    $precision
     *
     * @return void
     */
    public function add(string $operand, int $precision = 0): void
    {
        //@todo fix this to work with Objects and floating point values
        $this->number = bcadd($this->number, $operand, $precision);
    }
}
