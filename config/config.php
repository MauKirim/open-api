<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    "token" => env('MAUKIRIM_TOKEN', ''),
    "dev_url" => "https://dev-api.maukirim.com",
    "prod_url" => "https://api.maukirim.com",
    "api_env" => env('MAUKIRIM_ENV', 'prod'),
];