<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Tecnologia',
            'Negócios',
            'Saúde',
            'Educação',
            'Esporte',
            'Finança',
            'Viagem',
            'Culinária',
        ];

        foreach ($tags as $tag) {
            
            Tag::updateOrCreate([
                'name' => ucfirst($tag),
                'slug' => Str::slug($tag),
            ]);
        }
    }
}
