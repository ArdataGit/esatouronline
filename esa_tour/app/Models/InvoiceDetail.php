<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    
    protected $table = 'invoice_d';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Product::class, 'category_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'from_bank', 'id');
    }
}
