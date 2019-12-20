<?php

namespace BCMath\Format;

use BCMath\Exceptions\InvalidNumberFormat;
use BCMath\Math\MathInterface;

use function preg_match;
use function strlen;
use function strpos;
use function substr;

/**
 * Class Format
 *
 * @package BCMath\Format
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
abstract class Format implements FormatInterface, MathInterface
{
    public const FORMAT_DECIMAL = 'decimal';
    public const FORMAT_HEXADECIMAL = 'hexadecimal';
    public const FORMAT_BINARY = 'binary';

    protected const NEGATIVE_MARK = '-';

    /** @var string */
    protected $number;
    /** @var string|null */
    protected $fraction;
    /** @var bool */
    protected $isNegative = false;

    /**
     * Hexadecimal constructor
     *
     * @param string      $number
     * @param string|null $fraction
     *
     * @throws InvalidNumberFormat
     */
    public function __construct(string $number, ?string $fraction = null)
    {
        if (strpos($number, static::NEGATIVE_MARK) === 0) {
            $this->isNegative = true;
            $number = substr($number, 1);
        }

        if (
            $this->validate($number) === false
            || (
                $fraction !== null
                && $this->validate($fraction) === false
            )
        ) {
            throw new InvalidNumberFormat('Given number has no valid format');
        }

        $this->number = $number;
        $this->fraction = $fraction;
    }

    /**
     * Validate given number
     *
     * @param string $number
     *
     * @return bool
     */
    protected function validate(string $number): bool
    {
        return (bool)preg_match(static::FORMAT_VALIDATION, $number);
    }

    /**
     * Get absolute number without fractional part
     *
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get absolute fractional part without the number
     *
     * @return string
     */
    public function getFraction(): string
    {
        return $this->fraction ?? '';
    }

    /**
     * Get amount of fractions
     *
     * @return int
     */
    public function getFractionAmount(): int
    {
        return strlen($this->getFraction());
    }

    /**
     * Returns if its negative
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->isNegative;
    }

    /**
     * Get complete number
     *
     * @return string
     */
    public function getValue(): string
    {
        $output = '';

        if ($this->isNegative) {
            $output = static::NEGATIVE_MARK;
        }

        $output .= $this->number;

        if (!empty($this->fraction)) {
            $output .= '.' . $this->fraction;
        }

        return $output;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
