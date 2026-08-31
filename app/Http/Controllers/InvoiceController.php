<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceLineItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')
            ->orderByDesc('issue_date')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('invoices.form', [
            'invoice' => null,
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($validated, $request) {
            $invoice = Invoice::create([
                'client_id' => $validated['client_id'],
                'invoice_number' => $this->nextInvoiceNumber(),
                'status' => $validated['status'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'amount' => 0,
            ]);

            $totalCents = $this->syncLineItems($invoice, $request->input('line_items', []));
            $invoice->update(['amount' => $totalCents]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice ' . $invoice->invoice_number . ' created.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'lineItems']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load(['client', 'lineItems']);
        $clients = Client::orderBy('name')->get();
        return view('invoices.form', compact('invoice', 'clients'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $this->validateInvoice($request, $invoice);

        DB::transaction(function () use ($invoice, $validated, $request) {
            $invoice->update([
                'client_id' => $validated['client_id'],
                'status' => $validated['status'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
            ]);

            $totalCents = $this->syncLineItems($invoice, $request->input('line_items', []));
            $invoice->update(['amount' => $totalCents]);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice ' . $invoice->invoice_number . ' updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $number = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice ' . $number . ' deleted.');
    }

    private function validateInvoice(Request $request, ?Invoice $invoice = null): array
    {
        return $request->validate([
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'line_items' => 'sometimes|array|min:1',
            'line_items.*.description' => 'required|string|max:500',
            'line_items.*.quantity' => 'required|integer|min:1|max:99999',
            'line_items.*.unit_price_dollars' => 'required|numeric|min:0.01|max:99999999.99',
        ], [
            'line_items.*.description.required' => 'Each line item needs a description.',
            'line_items.*.quantity.min' => 'Quantity must be at least 1.',
            'line_items.*.unit_price_dollars.min' => 'Unit price must be at least $0.01.',
            'line_items.*.unit_price_dollars.max' => 'Unit price is too large.',
        ]);
    }

    private function syncLineItems(Invoice $invoice, array $lineItemsData): int
    {
        $invoice->lineItems()->delete();
        $totalCents = 0;

        foreach ($lineItemsData as $item) {
            $unitPriceCents = (int) round(((float) $item['unit_price_dollars']) * 100);
            $quantity = (int) $item['quantity'];
            $lineTotal = $unitPriceCents * $quantity;

            InvoiceLineItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $quantity,
                'unit_price' => $unitPriceCents,
                'total' => $lineTotal,
            ]);

            $totalCents += $lineTotal;
        }

        return $totalCents;
    }

    private function nextInvoiceNumber(): string
    {
        $last = Invoice::orderByDesc('id')->first();
        $nextId = $last ? $last->id + 1 : 1;
        return 'INV-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}
