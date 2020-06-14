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
        $this->formatter = $this->getMockBuilder('TDD\Formatter')
            ->setMethods(['currencyAmt'])
            ->getMock();
        $this->formatter->expects($this->any())
            ->method('currencyAmt')
            ->with($this->anything())
            ->will($this->returnArgument(0));
        $this->receipt = new Receipt($this->formatter);
    }

    protected function tearDown(): void
    {
        unset($this->receipt);
    }
    /**
     * @dataProvider provideSubTotal
     */
    public function testSubTotal($items, $expected)
    {
        $coupon = null;
        $output = $this->receipt->subtotal($items, $coupon);
        $this->assertEquals($expected, $output, 'When summing the total should equal to 15.');
    }

    public function provideSubTotal()
    {
        return [
            [[1, 2, 5, 8], 16],
            [[-1, 2, 5, 8], 14],
            [[1, 2, 8], 11],
        ];
    }

    public function testSubTotalAndCoupon()
    {
        $input = [0, 2, 5, 8];
        $coupon = 0.20;
        $output = $this->receipt->subtotal($input, $coupon);
        $this->assertEquals(12, $output, 'When summing the total should equal to 12.');
    }

    public function testTotalWithException()
    {
        $input = [0, 2, 5, 8];
        $coupon = 1.20;
        $this->expectException('BadMethodCallException');
        $output = $this->receipt->subtotal($input, $coupon);
    }
    /** @test */
    public function testPostTaxTotal()
    {
        $input = [1, 2, 5, 8];
        $tax = 0.20;
        $coupon = null;

        $receipt = $this->getMockBuilder('TDD\Receipt')
            ->setMethods(['Tax', 'subtotal'])
            ->setConstructorArgs([$this->formatter])
            ->getMock();
        $receipt->expects($this->once())
            ->method('subtotal')
            ->with($input, $coupon)
            ->will($this->returnValue(10.00));
        $receipt->expects($this->once())
            ->method('tax')
            ->with(10)
            ->will($this->returnValue(1.00));
        $result = $receipt->postTaxTotal([1, 2, 5, 8], null);
        $this->assertEquals(11.00, $result);
    }
    /** @test */
    public function testTax()
    {
        $inputAmount = 10.00;
        $this->receipt->tax = 0.10;
        $output = $this->receipt->tax($inputAmount);
        $this->assertEquals(1.00, $output, 'The tax calculation amount should be 1.00');
    }
}
