<?php

if (!function_exists('github_image_url')) {
    function github_image_url($filename)
    {
        return "https://raw.githubusercontent.com/devakhilus/ecommerce/main/public/images/products/" . $filename;
    }
}
