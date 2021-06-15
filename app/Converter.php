<?php
class Converter
{

    public static $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    public static $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

    //bangla to english number convert
    public static function bn2en($number)
    {
        return str_replace(self::$bn, self::$en, $number);
    }


    //english to bangla number convert
    public static function en2bn($number)
    {
        return str_replace(self::$en, self::$bn, $number);
    }

    //number to english word convert
    public static function en_word($number)
    {
        global $all_value, $single_supix;

        // explode the taken number so that detect decimal number
        $r = explode('.', $number);

        // take number without decimal
        $n = $r['0'];

        // convert this number into an array
        $stringto = array(); // this is array declaration
        $stringto = str_split($n);

        // count array value
        $c = count($stringto);

        // all value of number as string from 1 to 19

        $all_value = array(
            '1'  => 'one',
            '2'  => 'two',
            '3'  => 'three',
            '4'  => 'four',
            '5'  => 'five',
            '6'  => 'six',
            '7'  => 'seven',
            '8'  => 'eight',
            '9'  => 'nine',
            '01' => 'one',
            '02' => 'two',
            '03' => 'three',
            '04' => 'four',
            '05' => 'five',
            '06' => 'six',
            '07' => 'seven',
            '08' => 'eight',
            '09' => 'nine',
            '10' => 'ten',
            '11' => 'eleven',
            '12' => 'twelve',
            '13' => 'thirteen',
            '14' => 'fourteen',
            '15' => 'fifteen',
            '16' => 'sixteen',
            '17' => 'seventeen',
            '18' => 'eighteen',
            '19' => 'ninteen',
            '20' => 'twenty',
            '30' => 'thirty',
            '40' => 'fourty',
            '50' => 'fifty',
            '60' => 'sixty',
            '70' => 'seventy',
            '80' => 'eighty',
            '90' => 'ninety',
            
        );

        // this is for single supix of tens multifly number
        $single_supix = array(
            '2' => 'twenty',
            '3' => 'thirty',
            '4' => 'forty',
            '5' => 'fifty',
            '6' => 'sixty',
            '7' => 'seventy',
            '8' => 'eighty',
            '9' => 'ninety',
        );

        /////////////////////////////////////////
        ///// all function are declare here /////
        ////////////////////////////////////////



        ////////////////////////////////////////////////////////
        //// all logic are here.where all function calling /////
        ///////////////////////////////////////////////////////

	// if the numner is one digit value
        if ($c == 1) {
            return Converter::basic_number($n);
        }

	// two digit number
        else if ($c == 2) {
            return Converter::two_digit($n);
        }

	// three digit number
        else if ($c == 3) {
            return Converter::three_digit($n);
        }

	// four digit number
        else if ($c == 4) {
            return Converter::four_digit($n);
        }

	// five digit number
        else if ($c == 5) {
            return Converter::five_digit($n);
        }

	// six digit number
        else if ($c == 6) {
            return Converter::six_digit($n);
        }

	// seven digit number
        else if ($c == 7) {
            return Converter::seven_digit($n);
        }

	// eight digit number
        else if ($c == 8) {
            return Converter::eight_digit($n);
        }

	// nine digit number
        else if ($c == 9) {
            return Converter::nine_digit($n);
        }

	// ten digit number
        else if ($c == 10) {
            return Converter::ten_digit($n);
        }

    }

	//////////////////////////////////////
	    /// end all function calling logic ///
	    /////////////////////////////////////

	/////////////////////////////////////////////
    // end of translation into english in word //
    /////////////////////////////////////////////

	//////////////////////////////////////////
    // bangla translation function         //
    /////////////////////////////////////////

    // one digit number
        public static function basic_number($num)
        {
            global $all_value;

            return $all_value[$num];
        }

// two digit number function
        public static function two_digit($num)
        {
            global $single_supix, $all_value;

            if (array_key_exists($num, $all_value)) {
                return $result = $all_value[$num];
            } else {

                // make array by this number
                $numToarray = str_split($num);

                // first digit
                $first_digit = $numToarray['0'];
                $prepix      = $single_supix[$first_digit];

                // second digit
                $second_digit = $numToarray['1'];
                $supix        = $all_value[$second_digit];

                return $numToString = $prepix . ' ' . $supix;
            }
        }

// this is three digit number conversion

        public static function three_digit($num)
        {
            global $single_supix, $all_value, $bnValue, $enValue;
            $numToarray = str_split($num);

            if ($numToarray['0'] == 0) {
                $two_digit = $numToarray['1'] . $numToarray['2'];

                // function calling two_digit
                return $result = Converter::two_digit($two_digit);
            } else {

                // this is hundred valued number supix
                $supix = 'hundred';

                // first digit
                $first_digit = $numToarray['0'];
                $first_value = $all_value[$first_digit];

                // rest two digit
                $two_digit = $numToarray['1'] . $numToarray['2'];

                // call two digit function
                $r = Converter::two_digit($two_digit);

                // return all value
                return $result = $first_value . ' ' . $supix . ' ' . $r;
            }
        }

// this is four digit number conversion

        public static function four_digit($num)
        {
            global $single_supix, $all_value;

            // this is thousand value number supix
            $supix      = 'thousand';
            $numToarray = str_split($num);

            $f_r = '';

            if ($numToarray['0'] == 0) {
                $num                 = $numToarray['1'] . $numToarray['2'] . $numToarray['3'];
                return $forth_result = Converter::three_digit($num);
            } else {

                // first digit
                $fouth_digit  = $numToarray['0'];
                $fourth_value = $all_value[$fouth_digit];

                // rest three digit
                $three_digit = $numToarray['1'] . $numToarray['2'] . $numToarray['3'];

                // call three digit function
                if ($three_digit != '000') {
                    $f_r = Converter::three_digit($three_digit);
                }
                // return all value
                return $forth_result = $fourth_value . ' ' . $supix . ' ' . $f_r;
            }

        }

// this is five digit number conversion

        public static function five_digit($num)
        {
            $supix = 'thousand';

            $numToarray = str_split($num);

            // if first digit is zero
            if ($numToarray['0'] == 0) {

                //
                $num                    = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'];
                return $fifthConversion = Converter::four_digit($num);
            } else {

                // first two digit
                $firstTwoDigit = $numToarray['0'] . $numToarray['1'];

                // call two digit number function
                $firstPart = Converter::two_digit($firstTwoDigit);

                // last three digit
                $lastThreeDigit = $numToarray['2'] . $numToarray['3'] . $numToarray['4'];

                // call three digit function
                // three digit but not zero
                if ($lastThreeDigit != 0) {
                    $lastPart = Converter::three_digit($lastThreeDigit);
                }

                return $fifthConversion = $firstPart . ' ' . $supix . ' ' . $lastPart;
            }
        }

        // this function is for six digit value
        public static function six_digit($num)
        {
            $numToarray = str_split($num);
            $supix      = 'lac';

            // if first value is zero

            if ($numToarray['0'] == 0) {
                $num           = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'];
                return $result = Converter::five_digit($num);
            } else {
                $lakh = Converter::basic_number($numToarray['0']);

                // five digit number
                $num = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'];
                $r   = Converter::five_digit($num);

                return $result = $lakh . ' ' . $supix . ' ' . $r;
            }

        }

        // seven digit function

        public static function seven_digit($num)
        {
            $numToarray = str_split($num);
            $supix      = 'lac';

            // first digit
            $firstDigit = $numToarray['0'];

            if ($firstDigit == 0) {

                // rest six digit
                $lastSixDigit = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'];

                // calling six digit function
                return $result = Converter::six_digit($lastSixDigit);
            } else {

                // first two digit
                $firstTwoDigit = $numToarray['0'] . $numToarray['1'];

                // first two digit result
                $firstPart = Converter::two_digit($firstTwoDigit);

                // last five digit
                $lastFiveDigit = $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'];

                // five digit function calling
                $lastPart = Converter::five_digit($lastFiveDigit);

                // final result
                return $result = $firstPart . ' ' . $supix . ' ' . $lastPart;
            }

        }

        // eight digit function
        public static function eight_digit($num)
        {

            // convert this into an array
            $numToarray = str_split($num);

            // supix of this number
            $supix = 'crore';

            if ($numToarray['0'] == 0) {
                $seven_digit = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'] . $numToarray['7'];

                return $result = Converter::seven_digit($seven_digit);
            } else {
                $firstDigit = $numToarray['0'];

                // send this value into one number function
                $firstPart = Converter::basic_number($firstDigit);

                // rest seven digit
                $seven_digit = $numToarray['1'] . $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'] . $numToarray['7'];

                // send this into seven digit function
                $lastPart = Converter::seven_digit($seven_digit);

                // final result
                return $result = $firstPart . ' ' . $supix . ' ' . $lastPart;

            }

        }

        // nine digit function
        public static function nine_digit($num)
        {

            // set supix for this number
            $supix = 'crore';

            // convert this into an array
            $numToarray = str_split($num);

            // take first two digit
            $firstTwoDigit = $numToarray['0'] . $numToarray['1'];

            // rest seven digit
            $sevenDigit = $numToarray['2'] . $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'] . $numToarray['7'] . $numToarray['8'];

            // test if first two digit is zero
            if ($firstTwoDigit == 0) {

                // send this seven digit function
                return $result = Converter::seven_digit($sevenDigit);
            } else {

                // send first two digit into two_digit function
                $firstPart = Converter::two_digit($firstTwoDigit);

                // call seven digit function
                $lastPart = Converter::seven_digit($sevenDigit);

                return $result = $firstPart . ' ' . $supix . ' ' . $lastPart;
            }

        }

        // ten digit function
        public static function ten_digit($num)
        {

            // supix of this number
            $supix = 'crore';

            // convert the value into an array
            $numToarray = str_split($num);

            // take first three digit
            $firstThreeDigit = $numToarray['0'] . $numToarray['1'] . $numToarray['2'];

            // rest seven digit
            $sevenDigit = $numToarray['3'] . $numToarray['4'] . $numToarray['5'] . $numToarray['6'] . $numToarray['7'] . $numToarray['8'] . $numToarray['9'];

            if ($firstThreeDigit == 0) {
                // send this number into seven digit function
                return $result = Converter::seven_digit($sevenDigit);
            } else {
                // first three digit function
                $firstPart = Converter::three_digit($firstThreeDigit);

                // rest seven digit function
                $lastPart = Converter::seven_digit($sevenDigit);

                // final result
                return $result = $firstPart . ' ' . $supix . ' ' . $lastPart;
            }
        }


    //number to bangla word convert
    public static function bn_word($number)
    {

	// function calling of english conversion
        $enResult = self::en_word($number);
        //$enResult=$bnConvert;

	// bangla word key array from one to ninety nine.//
        $bn = array(
            'one'           => 'এক',
            'two'           => 'দুই',
            'three'         => 'তিন',
            'four'          => 'চার',
            'five'          => 'পাঁচ',
            'six'           => 'ছয়',
            'seven'         => 'সাত',
            'eight'         => 'আট',
            'nine'          => 'নয়',
            'ten'           => 'দশ',
            'eleven'        => 'এগার',
            'twelve'        => 'বার',
            'thirteen'      => 'তের',
            'fourteen'      => 'চৌদ্দ',
            'fifteen'       => 'পনের',
            'sixteen'       => 'ষোল',
            'seventeen'     => 'সতের',
            'eighteen'      => 'আঠার',
            'nineteen'      => 'ঊনিশ',
            'twenty'        => 'বিশ',
            'twenty one'    => 'একুশ',
            'twenty two'    => 'বাইশ',
            'twenty three'  => 'তেইশ',
            'twenty four'   => 'চব্বিশ',
            'twenty five'   => 'পঁচিশ',
            'twenty six'    => 'ছাব্বিশ',
            'twenty seven'  => 'সাতাশ',
            'twenty eight'  => 'আঠাশ',
            'twenty nine'   => 'ঊনত্রিশ',
            'thirty'        => 'ত্রিশ',
            'thirty one'    => 'একত্রিশ',
            'thirty two'    => 'বত্রিশ',
            'thirty three'  => 'তেত্রিশ',
            'thirty four'   => 'চৌত্রিশ',
            'thirty five'   => 'পঁয়ত্রিশ',
            'thirty six'    => 'ছত্রিশ',
            'thirty seven'  => 'সাঁইত্রিশ',
            'thirty eight'  => 'আটত্রিশ',
            'thirty nine'   => 'ঊনচল্লিশ',
            'forty'         => 'চল্লিশ',
            'forty one'     => 'একচল্লিশ',
            'forty two'     => 'বিয়াল্লিশ',
            'forty three'   => 'তেতাল্লিশ',
            'forty four'    => 'চুয়াল্লিশ',
            'forty five'    => 'পঁয়তাল্লিশ',
            'forty six'     => 'ছেচল্লিশ',
            'forty seven'   => 'সাতচল্লিশ',
            'forty eight'   => 'আটচল্লিশ',
            'forty nine'    => 'ঊনপঞ্চাশ',
            'fifty'         => 'পঞ্চাশ',
            'fifty one'     => 'একান্ন',
            'fifty two'     => 'বায়ান্ন',
            'fifty three'   => 'তিপ্পান্ন',
            'fifty four'    => 'চুয়ান্ন',
            'fifty five'    => 'পঞ্চান্ন',
            'fifty six'     => 'ছাপ্পান্ন',
            'fifty seven'   => 'সাতান্ন',
            'fifty eight'   => 'আটান্ন',
            'fifty nine'    => 'ঊনষাট',
            'sixty'         => 'ষাট',
            'sixty one'     => 'একষট্টি',
            'sixty two'     => 'বাষট্টি',
            'sixty three'   => 'তেষট্টি',
            'sixty four'    => 'চৌষট্টি',
            'sixty five'    => 'পঁয়ষট্টি',
            'sixty six'     => 'ছেষট্টি',
            'sixty seven'   => 'সাতষট্টি',
            'sixty eight'   => 'আটষট্টি',
            'sixty nine'    => 'ঊনসত্তর',
            'seventy'       => 'সত্তর',
            'seventy one'   => 'একাত্তর',
            'seventy two'   => 'বাহাত্তর',
            'seventy three' => 'তিয়াত্তর',
            'seventy four'  => 'চুয়াত্তর',
            'seventy five'  => 'পঁচাত্তর',
            'seventy six'   => 'ছিয়াত্তর',
            'seventy seven' => 'সাতাত্তর',
            'seventy eight' => 'আটাত্তর',
            'seventy nine'  => 'ঊনআশি',
            'eighty'        => 'আশি',
            'eighty one'    => 'একাশি',
            'eighty two'    => 'বিরাশি',
            'eighty three'  => 'তিরাশি',
            'eighty four'   => 'চুরাশি',
            'eighty five'   => 'পঁচাশি',
            'eighty six'    => 'ছিয়াশি',
            'eighty seven'  => 'সাতাশি',
            'eighty eight'  => 'আটাশি',
            'eighty nine'   => 'ঊননব্বই',
            'ninety'        => 'নব্বই',
            'ninety one'    => 'একানব্বই',
            'ninety two'    => 'বিরানব্বই',
            'ninety three'  => 'তিরানব্বই',
            'ninety four'   => 'চুরানব্বই',
            'ninety five'   => 'পঁচানব্বই',
            'ninety six'    => 'ছিয়ানব্বই',
            'ninety seven'  => 'সাতানব্বই',
            'ninety eight'  => 'আটানব্বই',
            'ninety nine'   => 'নিরানব্বই',
            'hundred'       => ' শত ',
            'thousand'      => ' হাজার ',
            'lac'           => ' লক্ষ ',
            'crore'         => ' কোটি ',
        );

		// end of all bangla numbering //

		// single digit array
        $singleDigit = array('one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
		// tens supix array
        $tens_supix = array('twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');

		// explode the in word result into an array
        $enArray = explode(' ', $enResult);

		// this is for count total array value of english result
        $enCount = count($enArray);

        $bnValue = '';
        $enValue = '';
		// translate english result array into bangla
        for ($i = 0; $i < $enCount; $i++) {
            $enValue = $enArray[$i];

            // test this is single supix or not???
            if (in_array($enValue, $tens_supix)) {

                // increment array index if there have any tens supix //
                $i        = $i + 1;
                $enValue1 = $enArray[$i];
                if (in_array($enValue1, $singleDigit)) {
                    // test if this value have in tens array??

                    // combine these two digit english value into one
                    $value = $enValue . ' ' . $enValue1;
                    $bnValue .= ' ' . $bn[$value];
                } else {

                    // convert this value into bangla
                    $bnValue .= $bn[$enValue];

                }
            }
            // if no tens value
            else {
                $bnValue .= ' ' . $bn[$enValue];
            }
        }
        echo $bnValue; // show the bangla output
    }

}
