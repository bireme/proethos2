<?php

// This file is part of the ProEthos Software. 
// 
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software. 
// 
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt
 

namespace Proethos2\CoreBundle\Util;

use Proethos2\CoreBundle\Util\Security;


class Security {

    public static function encrypt($data)
    {
        global $kernel;
        // $private_key = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
        $private_key = $kernel->getContainer()->getParameter('private_key');
        // $index_key = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
        $index_key = $kernel->getContainer()->getParameter('index_key');

        $key        = base64_decode($private_key);
        $nonce      = base64_decode($index_key);
        $ciphertext = sodium_crypto_secretbox($data, $nonce, $key);
        $encoded    = base64_encode($nonce . $ciphertext);
        
        return $encoded;
    }

    public static function decrypt($data)
    {
        global $kernel;
        // $private_key = base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
        $private_key = $kernel->getContainer()->getParameter('private_key');

        $key        = base64_decode($private_key);
        $decoded    = base64_decode($data);
        $nonce      = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $decoded    = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

        return mb_convert_encoding($decoded, 'UTF-8', 'ISO-8859-1');
    }
}