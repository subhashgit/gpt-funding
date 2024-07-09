<?php

namespace App\Service\LoremIpsum;

class Statistics
{
    /**
     * Returns random number with normal distribution,
     * about some $mean with a standard deviation, $stDev.
     *
     * @return float
     */
    public static function ndRandom(float $mean = 0.0, float $stDev = 1.0): float|int
    {
        $x = self::random($mean, $stDev);
        $y = self::random($mean, $stDev);

        return sqrt(-2 * log($x)) * cos(2 * pi() * $y);
    }

    /**
     * Get a normalized random number about some $mean
     * with a some standard deviation, $stDev.
     */
    public static function gauss_ms(float $mean = 0.0, float $stDev = 1.0): float
    {
        return self::ndRandom() * $stDev + $mean;
    }

    /**
     * Return a random float on [0,1].
     */
    public static function random(): float
    {
        return (float) (mt_rand() / mt_getrandmax());
    }
}
