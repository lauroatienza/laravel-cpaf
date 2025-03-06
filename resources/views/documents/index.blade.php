<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
</head>
<body>

@extends('layouts.app')

@section('content')
<h2>MOU and MOA</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ route('documents.store') }}" method="POST">
    @csrf
    <label>Contributing Unit</label>
    <select name="contributing_unit">
        <option value="CSPPS">CSPPS</option>
        <option value="CISC">CISC</option>
        <option value="IGRD">IGRD</option>
        <option value="CPAf">CPAf</option>
    </select>

    <label>Type of Partnership Agreement</label>
    <select name="partnership_type">
        <option value="Memorandum of Understanding (MOU)">MOU</option>
        <option value="Memorandum of Agreement (MOA)">MOA</option>
    </select>

    <label>Title of Extension Partnership/Linkage</label>
    <input type="text" name="extension_title" required>

    <label>Name of Partner Stakeholder</label>
    <input type="text" name="partner_stakeholder" required>

    <label>Effective Start Date</label>
    <input type="date" name="start_date" required>

    <label>Effective End Date</label>
    <input type="date" name="end_date" required>

    <label>Training Courses</label>
    <select name="training_courses">
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>

    <label>Scope of Work</label>
    <textarea name="scope_of_work"></textarea>

    <label>PDF File URL</label>
    <input type="url" name="pdf_file_url">

    <button type="submit">Save</button>
</form>

<h3>Existing Documents</h3>
<ul>
    @foreach($documents as $document)
        <li>{{ $document->extension_title }} - <a href="{{ $document->pdf_file_url }}">View PDF</a></li>
    @endforeach
</ul>

</body>
</html>
