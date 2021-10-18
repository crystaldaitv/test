<?php
defined('ABSPATH') || die;

if (!function_exists('ag_sanitize_int_number')) {
    /**
     * Sanitize integer number
     *
     * @param mixed $number Number
     *
     * @return integer
     */
    function ag_sanitize_int_number($number)
    {
        return intval($number);
    }
}

if (!function_exists('ag_sanitize_float_number')) {
    /**
     * Sanitize float number
     *
     * @param mixed $number Number
     *
     * @return float
     */
    function ag_sanitize_float_number($number)
    {
        return floatval($number);
    }
}


if (!function_exists('ag_sanitize_font_style')) {
    /**
     * Sanitize font style
     *
     * @param string $styles Style
     *
     * @return string
     */
    function ag_sanitize_font_style($styles)
    {
        return strval($styles);
    }
}

if (!function_exists('ag_sanitize_alpha_color')) {
    /**
     * Sanitize RGBA color
     *
     * @param string $color Select color value
     *
     * @return string|boolean
     */
    function ag_sanitize_alpha_color($color)
    {
        if ($color === null || $color === '') {
            return '#ffffff00';
        }
        return (string) $color;
    }
}
