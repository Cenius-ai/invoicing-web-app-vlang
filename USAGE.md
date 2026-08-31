# Usage

Start the development server (see [INSTALL.md](INSTALL.md)) and open `http://localhost:8000` in a browser.

## Pages

- **Dashboard** – shows summary counts (e.g., total invoices, paid/unpaid totals). Access via the navigation bar or `/`.
- **Invoices** – lists all invoices with status, client name, and total. Click an invoice to view details, or use the "New Invoice" button to create one.
- **Invoice Detail** – displays a client’s information, line items (description, quantity, unit price), subtotal, and grand total. Status can be changed if the invoice is editable.
- **Create / Edit Invoice** – form with fields: client selection, line item addition (description, quantity, price), invoice status (draft, sent, paid, etc.).

## Navigation

A Blade‑powered navigation menu links to the Dashboard and Invoices list. The invoice detail page provides a back link to the list.