<?php declare(strict_types=1);

namespace Shwrm\Miinto\Utils;

class SeedGenerator
{
    const MAX_COUNTER = 100;

    /** @var int */
    private static $counter = 0;

    public static function generate()
    {
        if(self::$counter >= self::MAX_COUNTER) {
            self::$counter = 0;
        }

        return ++self::$counter;
    }
}
