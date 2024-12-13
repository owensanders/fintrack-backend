<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'monthly_income',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'expenses',
        'expense_total_amount',
    ];

    public function expenses(): HasMany
    {
        return $this->hasMany(UserExpense::class);
    }

    public function getExpensesAttribute(): array
    {
        return $this->expenses()->get()->toArray();
    }

    public function getExpenseTotalAmountAttribute(): float
    {
        //For a larger project caching would be user here
        return (float)$this->expenses()
            ->selectRaw('COALESCE(SUM(expense_amount), 0) as total')
            ->value('total');
    }
}
