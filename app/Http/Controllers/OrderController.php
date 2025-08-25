<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar order dengan pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $orders = Order::with(['orderItems.product', 'kasir'])
            ->whereDate('transaction_time', Carbon::today())
            ->orderBy('transaction_time', 'desc')
            ->paginate(10);

        return view('pages.orders.index', compact('orders'));
    }

    public function filter(Request $request)
    {
        $query = Order::with(['orderItems.product', 'kasir']);

        if ($request->start_date) {
            $query->whereDate('transaction_time', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('transaction_time', '<=', $request->end_date);
        }

        $orders = $query->orderBy('transaction_time', 'desc')->paginate(10);

        return view('pages.orders.index', compact('orders'));
    }

    public function print(Request $request)
    {
        $query = Order::with(['orderItems.product', 'kasir']);

        if ($request->start_date) {
            $query->whereDate('transaction_time', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('transaction_time', '<=', $request->end_date);
        }

        $orders = $query->orderBy('transaction_time', 'desc')->get();

        $pdf = PDF::loadView('pages.orders.print', compact('orders'))
            ->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-orders.pdf');
    }
    public function show($id)
    {
        $order = Order::with(['kasir', 'orderItems.product'])->findOrFail($id);
        return view('pages.orders.view', compact('order'));
    }
}
