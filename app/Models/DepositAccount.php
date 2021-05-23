<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_account',
        'currency',
    ];
}
