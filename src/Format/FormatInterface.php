<?php

namespace BCMath\Format;

/**
 * Interface FormatInterface
 *
 * @package BCMath\Format
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
interface FormatInterface
{
    /**
     * Receive Format
     *
     * @return string
     */
    public function getFormat(): string;

    /**
     * Convert number into decimal number
     *
     * @return Decimal
     */
    public function convertToDecimal(): Decimal;

    /**
     * Convert number to binary
     *
     * @param int|null $fractionPrecision Only used from decimal number to binary conversion
     *
     * @return Binary
     */
    public function convertToBinary(?int $fractionPrecision = 16): Binary;

    /**
     * Convert number to hexadecimal
     *
     * @param int|null $fractionPrecision Only used from decimal number to hexadecimal conversion
     *
     * @return Hexadecimal
     */
    public function convertToHexadecimal(?int $fractionPrecision = 16): Hexadecimal;

    /**
     * Get number without fractional part
     *
     * @return string
     */
    public function getNumber(): string;

    /**
     * Get fractional part without the number
     *
     * @return string
     */
    public function getFraction(): string;

    /**
     * Get amount of fractions
     *
     * @return int
     */
    public function getFractionAmount(): int;
}
