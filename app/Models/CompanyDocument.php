<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
        protected $fillable = [
        'name', 'issuance_date', 'issuing_authority', 'renewal_date', 'document_image_path'
    ];
}
