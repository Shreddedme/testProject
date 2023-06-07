<?php

namespace App\TestProject\Application\Service;

use Doctrine\DBAL\Exception;

class TaxService
{
    private CONST TAX_BY_COUNTRY = [
        'DE' => 0.19,
        'IT' => 0.22,
        'GR' => 0.24,
        'FR' => 0.26
    ];

    public function getTax(string $taxNumber): float
    {
        if (preg_match('/^DE\d{9}$/', $taxNumber)) {
            return self::TAX_BY_COUNTRY['DE'];
        }

        if (preg_match('/^IT\d{11}$/', $taxNumber)) {
            return self::TAX_BY_COUNTRY['IT'];
        }

        if (preg_match('/^GR\d{9}$/', $taxNumber)) {
            return self::TAX_BY_COUNTRY['GR'];
        }

        if (preg_match('/^FR[A-Z]{2}\d{9}$/', $taxNumber)) {
            return self::TAX_BY_COUNTRY['FR'];
        }

        throw new Exception('Invalid taxNumber');
    }
}