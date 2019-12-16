<?php

namespace bcmath\Format;

use bcmath\Helper;
use bcmath\Math;

use function abs;
use function array_map;
use function base_convert;
use function bcadd;
use function bcmul;
use function bcpow;
use function hexdec;
use function implode;
use function ltrim;
use function rtrim;
use function str_repeat;
use function str_split;
use function strlen;
use function substr;

/**
 * Class Hexadecimal
 *
 * @package bcmath\Format
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
class Hexadecimal extends Format
{
    use Helper\FractionHelper;
    use Math\Floor;
    use Math\Ceil;
    use Math\Round;
    use Math\Abs;

    public const FORMAT_VALIDATION = '/^[0-9abcdefABCDEF]*$/';

    /**
     * Block size for hexadecimal conversion
     * possible values: 1, 2, 3, 4, 5, 6, 7, 8
     */
    public const HEXADECIMAL_BLOCK_SIZE = 8;

    /**
     * Receive Format
     *
     * @return string
     */
    public function getFormat(): string
    {
        return static::FORMAT_HEXADECIMAL;
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
        $number = $this->convertNumberToBinary($this->number);

        if ($this->fraction !== null) {
            $fraction = $this->convertNumberToBinary($this->fraction, false);
            $fraction = rtrim($fraction, '0');
        }

        $prefix = '';

        if ($this->isNegative()) {
            $prefix = self::NEGATIVE_MARK;
        }

        return new Binary(
            $prefix . $number,
            $fraction ?? null
        );
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
        return $this;
    }

    /**
     * Convert hexadecimal number into decimal number
     *
     * @return Decimal
     */
    public function convertToDecimal(): Decimal
    {
        $hexadecimal = $this->number;

        $length = strlen($hexadecimal);
        $number = '0';

        for ($i = $length; $i > 0; $i--) {
            $number = bcadd(
                $number,
                bcmul(
                    hexdec($hexadecimal[$length - $i]),
                    bcpow(
                        '16',
                        $i - 1
                    )
                )
            );
        }

        if ($this->fraction !== null) {
            $fraction = $this->convertFractionToDecimal();
        }

        $prefix = '';

        if ($this->isNegative()) {
            $prefix = self::NEGATIVE_MARK;
        }

        return new Decimal(
            $prefix . $number,
            $fraction ?? null
        );
    }

    /**
     * Convert hexadecimal number to binary and add leading 0 for full block length
     *
     * @param string $hexadecimal Hexadecimal Number
     *
     * @return string Binary Number as String
     */
    private function convertBlockToBinary($hexadecimal): string
    {
        $binary = base_convert($hexadecimal, 16, 2);
        $missingLeadingZeroAmount = Binary::BINARY_BLOCK_SIZE - strlen($binary);

        /** fill with leading 0 to fill block size length */
        $leadingBlockNumbers = str_repeat(
            '0',
            $missingLeadingZeroAmount
        );

        return $leadingBlockNumbers . $binary;
    }

    /**
     * Convert number to binary
     *
     * @param string $number
     * @param bool   $removeLeadingZero
     *
     * @return string
     */
    private function convertNumberToBinary(string $number, $removeLeadingZero = true): string
    {
        /* Split into blocks with 8 characters */
        $blocks = str_split(
            $number,
            static::HEXADECIMAL_BLOCK_SIZE
        );

        $mappedBlocks = array_map(
            [$this, 'convertBlockToBinary'],
            $blocks
        );

        $compoundNumber = implode('', $mappedBlocks);

        if ($removeLeadingZero === true) {
            $compoundNumber = ltrim($compoundNumber, '0');
        }

        return $compoundNumber;
    }

    /**
     * @return string
     */
    private function convertFractionToDecimal(): string
    {
        $splittedDecimalParts = str_split($this->fraction, 1);

        $count = 0;
        $result = '0';

        foreach ($splittedDecimalParts as $part) {
            $count--;

            $multiplier = bcpow(16, $count, abs($count) * 4);
            $scale = strlen($multiplier);

            $partOperand = bcmul(
                (string)hexdec($part),
                $multiplier,
                $scale
            );

            $result = bcadd(
                $result,
                $partOperand,
                $scale
            );
        }

        return substr(
            rtrim(
                $result,
                '0'
            ),
            2
        );
    }
}
