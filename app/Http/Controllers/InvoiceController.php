<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\Invoice_Details;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice_Attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();

        return view('invoices.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoice::latest()->first()->id;
        Invoice_Details::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section,
            'status' => 'غير مدفوعة',
            'status_value' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoice_Attachments;
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        session()->flash('add', 'تم اضافة الفاتورة بنجاح');
        return redirect(route('invoices.index'));




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $sections = Section::all();

        return view('invoices.edit', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }


    // public function destroy(Invoice $invoice)
    public function destroy(Request $request)
    {

        $id = $request->invoice_id;
        $invoice = invoice::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();
        // $attachment = $invoice->invoiceAttachments;

        if (!empty($Details->invoice_number)) {

            // Storage::disk('public_uploads')->delete($Details->invoice_number . '/' . $Details->file_name);
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }

        $invoice->forceDelete();

        session()->flash('delete_invoice');

        return redirect(route('invoices.index'));




    }

    public function getProduct($id) {

        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);

    }


    // Edit payment status

    public function editPaymentStatus(Invoice $invoice) {

        $sections = Section::all();

        return view('invoices.edit_payment_status', compact('invoice', 'sections'));

    }

    public function updatePaymentStatus(Request $request, Invoice $invoice) {

        if ($request->status === 'مدفوعة') {

            $invoice->update([
                'value_status' => 1,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);

            Invoice_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'status_value' => 1,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoice->update([
                'value_status' => 3,
                'status' => $request->status,
                'payment_date' => $request->payment_date,
            ]);
            invoice_Details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->section,
                'status' => $request->status,
                'status_value' => 3,
                'note' => $request->note,
                'payment_date' => $request->payment_date,
                'user' => (Auth::user()->name),
            ]);
        }


        session()->flash('Status_Update');
        return redirect(route('invoices.index'));

    }


    // paid invoices
    public function paidInvoices() {

        $invoices = Invoice::where('value_status', 1)->get();

        return view('invoices.paidInvoices', compact('invoices'));

    }

    //unpaid invoices
    public function unpaidInvoices() {

        $invoices = Invoice::where('value_status', 2)->get();

        return view('invoices.unpaidInvoices', compact('invoices'));

    }

    //partially paid invoices
    public function partiallyPaidInvoices() {

        $invoices = Invoice::where('value_status', 3)->get();

        return view('invoices.partiallyPaidInvoices', compact('invoices'));

    }

    // print invoice
    public function printInvoice(Invoice $invoice) {

        return view('invoices.printInvoice', compact('invoice'));


    }

    //export invoice excel sheet
    public function export()
    {
        return Excel::download(new InvoicesExport, 'قائمة الفواتير.xlsx');
    }


}
