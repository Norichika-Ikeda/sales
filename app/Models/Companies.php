<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products;

class Companies extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Products::class, 'company_id');
    }
}
