<?php

namespace Symplefony;

use DateTime;

class ConversionTools
{
    /**
     * Convertit une date de DateTime vers string
     * @param DateTime $date
     * @return string
     */
    public static function dateToSQLFormat(DateTime $date): string
    {
        return $date->format('Y-m-d H:m:s');
    }
}
