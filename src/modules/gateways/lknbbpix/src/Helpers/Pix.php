<?php

namespace Lkn\BBPix\Helpers;

use DateInterval;
use DateTime;

final class Pix
{
    public static function isExpired(string $expirationDate, string $createdAtDate): bool
    {
        $today = new DateTime();
        $daysPixIsValid = new DateInterval('PT' . $expirationDate . 'S');
        $expiryDate = new DateTime($createdAtDate);
        $expiryDate->add($daysPixIsValid);

        return $today > $expiryDate;
    }
}
