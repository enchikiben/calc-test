<?php

namespace app\tests\unit\components;

use app\components\Calculator;

class CalculatorTest extends \Codeception\Test\Unit
{
    public static $examples = [
        '1' => [
            'normalize' => '1',
            'validate' => false,
            'result' => false,
        ],
        '0,0000002 ' => [
            'normalize' => '0.0000002',
            'validate' => false,
            'result' => false,
        ],
        // Сложение 2ух/3ёх чисел (A+B, A+B+C)
        '1+2' => [
            'normalize' => '1+2',
            'validate' => true,
            'result' => 3,
        ],
        '2 + 2 ' => [
            'normalize' => '2+2',
            'validate' => true,
            'result' => 4,
        ],
        '2 + -2 ' => [
            'normalize' => '2+-2',
            'validate' => true,
            'result' => 0,
        ],
        '2 +' => [
            'normalize' => '2+',
            'validate' => false,
            'result' => false,
        ],
        '2 +2+2' => [
            'normalize' => '2+2+2',
            'validate' => true,
            'result' => 6,
        ],
        '-2+2 +2' => [
            'normalize' => '-2+2+2',
            'validate' => true,
            'result' => 2,
        ],
        '2+2 +-2' => [
            'normalize' => '2+2+-2',
            'validate' => true,
            'result' => 2,
        ],
        '-2+-2 +-2' => [
            'normalize' => '-2+-2+-2',
            'validate' => true,
            'result' => -6,
        ],
        '-2-2 +-2' => [
            'normalize' => '-2-2+-2',
            'validate' => false,
            'result' => false,
        ],
        '-2-0,0000002 +-2' => [
            'normalize' => '-2-0.0000002+-2',
            'validate' => false,
            'result' => false,
        ],
        // Вычитание A-B
        '-2 - 2' => [
            'normalize' => '-2-2',
            'validate' => true,
            'result' => -4,
        ],
        // Умножение A*B
        '2 *' => [
            'normalize' => '2*',
            'validate' => false,
            'result' => false,
        ],
        '2 * -2' => [
            'normalize' => '2*-2',
            'validate' => true,
            'result' => -4,
        ],
        '-2 * - 2' => [
            'normalize' => '-2*-2',
            'validate' => true,
            'result' => 4,
        ],
        '1.2000 + 1.203' => [
            'normalize' => '1.2000+1.203',
            'validate' => true,
            'result' => 2.4030,
        ],
        '5650175242.508133742 + 308437806.831153821478770' => [
            'normalize' => '5650175242.508133742+308437806.831153821478770',
            'validate' => true,
            'result' => 5958613049.339287563478770,
        ],
    ];

    public function testNormalize()
    {
        foreach (self::$examples as $example => $test) {
            expect(Calculator::normalizeExample($example))->equals($test['normalize']);
        }
    }

    public function testValidate()
    {
        $calculator = new Calculator();
        foreach (self::$examples as $example => $test) {
            $calculator->example = $example;
            expect($calculator->validate())->equals($test['validate']);
        }
    }

    public function testCalculate()
    {
        $calculator = new Calculator();
        foreach (self::$examples as $example => $test) {
            $calculator->example = $example;
            expect($calculator->calculate())->equals($test['result']);
        }
    }
}
