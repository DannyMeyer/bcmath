<?php

namespace bcmath\Format;

use bcmath\Helper;
use bcmath\Math;

use function array_map;
use function array_reverse;
use function bindec;
use function dechex;
use function implode;
use function str_repeat;
use function str_split;
use function strlen;
use function strrev;

/**
 * Class Binary
 *
 * @package bcmath\Format
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
class Binary extends Format
{
    use Helper\FractionHelper;
    use Math\Floor;
    use Math\Ceil;
    use Math\Round;
    use Math\Abs;

    public const FORMAT_VALIDATION = '/^[0-1]*$/';

    /**
     * Block size for binary conversion
     * possible values: 4, 8, 12, 16, 20, 24, 28, 32
     */
    public const BINARY_BLOCK_SIZE = 32;

    /**
     * Receive Format
     *
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT_BINARY;
    }

    /**
     * Convert binary number into decimal number
     *
     * @return Decimal
     */
    public function convertToDecimal(): Decimal
    {
        $hexadecimal = $this->convertToHexadecimal();
        return $hexadecimal->convertToDecimal();
    }

    /**
     * Convert number to binary
     *
     * @param int|null $fractionPrecision Only used from decimal number to binary conversion
     *
     * @return Binary
     */
    public function convertToBinary(?int $fractionPrecision = 16): Binary
    {
        return $this;
    }

    /**
     * Convert binary number to hexadecimal
     *
     * @param int|null $fractionPrecision
     *
     * @return Hexadecimal
     */
    public function convertToHexadecimal(?int $fractionPrecision = 16): Hexadecimal
    {
        /** Reverse binary and split it into blocks with a maximum of 32 characters */
        $blocks = str_split(strrev($this->number), static::BINARY_BLOCK_SIZE);

        /**
         * Map function to convert reversed binary to hex for each block
         * reverse complete array to have the correct sort order
         * implode array to get complete hexadecimal string
         */
        $number = implode('', array_reverse(array_map([$this, 'mapReverseBin2Hex'], $blocks)));

        if (!empty($this->fraction)) {
            $fraction = $this->convertFractionToHexadecimal();
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
     * Convert fraction to hexadecimal
     *
     * @return string
     */
    private function convertFractionToHexadecimal(): string
    {
        $blocks = str_split(
            $this->fraction,
            static::BINARY_BLOCK_SIZE
        );

        return implode(
            '',
            array_map(
                [$this, 'mapBinToHex'],
                $blocks
            )
        );
    }

    /**
     * Convert short reversed binary number to hexadecimal
     *
     * @param string $binary Binary Number
     *
     * @return string Hexadecimal Number as String
     */
    private function mapReverseBin2Hex(string $binary): string
    {
        return dechex(bindec(strrev($binary)));
    }

    /**
     * Convert binary parts to hexadecimal
     *
     * @param string $binary
     *
     * @return string
     */
    private function mapBinToHex(string $binary): string
    {
        $hexadecimal = dechex(bindec($binary));

        $missingLeadingZeroAmount = Hexadecimal::HEXADECIMAL_BLOCK_SIZE - strlen($hexadecimal);
        $leadingBlockNumbers = str_repeat(
            '0',
            $missingLeadingZeroAmount
        );

        return $leadingBlockNumbers . $hexadecimal;
    }
}
