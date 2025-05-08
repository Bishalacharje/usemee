<?php
function encryptId($id, $key = 'your-secret-key-1234', $iv = '1234567890123456')
{
    return urlencode(base64_encode(openssl_encrypt($id, 'AES-128-CBC', $key, 0, $iv)));
}

function decryptId($encryptedId, $key = 'your-secret-key-1234', $iv = '1234567890123456')
{
    return openssl_decrypt(base64_decode(urldecode($encryptedId)), 'AES-128-CBC', $key, 0, $iv);
}

?>