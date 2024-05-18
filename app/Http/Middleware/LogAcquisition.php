<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log;

class LogAcquisition
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // リクエストのログを取得
        $log = new Log();

        $log->path = $request->path();
        $log->method = $request->method();
        $log->ip_address = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->request_header = json_encode($request->header());
        $log->request_body = json_encode($request->all());

        $response = $next($request);

        // 認証済みユーザーがいる場合はログにユーザーIDを保存
        if (auth()->check()) {
            $log->user_id = auth()->id();
        }

        // レスポンスのステータスコードをログに保存
        $log->response_status = $response->getStatusCode();

        $log->save();

        return $response;
    }
}
