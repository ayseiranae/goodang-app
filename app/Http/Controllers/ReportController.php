<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    public function index()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        $data = $this->getReportData($startDate, $endDate);

        return view('reports.index', $data);
    }

    public function filter(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'type' => 'nullable|in:all,in,out'
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $type = $request->type ?? 'all';

            $query = DB::table('activities')
                ->join('items', 'activities.item_id', '=', 'items.id')
                ->join('users', 'activities.user_id', '=', 'users.id')
                ->whereBetween('activities.created_at', [$startDate, $endDate])
                ->select(
                    'activities.*',
                    'items.name as item_name',
                    'users.name as user_name'
                );

            if ($type !== 'all') {
                $query->where('activities.type', $type);
            }

            $transactions = $query->orderBy('activities.created_at', 'desc')
                ->limit(100)
                ->get();

            $stockIn = DB::table('activities')
                ->where('type', 'in')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $stockOut = DB::table('activities')
                ->where('type', 'out')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $totalTransactions = $transactions->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'statistics' => [
                        'stockIn' => $stockIn ?? 0,
                        'stockOut' => $stockOut ?? 0,
                        'netChange' => ($stockIn ?? 0) - ($stockOut ?? 0),
                        'totalTransactions' => $totalTransactions
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportPDF(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'type' => 'nullable|in:all,in,out'
            ]);

            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $type = $request->type ?? 'all';

            $data = $this->getReportData($startDate, $endDate, $type);
            $data['startDate'] = $startDate->format('d M Y');
            $data['endDate'] = $endDate->format('d M Y');
            $data['generatedAt'] = now()->format('d M Y H:i');

            $pdf = Pdf::loadView('reports.pdf', $data);

            $filename = 'Laporan_GOODANG_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    public function getStatistics(Request $request)
    {
        try {
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfDay();

            $stockIn = DB::table('activities')
                ->where('type', 'in')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            $stockOut = DB::table('activities')
                ->where('type', 'out')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity');

            return response()->json([
                'success' => true,
                'data' => [
                    'stockIn' => $stockIn ?? 0,
                    'stockOut' => $stockOut ?? 0,
                    'netChange' => ($stockIn ?? 0) - ($stockOut ?? 0)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getReportData($startDate, $endDate, $type = 'all')
    {
        $query = DB::table('activities')
            ->join('items', 'activities.item_id', '=', 'items.id')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->whereBetween('activities.created_at', [$startDate, $endDate])
            ->select(
                'activities.*',
                'items.name as item_name',
                'users.name as user_name'
            );

        if ($type !== 'all') {
            $query->where('activities.type', $type);
        }

        $transactions = $query->orderBy('activities.created_at', 'desc')->get();

        $stockIn = DB::table('activities')
            ->where('type', 'in')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        $stockOut = DB::table('activities')
            ->where('type', 'out')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        return [
            'transactions' => $transactions,
            'totalStockIn' => $stockIn ?? 0,
            'totalStockOut' => $stockOut ?? 0,
            'netChange' => ($stockIn ?? 0) - ($stockOut ?? 0),
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }
}
