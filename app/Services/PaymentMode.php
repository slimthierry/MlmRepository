<?php

namespace App\Services;

use App\Models\PaymentMode;

class PaymentPlatformResolver
{
    protected $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = PaymentMode::all();
    }

    public function resolveService($paymentModeId)
    {
        $name = strtolower($this->paymentMode->firstWhere('id', $paymentModeId)->name);

        $service = config("services.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected platform is not in the configuration');

    }
}
