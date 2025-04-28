<?php

namespace App\Services;

class DkimService
{
    public function generateDkim(string $domain, string $selector = 'default'): array
    {
        $config = [
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);

        if (!$res) {
            throw new \Exception('Failed to generate DKIM key pair.');
        }

        openssl_pkey_export($res, $privateKey);

        $details = openssl_pkey_get_details($res);
        $publicKey = $details['key'];

        $dnsPublicKey = str_replace(
            ["-----BEGIN PUBLIC KEY-----", "-----END PUBLIC KEY-----", "\n", "\r"],
            '',
            $publicKey
        );

        $txtName = "{$selector}._domainkey.{$domain}";
        $txtValue = "v=DKIM1; k=rsa; p=" . trim($dnsPublicKey);

        return [
            'domain'      => $domain,
            'selector'    => $selector,
            'txt_name'    => $txtName,
            'txt_value'   => $txtValue,
            'private_key' => $privateKey,
            'public_key'  => $publicKey,
        ];
    }
}
