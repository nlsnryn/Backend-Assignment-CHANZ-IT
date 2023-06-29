<?php

namespace App\Http\Controllers;

use SplStack;
use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Stringy\StaticStringy;

class ConversionController extends Controller
{
    public function index()
    {
        return view('conversion.index');
    }

    public function number(Request $request)
    {
        // Retrieve the flashed result data
        $result = $request->session()->get('result');

        if (!is_null($result) && array_key_exists('input', $result)) {
            return $this->currencyConversion($request);
        }
        return view('conversion.numberToWords');
    }
    public function words(Request $request)
    {
        // Retrieve the flashed result data
        $result = $request->session()->get('result');

        if (!is_null($result) && array_key_exists('input', $result)) {
            return $this->currencyConversion($request);
        }
        return view('conversion.wordsToNumber');
    }

    public function convertNumberToWords(Request $request)
    {
        $input = $request->input('input');

        function numberToWord($num = '')
        {
            $num    = (string) ((int) $num);

            if ((int) ($num) && ctype_digit($num)) {
                $words  = array();

                $num    = str_replace(array(',', ' '), '', trim($num));

                $list1  = array(
                    '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
                    'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
                    'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
                );

                $list2  = array(
                    '', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
                    'seventy', 'eighty', 'ninety', 'hundred'
                );

                $list3  = array(
                    '', 'thousand', 'million', 'billion', 'trillion',
                    'quadrillion', 'quintillion', 'sextillion', 'septillion',
                    'octillion', 'nonillion', 'decillion', 'undecillion',
                    'duodecillion', 'tredecillion', 'quattuordecillion',
                    'quindecillion', 'sexdecillion', 'septendecillion',
                    'octodecillion', 'novemdecillion', 'vigintillion'
                );

                $num_length = strlen($num);
                $levels = (int) (($num_length + 2) / 3);
                $max_length = $levels * 3;
                $num    = substr('00' . $num, -$max_length);
                $num_levels = str_split($num, 3);

                foreach ($num_levels as $num_part) {
                    $levels--;
                    $hundreds   = (int) ($num_part / 100);
                    $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : '') . '' : '');
                    $tens       = (int) ($num_part % 100);
                    $singles    = '';

                    if ($tens < 20) {
                        $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
                    } else {
                        $tens = (int) ($tens / 10);
                        $tens = ' ' . $list2[$tens] . ' ';
                        $singles = (int) ($num_part % 10);
                        $singles = ' ' . $list1[$singles] . ' ';
                    }
                    $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . '' : '');
                }
                $commas = count($words);
                if ($commas > 1) {
                    $commas = $commas - 1;
                }

                $words  = implode(', ', $words);

                $words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
                if ($commas) {
                    $words  = str_replace(',', ' and', $words);
                }

                return $words;
            } else if (!((int) $num)) {
                return 'Zero';
            }
            return '';
        }

        $result['input'] = $input;
        $result['output'] = numberToWord($input);
        // return view('conversion.numberToWords', ['result' => $result]);
        return Redirect::route('convert.number')->with('result', $result);
    }

    public function convertWordsToNumber(Request $request)
    {
        $input = $request->input('input');
        function wordsToNumber($data)
        {
            $data = strtr(
                $data,
                array(
                    'zero'      => '0',
                    'a'         => '1',
                    'one'       => '1',
                    'two'       => '2',
                    'three'     => '3',
                    'four'      => '4',
                    'five'      => '5',
                    'six'       => '6',
                    'seven'     => '7',
                    'eight'     => '8',
                    'nine'      => '9',
                    'ten'       => '10',
                    'eleven'    => '11',
                    'twelve'    => '12',
                    'thirteen'  => '13',
                    'fourteen'  => '14',
                    'fifteen'   => '15',
                    'sixteen'   => '16',
                    'seventeen' => '17',
                    'eighteen'  => '18',
                    'nineteen'  => '19',
                    'twenty'    => '20',
                    'thirty'    => '30',
                    'forty'     => '40',
                    'fourty'    => '40', // common misspelling
                    'fifty'     => '50',
                    'sixty'     => '60',
                    'seventy'   => '70',
                    'eighty'    => '80',
                    'ninety'    => '90',
                    'hundred'   => '100',
                    'thousand'  => '1000',
                    'million'   => '1000000',
                    'billion'   => '1000000000',
                    'and'       => '',
                )
            );

            // Correct spaceless input by inserting spaces between words
            $data = preg_replace('/([a-z])([A-Z])/s', '$1 $2', $data);

            // Remove spaces between numbers and words
            $data = preg_replace('/(\d)\s+([a-zA-Z])/s', '$1$2', $data);
            $data = preg_replace('/([a-zA-Z])\s+(\d)/s', '$1$2', $data);

            // Coerce all tokens to numbers
            $parts = array_map(
                function ($val) {
                    return floatval($val);
                },
                preg_split('/[\s-]+/', $data)
            );

            $stack = new SplStack; // Current work stack
            $sum   = 0; // Running total
            $last  = null;

            foreach ($parts as $part) {
                if (!$stack->isEmpty()) {
                    // We're part way through a phrase
                    if ($stack->top() > $part) {
                        if ($last >= 1000) {
                            $sum += $stack->pop();

                            $stack->push($part);
                        } else {
                            $stack->push($stack->pop() + $part);
                        }
                    } else {
                        $stack->push($stack->pop() * $part);
                    }
                } else {
                    $stack->push($part);
                }

                $last = $part;
            }

            return $sum + $stack->pop();
        }

        $result['input'] = $input;
        $result['output'] = wordsToNumber($input);
        // dd($result);
        // return view('conversion.wordsToNumber', ['result' => $result]);
        return Redirect::route('convert.words')->with('result', $result);
    }

    public function currencyConversion(Request $request)
    {

        $result = $request->session()->get('result');

        if (!is_null($result) && array_key_exists('input', $result)) {
            // Access the result data
            $input = $result['input'];
            $output = $result['output'];
            // dd($input);
        }

        if (is_string($result['output'])) {
            $amount = $input;
        } else {
            $amount = $output;
        }

        $apikey = '733e3b6219a4792427bd27f0';
        $fromCurrency = urlencode('PHP');
        $toCurrency = urlencode('USD');

        // Change the URL if you're using a different currency conversion API
        $url = "https://v6.exchangerate-api.com/v6/{$apikey}/pair/{$fromCurrency}/{$toCurrency}/{$amount}";

        $json = file_get_contents($url);
        $data = json_decode($json);

        $resultObject = new stdClass();
        $resultObject->input = $input;
        $resultObject->output = $output;
        $resultObject->converted = $data;
        // dd($resultObject->converted->conversion_result);

        if (is_string($result['output'])) {
            return view('conversion.numberToWords', ['result' => $resultObject]);
        } else {
            return view('conversion.wordsToNumber', ['result' => $resultObject]);
        }
    }
}
