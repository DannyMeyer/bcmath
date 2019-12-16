<?php

namespace bcmath\Math;

/**
 * Interface MathInterface
 *
 * @package bcmath\Math
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
interface MathInterface
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
    public function floor(int $precision = 0, bool $switchToCeilOnNegative = false): void;

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
    public function ceil(int $precision = 0, bool $switchToFloorOnNegative = false): void;

    /**
     * Round a big number
     *
     * Use precision to round to an fractional part
     *
     * @param int $precision
     *
     * @return void
     */
    public function round(int $precision = 0): void;

    /**
     * Create absolute from current number
     *
     * @return void
     */
    public function abs(): void;
}
