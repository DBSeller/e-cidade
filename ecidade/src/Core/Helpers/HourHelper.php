<?php


namespace ECidade\Core\Helpers;

class HourHelper
{
    /**
     * @param $hourInFloat float
     * @return string
     */
    public function convertFloatToHour($hourInFloat)
    {
        $hours = (int) $hourInFloat;
        $mod = abs($hourInFloat - $hours);
        $minutes = (int) round(((60 * $mod) / 100) * 100);

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    /**
     * @param $hourFormat
     * @return float
     */
    public function convertHourToFloat($hourFormat)
    {
        list($hour, $minutes) = explode(':', $hourFormat);
        return $hour + round(($minutes / 60) * 100) / 100;
    }
}
