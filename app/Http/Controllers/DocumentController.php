<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();
        return view('documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contributing_unit' => 'required|string',
            'partnership_type' => 'required|string',
            'extension_title' => 'required|string',
            'partner_stakeholder' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'training_courses' => 'required|string',
            'technical_advisory_service' => 'required|string',
            'information_dissemination' => 'required|string',
            'consultancy' => 'required|string',
            'community_outreach' => 'required|string',
            'technology_transfer' => 'required|string',
            'organizing_events' => 'required|string',
            'scope_of_work' => 'nullable|string',
            'pdf_file_url' => 'nullable|url'
        ]);

        Document::create($request->all());

        return redirect()->back()->with('success', 'MOU/MOA Document added successfully.');
    }
}
