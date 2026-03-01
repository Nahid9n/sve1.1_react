<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Models\Visit;

class VisitorController extends Controller
{
    // index
    public function index(Request $request)
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        // $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
        // dd($period);
        $weekly_visits = Visit::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        // dd($weekly_visits);

        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);

        $weekly_number_of_visits = [];
        $weekly_unique_visitors = [];
        $days = [];
        foreach ($period as $date) {
            $days[] = $date->format('l');
            if (array_key_exists($date->format('Y-m-d'), $weekly_visits->pluck('total', 'date')->toArray())) {
                $visits = $weekly_visits->pluck('total', 'date')->toArray();
                $count_visitors = Visit::whereDate('created_at', $date)
                    ->distinct('ip')
                    ->count('ip');
                $weekly_unique_visitors[] = $count_visitors;
                $weekly_number_of_visits[] = $visits[$date->format('Y-m-d')];
            } else {
                $weekly_unique_visitors[] = 0;
                $weekly_number_of_visits[] = 0;
            }
        }
        $weekly_data = [
            'weekly_visits' => $weekly_number_of_visits,
            'weekly_unique_visitors' => $weekly_unique_visitors,
            'days' => $days,
        ];

        // dd($weekly_data);

        if ($request->start_date && $request->end_date) {
            // dd($request->all());
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $dateRange = Carbon::parse($start)->format('d/m/Y').' - '.Carbon::parse($end)->format('d/m/Y');
            $uniqueVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [$start, $end])->select('ip')->groupBy('ip')->get()->count();
            $totalVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [$start, $end])->select('ip')->get()->count();
            $activeVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [$start, $end])->select('ip')->where('created_at', '>=', now()->subMinutes(2))->groupBy('ip')->get()->count();

        } else {
            $uniqueVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->select('ip')->groupBy('ip')->get()->count();
            $totalVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->select('ip')->get()->count();
            $activeVisitors = DB::table('shetabit_visits')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->select('ip')->where('created_at', '>=', now()->subMinutes(2))->groupBy('ip')->get()->count();
            $dateRange = Carbon::parse(now()->startOfMonth())->format('d/m/Y').' - '.Carbon::parse(now()->endOfMonth())->format('d/m/Y');
        }

        $top_urls = DB::table('shetabit_visits')
            ->select('url', 'ip', DB::raw('COUNT(url) as count'), DB::raw('COUNT(distinct(ip)) as total_ip'))
            ->groupBy('url', 'ip')
            ->orderBy('count', 'DESC')
            ->paginate(10);

        $devices = [];
        $user_agents = Visit::pluck('useragent');
        foreach ($user_agents as $user_agent) {
            $devices[] = $this->parseDeviceFromUserAgent($user_agent);
        }
        $devices = array_count_values($devices);
        // dd($devices);

        $visits_duration = DB::table('shetabit_visits')
            ->select('duration')
            ->get();

        // $data           = DB::table('shetabit_visits')->latest()->paginate(10);
        // $top_urls       = DB::table('shetabit_visits')->select('url', DB::raw('count(*) as total'))->groupBy('url')->orderBy('total', 'desc')->limit(10)->get();

        return view('backEnd.admin.visitor.index', compact('uniqueVisitors', 'totalVisitors', 'activeVisitors', 'dateRange', 'weekly_data', 'top_urls', 'devices', 'visits_duration'));
    }

    // visitorFilter
    public function visitorFilter(Request $request)
    {
        // dd($request->all());
        // $status = $request->status;

        $data = Visit::query();
        if ($request->status == 'unique' && $request->date_range) {
            $status = $request->status;
            $start = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[0]))->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[1]))->endOfDay()->format('Y-m-d H:i:s');
            $data = $data->whereBetween('created_at', [$start, $end])->select('ip')->groupBy('ip');

        } elseif ($request->status == 'total' && $request->date_range) {
            $status = $request->status;
            $start = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[0]))->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[1]))->endOfDay()->format('Y-m-d H:i:s');
            $data = $data->whereBetween('created_at', [$start, $end]);
        } elseif ($request->status == 'online' && $request->date_range) {
            $status = $request->status;
            $start = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[0]))->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::createFromFormat('d/m/Y', str_replace(' ', '', explode('-', $request->date_range)[1]))->endOfDay()->format('Y-m-d H:i:s');
            // $data  = DB::table('shetabit_visits')->whereBetween('created_at', [$start, $end])->select('ip')->where('created_at', '>=', now()->subMinutes(2))->groupBy('ip')->get();
            $data = $data->whereBetween('created_at', [$start, $end])->where('created_at', '>=', now()->subMinutes(2));
            // dd($data);
        }
        $data = $data->latest()->paginate(10);
        $data = $data->appends(
            [
                'status' => $request->status,
                'date_range' => $request->date_range,
            ]
        );

        // dd($data);
        return view('backEnd.admin.visitor.vistor-filter', compact('data', 'status'));

    }

    public function uniqueVisitorList(Request $request)
    {
        // dd($request->all());
        $data = Visit::where('ip', $request->ip)->paginate(10);
        // appends
        $data = $data->appends(
            [
                'ip' => $request->ip,
            ]
        );

        // dd($data);
        return view('backEnd.admin.visitor.unique-list', compact('data'));

    }

    public function delete($id)
    {
        DB::table('shetabit_visits')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Visitor deleted successfully');
    }

    protected function getUniqueVisitor($unique_visitors)
    {
        // dd($unique_visitors);
        $unique_ids = [];
        foreach ($unique_visitors as $id => $unique_visitor) {
            $unique_ids[] = $unique_visitor;
        }

        return $unique_ids;
    }

    protected function parseDeviceFromUserAgent($userAgent)
    {
        // Common device keywords
        $devices = [
            'iPhone' => 'iPhone',
            'iPad' => 'iPad',
            'Android' => 'Android Device',
            'Windows Phone' => 'Windows Phone',
            'Macintosh' => 'Mac',
            'Windows' => 'Windows PC',
            'Linux' => 'Linux PC',
        ];

        // Check for each device keyword in the User-Agent string
        foreach ($devices as $keyword => $deviceName) {
            if (stripos($userAgent, $keyword) !== false) {
                return $deviceName;
            }
        }

        // Default to "Unknown" if no device is matched
        return 'Unknown Device';
    }
}
