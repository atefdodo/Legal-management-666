<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CompanyDocument;

class CompanyDocumentFactory extends Factory
{
    protected $model = CompanyDocument::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'issuance_date' => $this->faker->date(),
            'issuing_authority' => $this->faker->company(),
            'renewal_date' => $this->faker->optional()->date(),
            'document_image_path' => null
        ];
    }
}
