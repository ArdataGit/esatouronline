<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalInvoice extends Model
{
    use HasFactory;

    protected $table = 'physical_invoice';
    protected $guarded = ['id'];
}
