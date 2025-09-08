<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'invoice_category_id' => 'required|exists:invoice_categories,id',
            'invoice_date' => 'nullable|date',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        // 🛡️ رفع الملف إلى مجلد خاص داخل storage (وليس public)
        $filePath = $request->file('file')->store('invoices');

        // 🧾 إنشاء الفاتورة
        $invoice = Invoice::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'invoice_category_id' => $request->invoice_category_id,
            'invoice_date' => $request->invoice_date,
            'file_path' => $filePath,
            'user_id' => auth()->id(), // ربط الفاتورة بالمستخدم
        ]);

        return redirect()->route('invoices.success', $invoice->id);
    }

    public function create()
    {
        $categories = InvoiceCategory::all();
        return view('invoices.create', compact('categories'));
    }

    public function success($id)
    {
        $invoice = Invoice::findOrFail($id);

        // ✅ حماية الوصول للفاتورة
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بعرض هذه الفاتورة');
        }

        return view('invoices.success', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتحميل هذه الفاتورة');
        }

        if (!Storage::exists($invoice->file_path)) {
            abort(404, 'الملف غير موجود.');
        }

        return Storage::download($invoice->file_path, 'فاتورة.pdf');
    }

    public function view($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بعرض هذه الفاتورة');
        }

        if (!Storage::exists($invoice->file_path)) {
            abort(404, 'الملف غير موجود.');
        }

        $path = Storage::path($invoice->file_path);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice.pdf"',
        ]);
    }
}
