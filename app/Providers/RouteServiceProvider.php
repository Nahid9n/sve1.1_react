<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    public const ADMIN_HOME = '/admin';

    public const EMPLOYEE_HOME = '/employee-home';

    public const MANAGER_HOME = '/manager-home';

    public const CUSTOMER_HOME = '/customer-dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        // if (file_exists(base_path('vendor/laravel/framework/src/Illuminate/license.dat'))) {
        //     $file = fopen(base_path() . "/vendor/laravel/framework/src/Illuminate/license.dat", 'r+');
        //     $read = fgets($file);
        //     fclose($file);
        //     if ($read == str_replace('www.', '', $_SERVER['SERVER_NAME'])) {
        // give access
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
        //     } else {
        //         function getIPAddress()
        //         {
        //             if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //                 $ip = $_SERVER['HTTP_CLIENT_IP'];
        //             } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //                 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //             } else {
        //                 $ip = $_SERVER['REMOTE_ADDR'];
        //             }
        //             return $ip;
        //         }

        //         $ip = getIPAddress();
        //         if ($ip == '::1') {
        //             $ip = gethostname();
        //         }
        //         $client = new \GuzzleHttp\Client();
        //         $url = 'https://license.prodevsltd.com/activation/attempt/store';
        //         $url2 = 'http://' . $_SERVER['SERVER_NAME'];
        //         $form_params = [
        //             'ip' => $ip,
        //             'parent' => $read ?? null,
        //             'url' => $url2,
        //         ];
        //         $response = $client->post($url, ['form_params' => $form_params]);
        //         $response->getBody()->getContents();
        //         dd('This Product Is Pirated. Please Contact with ask@prodevsltd.com or www.prodevsltd.com');
        //     }
        // } else {
        //     try {
        //         $url = 'http://' . $_SERVER['SERVER_NAME'];
        //         $gate = "https://license.prodevsltd.com/activation/license/" . $url;

        //         $stream = curl_init();
        //         curl_setopt($stream, CURLOPT_URL, $gate);
        //         curl_setopt($stream, CURLOPT_HEADER, 0);
        //         curl_setopt($stream, CURLOPT_RETURNTRANSFER, 1);
        //         $output = curl_exec($stream);
        //         curl_close($stream);
        //         $output = str_replace('"', '', $output);
        //         if ($output == 'active') {
        //             $file = fopen(base_path() . "/vendor/laravel/framework/src/Illuminate/license.dat", 'w');
        //             fwrite($file, str_replace('www.', '', $_SERVER['SERVER_NAME']));
        //             fclose($file);
        //         } elseif ($output == 'inactive') {
        //             dd('This Product Is Not Activated. Please Contact with ask@prodevsltd.com or www.prodevsltd.com');
        //         } elseif ($output == 'not_found') {
        //             function getIPAddress()
        //             {
        //                 if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //                     $ip = $_SERVER['HTTP_CLIENT_IP'];
        //                 } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //                     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        //                 } else {
        //                     $ip = $_SERVER['REMOTE_ADDR'];
        //                 }
        //                 return $ip;
        //             }

        //             $parent = env('APP_NAME') . ', ' . env('APP_URL');

        //             $ip = getIPAddress();
        //             if ($ip == '::1') {
        //                 $ip = gethostname();
        //             }
        //             $client = new \GuzzleHttp\Client();
        //             $url = 'https://license.prodevsltd.com/activation/attempt/store';
        //             $url2 = 'http://' . $_SERVER['SERVER_NAME'];
        //             $form_params = [
        //                 'ip' => $ip,
        //                 'parent' => $parent,
        //                 'url' => $url2,
        //             ];
        //             $response = $client->post($url, ['form_params' => $form_params]);
        //             $response->getBody()->getContents();
        //             dd('This Product Is Pirated. Please Contact with ask@prodevsltd.com or www.prodevsltd.com');
        //         }
        //     } catch (\Exception $e) {
        //         dd($e);
        //     }

        // }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
