<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currency';
    protected $fillable = ['valuteID', 'numCode', 'charCode', 'name', 'value', 'date'];
}
