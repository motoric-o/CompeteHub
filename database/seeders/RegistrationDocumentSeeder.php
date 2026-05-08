<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationDocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil registration IDs berdasarkan urutan insert
        $registrations = DB::table('registrations')->orderBy('id')->pluck('id');

        // Reg 1 = Tim Alpha (Hackathon, payment_ok) → dokumen verified
        $regAlpha = $registrations[0];
        // Reg 2 = Tim Beta (Hackathon, documents_ok) → dokumen verified
        $regBeta  = $registrations[1];
        // Reg 3 = Budi (CP, payment_ok)
        $regBudi  = $registrations[2];
        // Reg 4 = Siti (CP, account_ok) → dokumen belum lengkap
        $regSiti  = $registrations[3];

        DB::table('registration_documents')->insert([
            // Tim Alpha — semua verified
            ['registration_id' => $regAlpha, 'document_type' => 'ktp',       'file_path' => 'docs/alpha_ktp.pdf',       'status' => 'verified',  'uploaded_at' => now()],
            ['registration_id' => $regAlpha, 'document_type' => 'ktm',       'file_path' => 'docs/alpha_ktm.pdf',       'status' => 'verified',  'uploaded_at' => now()],
            ['registration_id' => $regAlpha, 'document_type' => 'proposal',  'file_path' => 'docs/alpha_proposal.pdf',  'status' => 'verified',  'uploaded_at' => now()],
            // Tim Beta — semua verified
            ['registration_id' => $regBeta,  'document_type' => 'ktp',       'file_path' => 'docs/beta_ktp.pdf',        'status' => 'verified',  'uploaded_at' => now()],
            ['registration_id' => $regBeta,  'document_type' => 'ktm',       'file_path' => 'docs/beta_ktm.pdf',        'status' => 'verified',  'uploaded_at' => now()],
            ['registration_id' => $regBeta,  'document_type' => 'proposal',  'file_path' => 'docs/beta_proposal.pdf',   'status' => 'verified',  'uploaded_at' => now()],
            // Budi — CP, verified
            ['registration_id' => $regBudi,  'document_type' => 'ktp',       'file_path' => 'docs/budi_ktp.pdf',        'status' => 'verified',  'uploaded_at' => now()],
            ['registration_id' => $regBudi,  'document_type' => 'ktm',       'file_path' => 'docs/budi_ktm.pdf',        'status' => 'verified',  'uploaded_at' => now()],
            // Siti — CP, masih pending
            ['registration_id' => $regSiti,  'document_type' => 'ktp',       'file_path' => 'docs/siti_ktp.pdf',        'status' => 'pending',   'uploaded_at' => now()],
        ]);
    }
}
