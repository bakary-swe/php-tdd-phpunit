<?php

namespace TDD;

use BadMethodCallException;

class Receipt {

    public function total(array $items= [], $coupon)
    {
        if ($coupon > 1.00)  {
            throw new BadMethodCallException('Coupon must be less than 1.00');
        }
        $sum = array_sum($items);
        if(!is_null($coupon)) {
            return $sum - ($sum * $coupon);
        }
        return $sum;
    }

    public function Tax($amount, $taxRate)
    {
        return ($amount * $taxRate);
    }
    public function postTaxTotal(array $items, $taxRate, $coupon)
    {
        $subTotal = $this->total($items, $coupon);
        return $subTotal + $this->Tax($subTotal, $taxRate);
    }
}