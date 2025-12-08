<?php

namespace App\Http\Controllers;

use App\Models\FacebookMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacebookMessageController extends Controller
{
    /**
     * Display a listing of stored Facebook messages.
     */
    public function index(Request $request): JsonResponse
    {
        $messages = FacebookMessage::query()
            ->when($request->filled('thread_id'), fn ($query) => $query->where('thread_id', $request->input('thread_id')))
            ->when($request->filled('direction'), fn ($query) => $query->where('direction', $request->input('direction')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderByDesc('sent_at')
            ->orderByDesc('id')
            ->paginate($request->input('per_page', 15));

        return response()->json($messages);
    }

    /**
     * Store a newly created Facebook message.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'thread_id'    => ['required', 'string', 'max:255'],
            'sender_id'    => ['nullable', 'string', 'max:255'],
            'recipient_id' => ['nullable', 'string', 'max:255'],
            'sender_name'  => ['nullable', 'string', 'max:255'],
            'message'      => ['required', 'string'],
            'direction'    => ['required', 'in:inbound,outbound'],
            'status'       => ['nullable', 'in:received,sent,read,failed'],
            'sent_at'      => ['nullable', 'date'],
            'metadata'     => ['nullable', 'array'],
        ]);

        $validated['status'] ??= 'received';

        $message = FacebookMessage::create($validated);

        return response()->json($message, 201);
    }
}
