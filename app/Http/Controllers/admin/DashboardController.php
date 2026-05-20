<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use App\Models\StockImport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CARD THỐNG KÊ LỚN
        |--------------------------------------------------------------------------
        */

        // Doanh thu hôm nay
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'paid')
            ->sum('total_price');

        // Đơn hàng hôm nay
        $todayOrders = Order::whereDate('created_at', today())
            ->count();

        // Doanh thu tháng
        $monthRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->sum('total_price');

        // Bàn đang phục vụ
        $servingTables = Table::where('status', 'using')
            ->count();

        $totalTables = Table::count();

        /*
        |--------------------------------------------------------------------------
        | CARD THỐNG KÊ NHỎ
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $totalUsers = User::where('role', 'staff')
            ->count();

        $paidOrders = Order::where('status', 'paid')
            ->count();

        $totalCategories = DB::table('categories')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | BIỂU ĐỒ DOANH THU 7 NGÀY
        |--------------------------------------------------------------------------
        */

        $revenueLabels = [];
        $revenueData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $revenueLabels[] = $date->format('d/m');

            $dailyRevenue = Order::whereDate(
                'created_at',
                $date->toDateString()
            )
                ->where('status', 'paid')
                ->sum('total_price');

            $revenueData[] = (float) $dailyRevenue;
        }

        /*
        |--------------------------------------------------------------------------
        | TRẠNG THÁI ĐƠN HÀNG
        |--------------------------------------------------------------------------
        */

        $pendingCount = Order::where('status', 'pending')
            ->count();

        $paidCount = Order::where('status', 'paid')
            ->count();

        $cancelledCount = Order::where('status', 'cancelled')
            ->count();

        $totalOrderStatus =
            $pendingCount +
            $paidCount +
            $cancelledCount;

        /*
        |--------------------------------------------------------------------------
        | TOP SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->join(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )
            ->select(
                'products.name',

                DB::raw(
                    'SUM(order_items.quantity) as total_sold'
                ),

                DB::raw(
                    'SUM(order_items.quantity * order_items.price) as total_revenue'
                )
            )
            ->groupBy(
                'products.id',
                'products.name'
            )
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ĐƠN GẦN ĐÂY
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
            'user',
            'table'
        ])
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SƠ ĐỒ BÀN
        |--------------------------------------------------------------------------
        */

        $tables = Table::orderBy('name')
            ->get();

        $emptyTables = Table::where('status', 'empty')
            ->count();

        $usingTables = Table::where('status', 'using')
            ->count();

        return view(
            'admin.dashboard.index',
            compact(
                'todayRevenue',
                'todayOrders',
                'monthRevenue',
                'servingTables',
                'totalTables',

                'totalProducts',
                'totalUsers',
                'paidOrders',
                'totalCategories',

                'revenueLabels',
                'revenueData',

                'pendingCount',
                'paidCount',
                'cancelledCount',
                'totalOrderStatus',

                'topProducts',

                'recentOrders',

                'tables',
                'emptyTables',
                'usingTables'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUẢN LÝ DOANH THU
    |--------------------------------------------------------------------------
    */

    public function finance(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | NGÀY ĐƯỢC CHỌN
        |--------------------------------------------------------------------------
        */

        $date = $request->date ?? now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | THEO NGÀY
        |--------------------------------------------------------------------------
        */

        $revenue = Order::whereDate('created_at', $date)
            ->where('status', 'paid')
            ->sum('total_price');

        $importCost = StockImport::whereDate(
            'created_at',
            $date
        )->sum('total_price');

        $profit = $revenue - $importCost;

        /*
        |--------------------------------------------------------------------------
        | TỔNG TOÀN THỜI GIAN
        |--------------------------------------------------------------------------
        */

        $totalRevenue = Order::where('status', 'paid')
            ->sum('total_price');

        $totalImportCost = StockImport::sum('total_price');

        $totalProfit =
            $totalRevenue - $totalImportCost;

        /*
        |--------------------------------------------------------------------------
        | HÔM NAY
        |--------------------------------------------------------------------------
        */

        $todayRevenue = Order::whereDate(
            'created_at',
            today()
        )
            ->where('status', 'paid')
            ->sum('total_price');

        $todayImport = StockImport::whereDate(
            'created_at',
            today()
        )->sum('total_price');

        $todayProfit =
            $todayRevenue - $todayImport;

        /*
        |--------------------------------------------------------------------------
        | THÁNG NÀY
        |--------------------------------------------------------------------------
        */

        $monthRevenue = Order::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->where('status', 'paid')
            ->sum('total_price');

        $monthImport = StockImport::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->sum('total_price');

        $monthProfit =
            $monthRevenue - $monthImport;

        /*
        |--------------------------------------------------------------------------
        | BIỂU ĐỒ 7 NGÀY
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];

        $chartRevenue = [];

        $chartImport = [];

        for ($i = 6; $i >= 0; $i--) {

            $day = Carbon::today()
                ->subDays($i);

            $chartLabels[] =
                $day->format('d/m');

            $dailyRevenue = Order::whereDate(
                'created_at',
                $day
            )
                ->where('status', 'paid')
                ->sum('total_price');

            $dailyImport = StockImport::whereDate(
                'created_at',
                $day
            )->sum('total_price');

            $chartRevenue[] =
                (float) $dailyRevenue;

            $chartImport[] =
                (float) $dailyImport;
        }

        /*
        |--------------------------------------------------------------------------
        | DANH SÁCH ĐƠN
        |--------------------------------------------------------------------------
        */

        $orders = Order::with([
            'table',
            'user'
        ])
            ->whereDate('created_at', $date)
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view(
            'admin.finance.index',
            compact(
                'date',

                'revenue',
                'importCost',
                'profit',

                'totalRevenue',
                'totalImportCost',
                'totalProfit',

                'todayRevenue',
                'todayImport',
                'todayProfit',

                'monthRevenue',
                'monthImport',
                'monthProfit',

                'chartLabels',
                'chartRevenue',
                'chartImport',

                'orders'
            )
        );
    }
}
