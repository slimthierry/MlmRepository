<?php

namespace App\Services;


class Modules
{
    public static function getPaymentMethods($type = null)
    {

        if (!empty($payment_methods)) {
            return $payment_methods;
        }

        $list = [];

        $modules = new \stdClass();
        $modules->payment_methods = [];

        // Fire the event to get the list of payment methods

        event(new \App\Events\PaymentMethod($modules));

        foreach ($modules->payment_methods as $method) {
            if (!isset($method['name']) || !isset($method['code'])) {
                continue;
            }


            $list[] = $method;
        }

        foreach ($list as $method) {
            $payment_methods[$method['code']] = $method['name'];
        }


        return ($payment_methods) ? $payment_methods : [];
    }

}
