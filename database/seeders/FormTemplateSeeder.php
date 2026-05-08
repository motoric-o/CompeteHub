<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $hackathon = DB::table('competitions')->where('name', 'Hackathon Nasional 2025')->value('id');
        $cp        = DB::table('competitions')->where('name', 'Competitive Programming Cup')->value('id');

        DB::table('form_templates')->insert([
            [
                'competition_id' => $hackathon,
                'name'           => 'Formulir Hackathon',
                'fields'         => json_encode([
                    ['label' => 'KTP',             'type' => 'file',   'required' => true],
                    ['label' => 'Kartu Mahasiswa', 'type' => 'file',   'required' => true],
                    ['label' => 'Proposal Ide',    'type' => 'file',   'required' => true],
                    ['label' => 'Github Profile',  'type' => 'url',    'required' => false],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $cp,
                'name'           => 'Formulir CP Cup',
                'fields'         => json_encode([
                    ['label' => 'KTP',             'type' => 'file',   'required' => true],
                    ['label' => 'Kartu Mahasiswa', 'type' => 'file',   'required' => true],
                    ['label' => 'Codeforces ID',   'type' => 'text',   'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
