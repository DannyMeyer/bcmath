<?php

namespace BCMath\Format;

use BCMath\Helper;
use BCMath\Math;

use function array_map;
use function array_reverse;
use function base_convert;
use function implode;
use function ltrim;
use function str_repeat;
use function str_split;
use function strlen;
use function strrev;

/**
 * Class Binary
 *
 * @package BCMath\Format
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

    /** Block size for binary conversion; Needs to be set to 4 * HEXADECIMAL_BLOCK_SIZE */
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
        $reversedBinary = strrev($this->number);
        $blocks = str_split(
            $reversedBinary,
            static::BINARY_BLOCK_SIZE
        );

        /**
         * Map function to convert reversed binary to hex for each block
         * reverse complete array to have the correct sort order
         * implode array to get complete hexadecimal string
         */
        $mappedBlocks = array_map(
            [$this, 'convertReverseBlockToHexadecimal'],
            $blocks
        );

        $reversedBlocks = array_reverse($mappedBlocks);
        $number = implode('', $reversedBlocks);
        $number = ltrim($number, '0');

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
                [$this, 'convertBlockToHexadecimal'],
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
    private function convertReverseBlockToHexadecimal(string $binary): string
    {
        $normalizedBinary = strrev($binary);
        $hexadecimal = base_convert($normalizedBinary, 2, 16);
        $missingLeadingZeroAmount = Hexadecimal::HEXADECIMAL_BLOCK_SIZE - strlen($hexadecimal);

        /** fill with leading 0 to fill block size length */
        $leadingBlockNumbers = str_repeat(
            '0',
            $missingLeadingZeroAmount
        );

        return $leadingBlockNumbers . $hexadecimal;
    }

    /**
     * Convert binary parts to hexadecimal
     *
     * @param string $binary
     *
     * @return string
     */
    private function convertBlockToHexadecimal(string $binary): string
    {
        $hexadecimal = base_convert($binary, 2, 16);

        $missingLeadingZeroAmount = Hexadecimal::HEXADECIMAL_BLOCK_SIZE - strlen($hexadecimal);
        $leadingBlockNumbers = str_repeat(
            '0',
            $missingLeadingZeroAmount
        );

        return $leadingBlockNumbers . $hexadecimal;
    }
}
