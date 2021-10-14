

<?php 
class Utils {
    public static function moneyFormat($currency, $value)
    {
        return $currency . " " .number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $value)),2);
    }
}

?>

