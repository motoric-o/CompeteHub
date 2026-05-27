<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = DB::table('registrations')->get();

        $documents = [];

        foreach ($registrations as $reg) {
            $template = DB::table('form_templates')->where('competition_id', $reg->competition_id)->first();
            if (!$template) {
                continue;
            }

            $fields = json_decode($template->fields, true);
            if (!is_array($fields)) {
                continue;
            }

            foreach ($fields as $field) {
                if (($field['type'] ?? '') === 'file') {
                    $slug = \Illuminate\Support\Str::slug($field['label']);
                    
                    // Set status based on registration status
                    $docStatus = 'verified';
                    if ($reg->status === 'pending') {
                        $docStatus = 'pending';
                    } elseif ($reg->status === 'rejected') {
                        $docStatus = 'rejected';
                    }

                    $documents[] = [
                        'registration_id' => $reg->id,
                        'document_type'   => $field['label'],
                        'file_path'       => "docs/{$slug}.pdf",
                        'status'          => $docStatus,
                        'uploaded_at'     => now(),
                    ];
                }
            }
        }

        if (!empty($documents)) {
            DB::table('registration_documents')->insert($documents);
        }
    }
}
