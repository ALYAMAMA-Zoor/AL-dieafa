<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من وجود لغة محددة في الجلسة أو قبول اللغة من المتصفح
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // محاولة اكتشاف اللغة من المتصفح
            $browserLanguage = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (in_array($browserLanguage, ['en', 'ar', 'nl'])) {
                App::setLocale($browserLanguage);
                Session::put('locale', $browserLanguage);
            } else {
                // اللغة الافتراضية
                App::setLocale('en');
                Session::put('locale', 'en');
            }
        }
        
        return $next($request);
    }
}