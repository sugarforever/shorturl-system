<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\Redis;

class PrometheusService
{

    private $collectorRegistry;

    const NAMESPACE = "short_urls";

    public function __construct()
    {
        Log::debug("Constructing");
        $this->collectorRegistry = CollectorRegistry::getDefault();
    }

    public function incrementViews($token, $count)
    {
        $counter = $this->collectorRegistry
            ->getOrRegisterCounter(self::NAMESPACE, "short_url_views", 'Short URL Views', ["type"]);
        $counter->incBy($count, [$token]);
    }

    public function metrics()
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->collectorRegistry->getMetricFamilySamples());
        header('Content-type: ' . RenderTextFormat::MIME_TYPE);
        return $result;
    }
}
