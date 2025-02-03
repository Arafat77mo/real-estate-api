<?php

namespace Modules\Transactions\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Properties\App\Models\Property;


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
        return $this->belongsTo(Property::class, 'property_id');
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

// In PropertyTransaction model
    public function scopeForOwner($query, $ownerId)
    {
        return $query->whereHas('property', fn($q) => $q->where('user_id', $ownerId));
    }

    public function scopeSales($query)
    {
        return $query->where('transaction_type', 'sale');
    }

    public function scopeInstallments($query)
    {
        return $query->where('transaction_type', 'installment');
    }
    public function scopeRenters($query)
    {
        return $query->where('transaction_type', 'rent');
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }

}
