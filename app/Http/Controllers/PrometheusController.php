<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PrometheusService;

class PrometheusController extends Controller
{
    public function metrics(PrometheusService $prometheusService)
    {
        return response($prometheusService->metrics());
    }
}
