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
        $uiux      = DB::table('competitions')->where('name', 'UI/UX Design Competition')->value('id');
        $ctf       = DB::table('competitions')->where('name', 'Siber Defense CTF 2026')->value('id');
        $ds        = DB::table('competitions')->where('name', 'Data Science Challenge 2026')->value('id');
        $iot       = DB::table('competitions')->where('name', 'IoT Innovation Cup 2025')->value('id');
        $mobile    = DB::table('competitions')->where('name', 'Mobile App Dev Arena')->value('id');
        $ctfJr     = DB::table('competitions')->where('name', 'Cyber Security CTF Junior')->value('id');
 
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
                    ['label' => 'Full Name', 'type' => 'text', 'required' => true],
                    ['label' => 'Email', 'type' => 'email', 'required' => true],
                    ['label' => 'Phone Number', 'type' => 'text', 'required' => true],
                    ['label' => 'University', 'type' => 'text', 'required' => true],
                    ['label' => 'Previous UI/UX Experience', 'type' => 'textarea', 'required' => false],
                    ['label' => 'Student Card', 'type' => 'file', 'required' => true],
                    ['label' => 'Proposal File', 'type' => 'file', 'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $uiux,
                'name'           => 'Formulir UI/UX Design',
                'fields'         => json_encode([
                    ['label' => 'KTP Ketua',       'type' => 'file',   'required' => true],
                    ['label' => 'Link Portfolio',  'type' => 'url',    'required' => false],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $ctf,
                'name'           => 'Formulir CTF 2026',
                'fields'         => json_encode([
                    ['label' => 'Kartu Mahasiswa', 'type' => 'file',   'required' => true],
                    ['label' => 'Bukti Follow IG', 'type' => 'file',   'required' => true],
                    ['label' => 'ID Discord',      'type' => 'text',   'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $ds,
                'name'           => 'Formulir Data Science',
                'fields'         => json_encode([
                    ['label' => 'KTP',             'type' => 'file',   'required' => true],
                    ['label' => 'CV / Resume',     'type' => 'file',   'required' => true],
                    ['label' => 'Experience',      'type' => 'text',   'required' => false],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $iot,
                'name'           => 'Formulir IoT Cup',
                'fields'         => json_encode([
                    ['label' => 'Kartu Mahasiswa', 'type' => 'file',   'required' => true],
                    ['label' => 'Proposal Desain', 'type' => 'file',   'required' => true],
                    ['label' => 'Idea description', 'type' => 'textarea', 'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $mobile,
                'name'           => 'Formulir Mobile App Arena',
                'fields'         => json_encode([
                    ['label' => 'KTP Anggota',     'type' => 'file',   'required' => true],
                    ['label' => 'Mockup Desain',   'type' => 'file',   'required' => false],
                    ['label' => 'OS Target',       'type' => 'text',   'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'competition_id' => $ctfJr,
                'name'           => 'Formulir CTF Junior',
                'fields'         => json_encode([
                    ['label' => 'Kartu Pelajar',   'type' => 'file',   'required' => true],
                    ['label' => 'Surat Pengantar', 'type' => 'file',   'required' => true],
                    ['label' => 'School Name',     'type' => 'text',   'required' => true],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
