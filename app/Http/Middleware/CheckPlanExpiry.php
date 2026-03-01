<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlanExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $license_key = env('LICENSE_KEY');
        $baseUrl = env('ERP_URL');
        $cacheFile = base_path('vendor/laravel/framework/src/Illuminate/license.json');

        // Default response
        $planExpiredData = [
            'force_block' => false,
            'expire_date' => null,
            'invoice_no' => null,
            'website_status' => null,
            'support_phone' => env('APP_SUPPORT_PHONE'),
            'last_check' => null,
        ];

        // ---- Read cache file ----
        if (is_file($cacheFile)) {
            $json = file_get_contents($cacheFile);
            if ($json !== false && strlen($json) > 5) {
                $cached = json_decode($json, true);
                if (is_array($cached)) {
                    $planExpiredData = array_merge($planExpiredData, $cached);
                }
            }
        }

        // ---- Determine if today's API call was already done ----
        $today = date('Y-m-d');
        $needApiCall = false;

        if (empty($planExpiredData['last_check']) || $planExpiredData['last_check'] !== $today) {
            // Not called today → need new API call
            $needApiCall = true;
        }

        // ---- If API call is needed ----
        if ($needApiCall) {
            $url = "$baseUrl/check-website-validity/$license_key";

            try {
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 4,
                    CURLOPT_CONNECTTIMEOUT => 3,
                ]);

                $response = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpcode === 200 && ! empty($response)) {
                    $data = json_decode($response, true);

                    // merge fresh API data
                    $planExpiredData['force_block'] = $data['force_block'] ?? false;
                    $planExpiredData['expire_date'] = $data['expire_date'] ?? null;
                    $planExpiredData['invoice_no'] = $data['invoice_no'] ?? null;
                    $planExpiredData['website_status'] = $data['website_status'] ?? null;
                    $planExpiredData['support_phone'] = $data['support_phone'] ?? env('APP_SUPPORT_PHONE');
                }

            } catch (\Exception $e) {
                // Ignore and use old cache
            }

            // Mark today's check complete
            $planExpiredData['last_check'] = $today;

            // Update cache file
            file_put_contents($cacheFile, json_encode($planExpiredData));
        }

        // Share to blade
        view()->share('planExpiredData', $planExpiredData);

        return $next($request);
    }
}
