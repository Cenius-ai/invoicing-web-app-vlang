@extends('layouts.app')

@section('title', 'Invoices')
@section('heading', 'Invoices')

@section('actions')
<a href="{{ route('invoices.create') }}" class="btn btn-primary">+ New Invoice</a>
@endsection

@section('content')
<div class="panel">
    <table class="table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Client</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $inv)
            <tr>
                <td class="mono">
                    <a href="{{ route('invoices.show', $inv) }}" class="table-link">{{ $inv->invoice_number }}</a>
                </td>
                <td>{{ $inv->client->name }}</td>
                <td class="mono">${{ $inv->amount_formatted }}</td>
                <td><span class="badge badge-{{ $inv->status }}">{{ ucfirst($inv->status) }}</span></td>
                <td>{{ $inv->issue_date->format('M d, Y') }}</td>
                <td>{{ $inv->due_date->format('M d, Y') }}</td>
                <td class="row-actions">
                    <a href="{{ route('invoices.edit', $inv) }}" class="action-link">Edit</a>
                    <form action="{{ route('invoices.destroy', $inv) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete {{ $inv->invoice_number }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-link danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-cell">No invoices found. <a href="{{ route('invoices.create') }}">Create your first invoice.</a></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
