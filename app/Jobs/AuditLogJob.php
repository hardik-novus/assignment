<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AuditLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public $timeout = 120;

    public $maxExceptions = 5;

    private $request_endpoint;
    private $request_method;
    private $request_body;
    private $request_ip;
    private $request_user_agent;
    private $response_body;
    private $response_status_code;

    public function __construct($request_endpoint, $request_method, $request_body, $request_ip, $request_user_agent, $response_body, $response_status_code)
    {
        $this->request_endpoint = $request_endpoint;
        $this->request_method = $request_method;
        $this->request_body = $request_body;
        $this->request_ip = $request_ip;
        $this->request_user_agent = $request_user_agent;
        $this->response_body = $response_body;
        $this->response_status_code = $response_status_code;
    }

    public function handle()
    {
        // Save log
        $auditLog = new \App\Models\AuditLog();
        $auditLog->request_endpoint = $this->request_endpoint;
        $auditLog->request_method = $this->request_method;
        $auditLog->request_body = $this->request_body;
        $auditLog->request_ip = $this->request_ip;
        $auditLog->request_user_agent = $this->request_user_agent;
        $auditLog->response_body = $this->response_body;
        $auditLog->response_status_code = $this->response_status_code;
        $auditLog->save();
    }

    public function fail($exception = null)
    {
        Log::critical('Failed to save audit log', $exception);
    }
}
