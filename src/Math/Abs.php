<?php

namespace BCMath\Math;

/**
 * Trait Abs
 *
 * @package BCMath\Math
 * @author  Danny Meyer <danny.meyer@ravenc.de>
 */
trait Abs
{
    /**
     * Create absolute from current number
     *
     * @return void
     */
    public function abs(): void
    {
        $this->isNegative = false;
    }
}
