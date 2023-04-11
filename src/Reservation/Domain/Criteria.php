<?php

declare(strict_types=1);

namespace Reservation\Domain;

use Reservation\Domain\Identity\CompanyId;

final class Criteria
{
    private CompanyId $companyId;

    public function __construct(CompanyId $companyId)
    {
        $this->companyId = $companyId;
    }

    public function companyId(): CompanyId
    {
        return $this->companyId;
    }
}
