<?php
function encryptId($id, $key = 'usemee-secret-key-2025', $iv = '1234567890123400')
{
    return urlencode(base64_encode(openssl_encrypt($id, 'AES-128-CBC', $key, 0, $iv)));
}

function decryptId($encryptedId, $key = 'usemee-secret-key-2025', $iv = '1234567890123400')
{
    return openssl_decrypt(base64_decode(urldecode($encryptedId)), 'AES-128-CBC', $key, 0, $iv);
}

?>