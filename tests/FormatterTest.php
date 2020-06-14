<?php

namespace TDD\Test;

require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Formatter.php';

use PHPUnit\Framework\TestCase;
use TDD\Formatter;

/**
 * FormatterTest
 * @group group
 */
class FormatterTest extends TestCase
{
    protected function setUp(): void
    {
        $this->formatter = new Formatter();
    }

    protected function tearDown(): void
    {
        unset($this->formatter);
    }

    /**
     * @dataProvider provideCurrencyAmt
     */
    public function testCurrencyAmnt($input, $expected, $msg)
    {
        $this->assertSame($expected, $this->formatter->currencyAmt($input), $msg);
    }

    public function provideCurrencyAmt()
    {
        return [
            [1, 1.00, '1 should be transformed into 1.00'],
            [1.1, 1.10, '1.1 should be transformed into 1.10'],
            [1.11, 1.11, '1.11 should stay 1.11'],
            [1.111, 1.11, '1.111 should be transformed into 1.11']
        ];
    }
}
