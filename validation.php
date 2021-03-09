<?php
    function validateString($data){
        $REGEX = "|[^-A-Za-z0-9+/=]|=[^=]|={3,}$";
        if(is_string($data)) return true;
        else return false;
    }

    function checkIntegerRange($int, $min, $max)
    {
        if (is_string($int) && !ctype_digit($int)) {
            return false; // contains non digit characters
        }
        if (!is_int((int) $int)) {
            return false; // other non-integer value or exceeds PHP_MAX_INT
        }
        return ($int >= $min && $int <= $max);
    }

    function checkDecimalRange($decimal, $min, $max){
        if (is_string($decimal) && !ctype_digit($decimal)) {
            return false; // contains non digit characters
        }

        if(!is_double( (double) $decimal)){
            return false;
        }
        return ($decimal >= $min && $decimal <= $max);
    }

    function failValidation($reason){
        echo "\n Error: the input you entered was invalid: ",$reason;
        return true;
    }
