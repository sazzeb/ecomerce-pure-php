<?php
    define('BASEURL', $_SERVER['DOCUMENT_ROOT']. '/tutorial/');
    define('CART_COOKIE','hvhvGGS478JK9687BSgj63889shbDU');
    define('CART_COOKIE_EXPIRE',time() + (86400 * 30));
    define('TAXRATE',0.087); //sales tax rate

    define('CURRENCY','usd');
    define('CHECKOUTMODE','TEST'); // Change test to live when you are ready to go live

    if(CHECKOUTMODE == 'TEST'){
        define('STRIPE_PRIVATE','sk_test_ZSej3joEO4nZmZPUS6Fp1s9K');
        define('STRIPE_PUBLIC','pk_test_y8tykiwxMbQpotAZIAF9oZgA');
    }
    if(CHECKOUTMODE == 'LIVE'){
        define('STRIPE_PRIVATE','sk_live_SGFJgGg10O0TYGMf6qDRCLDY');
        define('STRIPE_PUBLIC','pk_live_d6pB8QbjWmhwQn1fwZdkw8hj');
    }
 ?>
