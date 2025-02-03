<?php
namespace Modules\Properties\App\Contracts;

use Illuminate\Pagination\Paginator;

interface OwnerReportInterface
{
    public function getRenters();

    public function getInstallments();

    public function getBuyers();

    public function getRenterDetails(int $userId): array;

    public function getInstallmentDetails(int $userId): array;
}
