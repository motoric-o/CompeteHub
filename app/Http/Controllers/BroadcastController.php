<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Services\Facade\NotificationFacade;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function create()
    {
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
            $result = $notificationFacade->broadcastToParticipants(
                $request->competition_id,
                $request->subject,
                $request->body,
                auth()->id()
            );

            $message = "Email broadcast berhasil dikirim ke {$result['sent']} dari {$result['total']} peserta.";

            if ($result['failed'] > 0) {
                $message .= " {$result['failed']} email gagal dan sudah dicatat di Log Notifikasi.";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}