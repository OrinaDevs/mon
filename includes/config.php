<?php
const SITE_NAME = 'Merchant of Nairobi';
const SITE_TAGLINE = 'Your Go To Tech Center';
//LOCAL: /mon, LIVE: ''(EMPTY)
const BASE_URL = '/mon';

const WHATSAPP_NUMBER = '254704067418';

// Each entry: the number as customers read it, and the +254 form for tap-to-call.
const SHOP_PHONES = [
    ['display' => '0704 067 418', 'intl' => '+254704067418'],
    ['display' => '0728 482 611', 'intl' => '+254728482611'],   // replace with the second number
];
const SHOP_EMAIL = 'merchantofnairobi@gmail.com';
const SHOP_LOCATION = 'Nairobi, Kenya';       // add the street/building when you have it
const SHOP_HOURS = 'Mon to Sat: 8:00 am to 6:00 pm';


// ---- Search engines -------------------------------------------------------
// Full address of the website, no trailing slash. Local: 'http://localhost/mon'.
// On the live host this becomes your real domain, e.g. 'https://www.yourdomain.co.ke'.
const SITE_URL = 'http://localhost/mon';
const SHOP_LOCALITY = 'Nairobi';       // town or city shown in Google's business details
const SHOP_COUNTRY_CODE = 'KE';