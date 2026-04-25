<?php

declare(strict_types=1);

namespace Rcalicdan\KSEFClient\Factories;

use Rcalicdan\KSEFClient\ValueObjects\EncryptionKey;
use Rcalicdan\KSEFClient\ValueObjects\KsefPublicKey;
use Rcalicdan\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PublicKey as RSAPublicKey;
use RuntimeException;

final class EncryptedKeyFactory extends AbstractFactory
{
    public static function make(EncryptionKey $encryptionKey, KsefPublicKey $ksefPublicKey): EncryptedKey
    {
        /** @var RSAPublicKey $pub */
        $pub = PublicKeyLoader::load($ksefPublicKey->value);

        //@phpstan-ignore-next-line
        $encryptedKey = $pub
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($encryptionKey->key);

        if ($encryptedKey === false) {
            throw new RuntimeException('Unable to encrypt key');
        }

        /** @var string $encryptedKey */
        $encryptedKey = base64_encode((string) $encryptedKey); //@phpstan-ignore-line

        /** @var string $encryptedIv */
        $encryptedIv = base64_encode($encryptionKey->iv);

        return new EncryptedKey($encryptedKey, $encryptedIv);
    }
}
