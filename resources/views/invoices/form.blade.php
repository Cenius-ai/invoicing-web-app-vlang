@extends('layouts.app')

@php
    $isEdit = isset($invoice) && $invoice && $invoice->exists;
@endphp

@section('title', $isEdit ? 'Edit ' . $invoice->invoice_number : 'New Invoice')
@section('heading', $isEdit ? 'Edit Invoice ' . $invoice->invoice_number : 'New Invoice')

@section('content')
<form method="POST" action="{{ $isEdit ? route('invoices.update', $invoice) : route('invoices.store') }}" class="invoice-form">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="form-grid">
        <div class="form-group">
            <label for="client_id" class="form-label">Client</label>
            <select name="client_id" id="client_id" class="form-input" required>
                <option value="">— Select Client —</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id ?? '') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-input" required>
                @foreach (['draft', 'sent', 'paid', 'overdue', 'cancelled'] as $status)
                    <option value="{{ $status }}" {{ old('status', $invoice->status ?? 'draft') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="issue_date" class="form-label">Issue Date</label>
            <input type="date" name="issue_date" id="issue_date" class="form-input"
                   value="{{ old('issue_date', ($isEdit && $invoice->issue_date) ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
            @error('issue_date') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" id="due_date" class="form-input"
                   value="{{ old('due_date', ($isEdit && $invoice->due_date) ? $invoice->due_date->format('Y-m-d') : now()->addDays(30)->format('Y-m-d')) }}" required>
            @error('due_date') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="panel" style="margin-top: 2rem;">
        <div class="panel-header">
            <h2 class="panel-title">Line Items</h2>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addLineItem()">+ Add Item</button>
        </div>

        <div id="line-items-container">
            @php $lineItems = old('line_items'); @endphp
            @if ($lineItems && is_array($lineItems) && count($lineItems))
                @foreach ($lineItems as $idx => $li)
                <div class="line-item-row">
                    <div class="line-item-fields">
                        <input type="text" name="line_items[{{ $idx }}][description]" value="{{ $li['description'] ?? '' }}" placeholder="Description" class="form-input" required>
                        <input type="number" name="line_items[{{ $idx }}][quantity]" value="{{ $li['quantity'] ?? 1 }}" min="1" max="99999" placeholder="Qty" class="form-input qty-input" required oninput="updateTotals()">
                        <input type="number" name="line_items[{{ $idx }}][unit_price_dollars]" value="{{ $li['unit_price_dollars'] ?? '' }}" step="0.01" min="0.01" max="99999999.99" placeholder="Unit Price ($)" class="form-input price-input" required oninput="updateTotals()">
                        <span class="line-total">—</span>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-item" onclick="removeLineItem(this)" title="Remove item">&times;</button>
                </div>
                @endforeach
            @elseif ($isEdit && $invoice->lineItems->count())
                @foreach ($invoice->lineItems as $idx => $item)
                <div class="line-item-row">
                    <div class="line-item-fields">
                        <input type="text" name="line_items[{{ $idx }}][description]" value="{{ $item->description }}" placeholder="Description" class="form-input" required>
                        <input type="number" name="line_items[{{ $idx }}][quantity]" value="{{ $item->quantity }}" min="1" max="99999" placeholder="Qty" class="form-input qty-input" required oninput="updateTotals()">
                        <input type="number" name="line_items[{{ $idx }}][unit_price_dollars]" value="{{ number_format($item->unit_price / 100, 2, '.', '') }}" step="0.01" min="0.01" max="99999999.99" placeholder="Unit Price ($)" class="form-input price-input" required oninput="updateTotals()">
                        <span class="line-total">${{ number_format($item->total / 100, 2) }}</span>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-item" onclick="removeLineItem(this)" title="Remove item">&times;</button>
                </div>
                @endforeach
            @else
                <div class="line-item-row">
                    <div class="line-item-fields">
                        <input type="text" name="line_items[0][description]" placeholder="Description" class="form-input" required>
                        <input type="number" name="line_items[0][quantity]" value="1" min="1" max="99999" placeholder="Qty" class="form-input qty-input" required oninput="updateTotals()">
                        <input type="number" name="line_items[0][unit_price_dollars]" step="0.01" min="0.01" max="99999999.99" placeholder="Unit Price ($)" class="form-input price-input" required oninput="updateTotals()">
                        <span class="line-total">—</span>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-item" onclick="removeLineItem(this)" title="Remove item">&times;</button>
                </div>
            @endif
        </div>

        <div class="form-total-row">
            <span class="form-total-label">Invoice Total:</span>
            <span class="form-total-value" id="form-total">$0.00</span>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ $isEdit ? route('invoices.show', $invoice) : route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update Invoice' : 'Create Invoice' }}</button>
    </div>
</form>

<script>
let itemCounter = {{ isset($idx) ? $idx + 1 : ($isEdit ? $invoice->lineItems->count() : 1) }};

function addLineItem() {
    const container = document.getElementById('line-items-container');
    const row = document.createElement('div');
    row.className = 'line-item-row';
    row.innerHTML = `
        <div class="line-item-fields">
            <input type="text" name="line_items[${itemCounter}][description]" placeholder="Description" class="form-input" required>
            <input type="number" name="line_items[${itemCounter}][quantity]" value="1" min="1" max="99999" placeholder="Qty" class="form-input qty-input" required oninput="updateTotals()">
            <input type="number" name="line_items[${itemCounter}][unit_price_dollars]" step="0.01" min="0.01" max="99999999.99" placeholder="Unit Price ($)" class="form-input price-input" required oninput="updateTotals()">
            <span class="line-total">—</span>
        </div>
        <button type="button" class="btn btn-danger btn-sm remove-item" onclick="removeLineItem(this)" title="Remove item">&times;</button>
    `;
    container.appendChild(row);
    itemCounter++;
    updateTotals();
}

function removeLineItem(btn) {
    const rows = document.querySelectorAll('#line-items-container .line-item-row');
    if (rows.length <= 1) return;
    btn.closest('.line-item-row').remove();
    updateTotals();
}

function updateTotals() {
    let grandTotal = 0;
    document.querySelectorAll('#line-items-container .line-item-row').forEach(row => {
        const qty = parseInt(row.querySelector('.qty-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        const lineTotal = qty * price;
        const totalSpan = row.querySelector('.line-total');
        if (totalSpan) totalSpan.textContent = lineTotal > 0 ? '$' + lineTotal.toFixed(2) : '—';
        grandTotal += lineTotal;
    });
    document.getElementById('form-total').textContent = '$' + grandTotal.toFixed(2);
}

document.addEventListener('DOMContentLoaded', updateTotals);
</script>
@endsection
