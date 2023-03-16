<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesReportsController extends Controller
{
    public function index() {

        return view('reports.invoices-reports');

    }

    public function search(Request $request){

        $radio = $request->radio;


        // في حالة البحث بنوع الفاتورة

        if ($radio == 1) {


            // في حالة عدم تحديد تاريخ
            if ($request->type && $request->start_at =='' && $request->end_at =='') {

               $invoices = Invoice::where('status', $request->type)->get();

               $type = $request->type;

               return view('reports.invoices-reports',compact('type'))->withDetails($invoices);
            //    return view('reports.invoices-reports',compact('type', 'invoices'));

            }

            // في حالة تحديد تاريخ استحقاق
            else {

              $start_at = date($request->start_at);

              $end_at = date($request->end_at);

              $type = $request->type;


              $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('status', $request->type)->get();
              return view('reports.invoices-reports',compact('type','start_at','end_at'))->withDetails($invoices);
            //   return view('reports.invoices-reports',compact('type','start_at','end_at','invoices'));

            }

        }

    //====================================================================

        // في البحث برقم الفاتورة
        else {

            $invoices = Invoice::where('invoice_number',$request->invoice_number)->get();
            return view('reports.invoices-reports')->withDetails($invoices);
            // return view('reports.invoices-reports', compact('invoices'));

        }

        }


}
