<?php

namespace Modules\Transactions\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Transactions\Database\Factories\InstallmentFactory;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transaction_id', // المعاملة المرتبطة
        'due_date', // تاريخ استحقاق القسط
        'amount', // المبلغ المستحق
        'is_paid', // حالة الدفع
    ];

    public function transaction()
    {
        return $this->belongsTo(PropertyTransaction::class, 'property_transaction_id');
    }
}
