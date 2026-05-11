@extends('admin.layouts.app')
@section('title', 'Event Control Panel')
@section('content')
<style>.card{background:#fff;border-radius:12px;padding:22px;margin-bottom:18px;box-shadow:0 1px 4px rgba(0,0,0,.06)}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px}.btn{display:inline-block;padding:9px 14px;border-radius:8px;text-decoration:none;border:0;cursor:pointer}.btn-primary{background:#f59e0b;color:#fff}.btn-light{background:#eef2f7;color:#111827}</style>
<div style="margin-bottom:14px"><a class="btn btn-light" href="{{ route('admin.event.index') }}">Back to Events</a></div>
@include('shared.event-control.panel', ['panel' => $panel, 'event' => $event])
@endsection
