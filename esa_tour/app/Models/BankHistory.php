<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankHistory extends Model
{
    use HasFactory;

    protected $table = 'bank_history';
    protected $guarded = ['id'];
}
