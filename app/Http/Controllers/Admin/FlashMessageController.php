<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashMessageController extends Controller
{
    public function export(Request $request, \App\Services\ExportService $exportService)
    {
        $messages = \App\Models\FlashMessage::latest()->get();

        return $exportService->export(
            $request,
            'Messages Flash',
            'flash_messages_' . date('Y-m-d'),
            ['ID', 'Contenu du Message', 'Créé le'],
            $messages,
            function ($msg) {
                return [
                    $msg->id,
                    strip_tags($msg->message),
                    $msg->created_at ? $msg->created_at->format('Y-m-d H:i') : ''
                ];
            }
        );
    }

    public function index()
    {
        $messages = \App\Models\FlashMessage::latest()->paginate(10);
        return view('admin.flash_messages.index', compact('messages'));
    }

    public function create()
    {
        return view('admin.flash_messages.create');
    }

    public function store(Request $request)
    {
        $request->validate(['message' => 'required']);
        \App\Models\FlashMessage::create($request->all());
        return redirect()->route('admin.flash-messages.index')->with('success', 'Message Flash ajouté.');
    }

    public function edit($id)
    {
        $message = \App\Models\FlashMessage::findOrFail($id);
        return view('admin.flash_messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['message' => 'required']);
        $message = \App\Models\FlashMessage::findOrFail($id);
        $message->update($request->all());
        return redirect()->route('admin.flash-messages.index')->with('success', 'Message Flash mis à jour.');
    }

    public function destroy($id)
    {
        $message = \App\Models\FlashMessage::findOrFail($id);
        $message->delete();
        return redirect()->route('admin.flash-messages.index')->with('success', 'Message Flash supprimé.');
    }
}
