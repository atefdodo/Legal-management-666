<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyDocument;
use Illuminate\Support\Str;

class CompanyDocumentSeeder extends Seeder
{
    public function run(): void
    {
        CompanyDocument::factory()->count(10)->create();
    }
}
