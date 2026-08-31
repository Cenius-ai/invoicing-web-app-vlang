@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('heading', 'Invoice ' . $invoice->invoice_number)

@section('actions')
<div class="action-group">
    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-secondary">Edit</a>
    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this invoice?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>
@endsection

@section('content')
<div class="invoice-detail">
    <div class="detail-grid">
        <div class="detail-block">
            <h3 class="detail-label">Client</h3>
            <p class="detail-value"><strong>{{ $invoice->client->name }}</strong></p>
            <p class="detail-value">{{ $invoice->client->email }}</p>
            <p class="detail-value">{{ $invoice->client->address }}</p>
        </div>
        <div class="detail-block">
            <h3 class="detail-label">Status</h3>
            <span class="badge badge-{{ $invoice->status }} badge-lg">{{ ucfirst($invoice->status) }}</span>
        </div>
        <div class="detail-block">
            <h3 class="detail-label">Issue Date</h3>
            <p class="detail-value">{{ $invoice->issue_date->format('F d, Y') }}</p>
        </div>
        <div class="detail-block">
            <h3 class="detail-label">Due Date</h3>
            <p class="detail-value">{{ $invoice->due_date->format('F d, Y') }}</p>
        </div>
    </div>

    <div class="panel" style="margin-top: 2rem;">
        <div class="panel-header">
            <h2 class="panel-title">Line Items</h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th class="w-60">Description</th>
                    <th class="w-10 right">Qty</th>
                    <th class="w-15 right">Unit Price</th>
                    <th class="w-15 right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->lineItems as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="mono right">{{ $item->quantity }}</td>
                    <td class="mono right">${{ $item->unit_price_formatted }}</td>
                    <td class="mono right">${{ $item->total_formatted }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="right"><strong>Total</strong></td>
                    <td class="mono right"><strong>${{ $invoice->amount_formatted }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
