<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Receipt.php';

use PHPUnit\Framework\TestCase;

use TDD\Receipt;

class ReceiptTest extends TestCase
{
    protected function setUp(): void
    {
        $this->receipt = new Receipt();
    }

    protected function tearDown(): void
    {
        unset($this->receipt);
    }
    /**
     * @dataProvider provideTotal
     */
    public function testTotal($items, $expected)
    {
        $coupon = null;
        $output = $this->receipt->total($items, $coupon);
        $this->assertEquals($expected, $output, 'When summing the total should equal to 15.');
    }

    public function provideTotal()
    {
        return [
            [[1, 2, 5, 8], 16],
            [[-1, 2, 5, 8], 14],
            [[1, 2, 8], 11],
        ];
    }

    public function testTotalAndCoupon()
    {
        $input = [0, 2, 5, 8];
        $coupon = 0.20;
        $output = $this->receipt->total($input, $coupon);
        $this->assertEquals(12, $output, 'When summing the total should equal to 12.');
    }

    public function testTotalWithException()
    {
        $input = [0, 2, 5, 8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $output = $this->receipt->total($input, $coupon);
    }
    /** @test */
    public function testPostTaxTotal()
    {
        $input = [1, 2, 5, 8];
        $tax = 0.20;
        $coupon = null;

        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['tax', 'total'])
            ->getMock();
        $receipt->expects($this->once())
            ->method('total')
            ->with($input, $coupon)
            ->will($this->returnValue(10.00));
        $receipt->expects($this->once())
            ->method('tax')
            ->with(10, $tax)
            ->will($this->returnValue(1.00));
        $result = $receipt->postTaxTotal([1, 2, 5, 8], 0.20, null);
        $this->assertEquals(11.00, $result);
    }

    /** @test */
    public function testTax()
    {
        $inputAmount = 10.00;
        $inputTax = 0.10;
        $output = $this->receipt->tax($inputAmount, $inputTax);
        $this->assertEquals(1.00, $output, 'The tax calculation amount should be 1.00');
    }
}
