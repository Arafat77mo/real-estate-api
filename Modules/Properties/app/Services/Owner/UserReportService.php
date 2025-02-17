<?php
namespace Modules\Properties\app\Services\Owner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\Properties\App\Models\Property;
use Modules\Properties\app\Traits\Payments;
use Modules\Transactions\App\Models\MonthlyPayment;

use Modules\Properties\app\Transformers\Owner\InstallmentResource;
use Modules\Properties\app\Transformers\Owner\PaymentResource;
use Modules\Properties\app\Transformers\Owner\RenterResource;
use Modules\Transactions\App\Models\PropertyTransaction;

class UserReportService
{
    use Payments;
    protected  $owner;
    public function __construct(
        protected PropertyTransaction $propertyTransaction,protected Property $property
    ) {

        $this->owner=\auth()->user();
    }
    public function getBuyers()
    {
        $transactions = $this->propertyTransaction::with(['user', 'property'])
            ->whereHas('property', fn($q) => $q->forOwner($this->owner->id))
            ->where('transaction_type', 'sale')
            ->fastPaginate(5); // أو simplePaginate(10)

        $transactions->getCollection()->transform(function ($transaction) {
            return [
                'user_id' => $transaction->user->id,
                'user_name' => $transaction->user->name,
                'property_id' => $transaction->property->id,
                'property_name' => $transaction->property->name,
                'paid' => $transaction->price,
                'date' => $transaction->created_at->toFormattedDateString(),
                'is_paid' => $transaction->is_paid,
            ];
        });

        return $transactions;

    }

    public function getRenters()
    {
        return $this->getPayments('rent');
    }

    public function getInstallments()
    {
        return $this->getPayments('installment');
    }


// استدعاء الدوال
    public function getRenterDetails($userId, $propertyId)
    {
        return $this->getPaymentDetails($userId, $propertyId, 'rent');
    }

    public function getInstallmentDetails($userId, $propertyId)
    {
        return $this->getPaymentDetails($userId, $propertyId, 'installment');
    }



    public function getDashboardAnalytics(): array
    {
        $cacheKey = "owner_dashboard_{$this->owner->id}";

        return Cache::store('redis')->remember($cacheKey, now()->addMinutes(30), function () {
            $baseQuery = $this->propertyTransaction::with('property')
                ->whereHas('property', fn($q) => $q->forOwner($this->owner->id));

            return [
                'total_properties' => $this->getTotalProperties(),
                'active_properties' => $this->getActivePropertiesCount(),
                ...$this->getTransactionAndUserStats($baseQuery),
                'monthly_revenue' => $this->getMonthlyRevenue($baseQuery),
                'analytics_trend' => $this->getAnalyticsTrend($baseQuery),
            ];
        });
    }



    /**
     * إحصائيات العقارات
     */
    private function getTotalProperties(): int
    {
        return $this->getPropertiesCount()->count();
    }

    private function getActivePropertiesCount(): int
    {
        return  $this->getPropertiesCount()
            ->active()
            ->count();
    }

    /**
     * دمج إحصائيات المعاملات والمستخدمين في استعلام واحد
     */
    private function getTransactionAndUserStats($baseQuery): array
    {
        $cacheKey = "transaction_user_stats_{$this->owner->id}";

        return Cache::store('redis')->remember($cacheKey, now()->addHours(1), function () use ($baseQuery) {
            $stats = $baseQuery->clone()
                ->selectRaw('
                transaction_type,
                COUNT(*) as total_transactions,
                COUNT(DISTINCT user_id) as total_users
            ')
                ->groupBy('transaction_type')
                ->get()
                ->keyBy('transaction_type');

            return [
                'sold_properties' => $stats['sale']->total_transactions ?? 0,
                'rented_properties' => $stats['rent']->total_transactions ?? 0,
                'total_buyers' => $stats['sale']->total_users ?? 0,
                'total_renters' => $stats['rent']->total_users ?? 0,
            ];
        });
    }


    /**
     * الإيرادات الشهرية باستخدام `DATE_FORMAT`
     */
    private function getMonthlyRevenue($baseQuery): array
    {
        $cacheKey = "monthly_revenue_{$this->owner->id}";

        return Cache::store('redis')->remember($cacheKey, now()->addHours(1), function () use ($baseQuery) {
            return $baseQuery->clone()
                ->selectRaw('SUM(price) as revenue, DATE_FORMAT(created_at, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('revenue', 'month')
                ->toArray();
        });
    }

    /**
     * تحليل الاتجاهات
     */
    private function getAnalyticsTrend($baseQuery): array
    {
        $cacheKey = "analytics_trend_{$this->owner->id}";

        return Cache::store('redis')->remember($cacheKey, now()->addHours(1), function () use ($baseQuery) {
            $currentMonth = now()->month;
            $previousMonth = now()->subMonth()->month;

            $stats = $baseQuery->clone()
                ->whereMonth('created_at', $currentMonth)
                ->orWhereMonth('created_at', $previousMonth)
                ->selectRaw('transaction_type, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('transaction_type', 'month')
                ->get()
                ->groupBy('month');

            return $this->calculateTrends($stats, [$currentMonth, $previousMonth]);
        });
    }


    /**
     * حساب نسبة التغيير بين الشهرين
     */
    private function calculateTrends($stats, $months): array
    {
        $current = $stats->get($months[0], collect())->groupBy('transaction_type');
        $previous = $stats->get($months[1], collect())->groupBy('transaction_type');

        return [
            'sale_increase' => $this->calcIncrease(
                $previous->get('sale', collect())->first()->count ?? 0,
                $current->get('sale', collect())->first()->count ?? 0
            ),
            'rent_increase' => $this->calcIncrease(
                $previous->get('rent', collect())->first()->count ?? 0,
                $current->get('rent', collect())->first()->count ?? 0
            )
        ];
    }

    private function calcIncrease(int $previous, int $current): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }


    private function getPropertiesCount()
    {
        return $this->property::forOwner($this->owner->id);
    }


}
