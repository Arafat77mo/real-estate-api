<?php

namespace Modules\Transactions\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Properties\App\Models\Property;

// use Modules\Transactions\Database\Factories\MonthlyPaymentFactory;

class MonthlyPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_transaction_id',
        'amount',
        'property_id',
        'due_date',
        'payment_status',
        'next_due_date'
    ];

    /**
     * العلاقة مع المستخدم (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع العقار (Property)
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * تحقق مما إذا كان الدفع متأخراً
     */
    public function isOverdue()
    {
        return $this->payment_status === 'pending' && now()->greaterThan($this->due_date);
    }

    /**
     * تحديث حالة الدفع إلى مدفوع
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'next_due_date' => now()->addMonth(),
        ]);
    }

    public function transaction()
    {
        return $this->belongsTo(PropertyTransaction::class, 'property_transaction_id');
    }
    public function scopeWithTransactionDetails($query)
    {
        return $query->with(['transaction.user', 'transaction.property']);
    }

}
