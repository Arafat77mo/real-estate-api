<?php

namespace Modules\Properties\app\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Modules\Properties\app\Services\Owner\UserReportService;
use Modules\Properties\app\Transformers\Owner\InstallmentResource;
use Modules\Properties\app\Transformers\Owner\RenterResource;
use Modules\Properties\app\Helpers\ResponseData;

class UserReportController extends Controller
{
    protected $reportService;

    public function __construct(UserReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function showRentersReport()
    {
        $renters = $this->reportService->getRenters();
        return ResponseData::send('success', __('messages.renters_report'), $renters);
    }

    public function showInstallmentsReport()
    {
        $installments = $this->reportService->getInstallments();
        return ResponseData::send('success', __('messages.installments_report'), $installments);
    }

    public function showBuyersReport()
    {
        $buyers = $this->reportService->getBuyers();
        return ResponseData::send('success', __('messages.buyers_report'), $buyers);  // أو استخدام BuyerResource لو كان مخصص للمشترين
    }

    public function showRenterDetails($userId, $propertyId)
    {
        $renterDetails = $this->reportService->getRenterDetails($userId, $propertyId);
        return ResponseData::send('success', __('messages.renter_details'), $renterDetails);
    }

    public function showInstallmentDetails($userId, $propertyId)
    {
        $installmentDetails = $this->reportService->getInstallmentDetails($userId, $propertyId);
        return ResponseData::send('success', __('messages.installment_details'), $installmentDetails);
    }
}
