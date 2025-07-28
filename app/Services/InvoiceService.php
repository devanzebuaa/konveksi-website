<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public static function generate(Order $order)
    {
        // Generate PDF dari view 'invoices.template'
        $pdf = Pdf::loadView('invoices.template', compact('order'));

        // Penamaan file berdasarkan order ID
        $filename = 'invoice_' . $order->id . '.pdf';
        $path = 'invoices/' . $filename;

        // Simpan file PDF ke storage/app/public/invoices/
        Storage::disk('public')->put($path, $pdf->output());

        // Update path invoice di tabel orders
        $order->update(['invoice_path' => $path]);

        return $path;
    }
}
