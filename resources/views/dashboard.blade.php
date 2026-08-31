@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-label">Total Invoices</span>
        <span class="stat-value">{{ $totalInvoices }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Total Revenue</span>
        <span class="stat-value">${{ number_format($totalRevenue / 100, 2) }}</span>
    </div>
    <div class="stat-card stat-paid">
        <span class="stat-label">Paid</span>
        <span class="stat-value">{{ $paidCount }}</span>
    </div>
    <div class="stat-card stat-sent">
        <span class="stat-label">Sent</span>
        <span class="stat-value">{{ $sentCount }}</span>
    </div>
    <div class="stat-card stat-overdue">
        <span class="stat-label">Overdue</span>
        <span class="stat-value">{{ $overdueCount }}</span>
    </div>
    <div class="stat-card stat-draft">
        <span class="stat-label">Drafts</span>
        <span class="stat-value">{{ $draftCount }}</span>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2 class="panel-title">Recent Invoices</h2>
        <a href="{{ route('invoices.index') }}" class="panel-action">View all &rarr;</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentInvoices as $inv)
            <tr onclick="window.location='{{ route('invoices.show', $inv) }}'" class="clickable">
                <td class="mono">{{ $inv->invoice_number }}</td>
                <td>{{ $inv->client->name }}</td>
                <td class="mono">${{ $inv->amount_formatted }}</td>
                <td><span class="badge badge-{{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                <td>{{ $inv->issue_date->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-cell">No invoices yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
