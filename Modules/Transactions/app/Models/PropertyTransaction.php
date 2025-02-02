<?php

namespace Modules\Transactions\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Properties\App\Models\Property;

// use Modules\Transactions\Database\Factories\PropertyTransactionFactory;

class PropertyTransaction extends Model
{
    protected $fillable = [
        'property_id',
        'user_id', // المستخدم الذي دفع
        'transaction_type', // نوع المعاملة: بيع/تقسيط
        'price',
        'duration_months',
        'is_paid',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function monthlyPayments()
    {
        return $this->hasMany(MonthlyPayment::class, 'property_transaction_id');
    }


}
