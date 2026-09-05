<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market\Order;
use App\Models\Market\Payment;
use App\Models\Market\ProductVariant;
use App\Models\Ticket\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = Payment::where('status', 'paid')->whereDate('created_at', today())->sum('amount');

        // $returnedOrders = Order::whereIn('order_status', [
        //     'not_checked',
        //     'awaiting_confirmation',
        //     'returned',
        // ])->count();

        $lowVariantsAvailable = ProductVariant::query()->with('warehouseVariants')->get()
            ->filter(function (ProductVariant $variant) {
                $availableStock = $variant->availableStock();

                return $availableStock > 0 && $availableStock <= 5;
            })->count();


        $confirmedOrders = Order::where('order_status', 'confirmed')->whereDate('created_at', today())->count();

        $openTickets = Ticket::where('status', 0)->count();

        ///////////////////////////////////////////////////////////////////////////////////
        //. گرفتین سفارش‌ های تاییدشده‌ ی ۳۰ روز اخیر
        $startDate = Carbon::today()->subDays(29);

        $rawSales = Order::where('order_status', 'confirmed')
            ->whereDate('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            });

        $chartLabels = [];
        $chartValues = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateKey = $date->format('Y-m-d');


            $chartLabels[] = $date->format('M d');


            if ($rawSales->has($dateKey)) {
                $chartValues[] = (float) $rawSales->get($dateKey)->sum('order_final_amount');
            } else {
                $chartValues[] = 0;
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////


        // سفارش ‌های تاییدشده از 6 ماه پیش تا امروز
        $startOfPeriod = Carbon::today()->subMonths(5)->startOfMonth();

        $rawMonthly = Order::query()
            ->where('order_status', 'confirmed')
            ->whereDate('created_at', '>=', $startOfPeriod)
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m');
            });

        // ساختن دیتای 6 ماه برای چارت (ماه‌ های بدون فروش = 0)
        $barLabels = [];
        $barValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::today()->subMonths($i);
            $monthKey = $date->format('Y-m');

            $barLabels[] = $date->format('M');

            // اگه اون ماه سفارشی داشت جمع مبالغش رو بذار وگرنه 0
            if ($rawMonthly->has($monthKey)) {
                $barValues[] = (float) $rawMonthly->get($monthKey)->sum('order_final_amount');
            } else {
                $barValues[] = 0;
            }
        }


        return view('admin.index', compact(
            'todayRevenue',
            'lowVariantsAvailable',
            'openTickets',
            'confirmedOrders',
            'chartLabels',
            'chartValues',
            'barLabels',
            'barValues',
        ));
    }
}
