<?php

namespace bcmath\Math;

/**
 * Trait Abs
 *
 * @package bcmath\Math
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
