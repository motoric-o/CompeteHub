<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Services\Facade\NotificationFacade;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function create()
    {
        // Ambil semua kompetisi untuk dropdown
        $competitions = Competition::orderBy('created_at', 'desc')->get();
        return view('broadcast.create', compact('competitions'));
    }

    public function store(Request $request, NotificationFacade $notificationFacade)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $notificationFacade->broadcastToParticipants(
                $request->competition_id,
                $request->subject,
                $request->body
            );

            return redirect()->back()->with('success', 'Email broadcast berhasil dikirim ke seluruh peserta lomba terpilih!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
