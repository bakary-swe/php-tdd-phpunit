<?php

namespace TDD;

use BadMethodCallException;

class Receipt {

    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }

    public function subtotal(array $items= [], $coupon)
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

    public function Tax($amount)
    {
        return $this->formatter->currencyAmt($amount * $this->tax);
    }

    public function postTaxTotal(array $items, $coupon)
    {
        $subTotal = $this->subtotal($items, $coupon);
        return $subTotal + $this->Tax($subTotal);
    }
}