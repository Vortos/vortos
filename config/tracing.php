<?php
declare(strict_types=1);

use Vortos\Tracing\Config\TracingModule;
use Vortos\Tracing\Config\TracingSampler;
use Vortos\Tracing\DependencyInjection\VortosTracingConfig;

return static function (VortosTracingConfig $config): void {
    // dev: trace everything, prod: trace 10% by default
    // Override here as needed:
    //
    // $config->sampler(TracingSampler::AlwaysOn);               // trace everything
    // $config->sampler(TracingSampler::AlwaysOff);              // disable completely
    // $config->sampler(TracingSampler::Ratio, rate: 0.05);      // trace 5%
    //
    // Disable specific modules:
    // $config->disable(TracingModule::Cache);                    // cache too noisy
    // $config->disable(TracingModule::Auth, TracingModule::Cache);
};
