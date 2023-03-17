<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        $all_invoices = Invoice::count();
        $paid_invoices = Invoice::where('value_status', 1)->count();
        $partialy_paid_invoices = Invoice::where('value_status', 3)->count();
        $unpaid_invoices = Invoice::where('value_status', 2)->count();

        $paid_percentage = $paid_invoices / $all_invoices * 100;
        $unpaid_percentage = $unpaid_invoices / $all_invoices * 100;
        $partaialy_paid_percentage = $partialy_paid_invoices / $all_invoices * 100;

        // $chartjs = app()->chartjs
        // ->name('lineChartTest')
        // ->type('bar')
        // ->size(['width' => 400, 'height' => 200])
        // ->labels(['الفواتير المدفوعة جزئيا', 'الفواتير المدفوعة', 'الفواتير الغير مدفوعة'])
        // ->datasets([
        //     [
        //         "label" => "نسبة الفواتير",
        //         'backgroundColor' => "rgba(38, 185, 154, 0.31)",
        //         'borderColor' => "rgba(38, 185, 154, 0.7)",
        //         "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
        //         "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
        //         "pointHoverBackgroundColor" => "#fff",
        //         "pointHoverBorderColor" => "rgba(220,220,220,1)",
        //         'data' => [($partialy_paid_invoices / $all_invoices) * 100 , ($paid_invoices / $all_invoices * 100), ($unpaid_invoices / $all_invoices * 100)],
        //     ],
            // [
            //     "label" => "My Second dataset",
            //     'backgroundColor' => "rgba(38, 185, 154, 0.31)",
            //     'borderColor' => "rgba(38, 185, 154, 0.7)",
            //     "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
            //     "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
            //     "pointHoverBackgroundColor" => "#fff",
            //     "pointHoverBorderColor" => "rgba(220,220,220,1)",
            //     'data' => [12, 33, 44, 44, 55, 23, 40],
            // ]
        // ])
        // ->optionsRaw([
        //     'legend' => [
        //         'display' => true,
        //         'labels' => [
        //             'fontColor' => 'black',
        //             'fontFamily' => 'Cairo',
        //             'fontStyle' => 'bold',
        //             'fontSize' => 14
        //         ]
        //     ]
        // ]);

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير المدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$unpaid_percentage]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$paid_percentage]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$partaialy_paid_percentage]
                ],


            ])
            ->options([]);


        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [$unpaid_percentage, $paid_percentage, $partaialy_paid_percentage]
                ]
            ])
            ->options([]);




        return view('home', compact('chartjs', 'chartjs_2'));
    }
}
