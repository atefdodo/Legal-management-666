<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalContract extends Model
{
    protected $fillable = [
        'lessor_name',
        'lessee_name',
        'contract_date',
        'start_date',
        'end_date',
        'rental_location',
        'rent_amount',
        'document_image_path'
    ];
}
