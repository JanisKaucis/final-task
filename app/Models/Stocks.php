<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'company_name',
        'symbol',
        'price_at_buy',
        'amount',
        'total_price',
        'logo'
    ];
}
