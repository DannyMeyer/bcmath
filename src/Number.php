<?php

namespace bcmath;

use bcmath\Format\Binary;
use bcmath\Format\Decimal;
use bcmath\Format\Format;
use bcmath\Format\FormatInterface;
use bcmath\Format\Hexadecimal;
use bcmath\Math\MathInterface;

use function explode;

/**
 * Class Number
 *
 * Handle Numbers
 *
 * @package bcmath
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
class Number
{
    /** @var FormatInterface|MathInterface */
    private $number;

    public const DECIMAL_LIMITER = '.';

    /**
     * Number constructor
     *
     * @param string|FormatInterface $number
     * @param string                 $state
     */
    public function __construct($number, string $state = Format::FORMAT_DECIMAL)
    {
        $this->number = $this->handleNumber($number, $state);
    }

    /**
     * @param string|FormatInterface $number
     * @param string                 $state
     *
     * @return FormatInterface
     */
    private function handleNumber($number, string $state): FormatInterface
    {
        if ($number instanceof FormatInterface) {
            return $number;
        }

        $numberParts = explode(static::DECIMAL_LIMITER, $number);

        $value = $numberParts[0] ?? '0';
        $decimals = $numberParts[1] ?? null;

        if ($state === Format::FORMAT_DECIMAL) {
            return new Decimal($value, $decimals);
        }

        if ($state === Format::FORMAT_HEXADECIMAL) {
            return new Hexadecimal($value, $decimals);
        }

        return new Binary($value, $decimals);
    }

    /**
     * Get current value
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->number;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Convert number to Decimal
     *
     * @return void
     */
    public function convertToDecimal(): void
    {
        $this->number = $this->number->convertToDecimal();
    }

    /**
     * Convert number to hexadecimal
     *
     * @param int|null $fractionPrecision Only used from decimal number to hexadecimal conversion
     *
     * @return void
     */
    public function convertToHexadecimal(?int $fractionPrecision = 16): void
    {
        $this->number = $this->number->convertToHexadecimal($fractionPrecision);
    }

    /**
     * Convert number to binary
     *
     * @param int|null $fractionPrecision Only used from decimal number to binary conversion
     *
     * @return void
     */
    public function convertToBinary(?int $fractionPrecision = 16): void
    {
        $this->number = $this->number->convertToBinary($fractionPrecision);
    }

    /**
     * Get current number format
     *
     * @return string
     */
    public function getFormat(): string
    {
        if ($this->number instanceof Decimal) {
            return Format::FORMAT_DECIMAL;
        }

        if ($this->number instanceof Hexadecimal) {
            return Format::FORMAT_HEXADECIMAL;
        }

        return Format::FORMAT_BINARY;
    }

    /**
     * Floor a big number
     *
     * Use precision to floor to an fractional part
     *
     * @param int $precision Precision to floor to decimals
     *
     * @return void
     */
    public function floor($precision = 0): void
    {
        $this->number->floor($precision);
    }

    /**
     * Ceil a big number
     *
     * Use precision to ceil to an fractional part
     *
     * @param int $precision Precision to ceil to
     *
     * @return void
     */
    public function ceil($precision = 0): void
    {
        $this->number->ceil($precision);
    }

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
        $this->number->round($precision);
    }

    /**
     * Absolute of a number
     *
     * @return void
     */
    public function abs(): void
    {
        $this->number->abs();
    }
}
