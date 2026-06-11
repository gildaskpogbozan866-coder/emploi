<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function show(Document $document)
    {
        $document->load(['user', 'type']);
        return view('admin.documents.detail', compact('document'));
    }

    public function destroy(Document $document)
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($document->fichier);
        $document->delete();
        return redirect()->route('admin.documents.list')->with('success', 'Document supprimé.');
    }
}
