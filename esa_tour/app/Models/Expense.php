<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expense';
    protected $guarded = ['id'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    public function category_expense()
    {
        return $this->belongsTo(CategoryExpense::class, 'category_expense_id', 'id');
    }
}
