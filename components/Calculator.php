<?php


namespace app\components;


class Calculator
{
    private $regExpNumber = "(?<!\d)-?\d*[.,]?\d+";

    public $example = null;

    /**
     * @return string
     */
    public static function normalizeExample($example)
    {
        return str_replace([",", " "], ['.', ""], $example);
    }

    /**
     * @return boolean
     */
    public function validate()
    {
        $validate = false;
        $example = self::normalizeExample($this->example);

        // check 1+2 1*2 1-2
        $regExp2x = "/^{$this->regExpNumber}[+\-*]{$this->regExpNumber}$/m";
        preg_match_all($regExp2x, $example, $matches2x, PREG_SET_ORDER, 0);

        if (empty($matches2x)) {
            // check 1+2+2
            $regExp3x = "/^{$this->regExpNumber}[+]{$this->regExpNumber}[+]{$this->regExpNumber}$/m";
            preg_match_all($regExp3x, $example, $matches3x, PREG_SET_ORDER, 0);
            $validate = !empty($matches3x);
        } else {
            $validate = true;
        }

        return $validate;
    }


    /**
     * @param bool $validate
     * @return bool | string
     */
    public function calculate($validate = true)
    {
        if ($validate && !$this->validate()) {
            return false;
        }

        $result = false;
        $example = self::normalizeExample($this->example);

        $regExp = "/{$this->regExpNumber}/m";
        preg_match_all($regExp, $example, $matches, PREG_PATTERN_ORDER, 0);

        if (!empty($matches) && isset($matches[0]) && is_array($matches[0])) {
            $numbers = [];
            foreach ($matches[0] as $num) {
                $numbers[] = trim($num);
            }
            $example = preg_replace($regExp, "", $example);

            $result = $numbers[0];
            $signs = str_split($example);
            foreach ($signs as $key => $sign) {
                $num = $numbers[$key + 1];
                $scale = max($this->numberLengthAfterPoint($result),$this->numberLengthAfterPoint($num));
                switch ($sign) {
                    case "+":
                        $result = bcadd($result, $num, $scale);
                        break;
                    case "-":
                        $result = bcsub($result, $num, $scale);
                        break;
                    case "*":
                        $result = bcmul($result, $num, $scale);
                        break;
                }
            }
        }


        return $result;
    }

    /**
     * @param $num
     * @return int
     */
    private function numberLengthAfterPoint($num)
    {
        $length = 0;
        $x = explode('.', strval($num));

        if (sizeof($x) > 1) {
            $length = strlen($x[1]);
        }

        return $length;
    }
}