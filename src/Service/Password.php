<?php

declare(strict_types=1);

namespace App\Service;

final class Password
{
    /**
     * @var \Cake\Utility\Security $security
     */
    private \Cake\Utility\Security $security;

    /**
     * @note 32 symbols
     * @var string $encryptionKey
     */
    private string $encryptionKey;

    /**
     * @var string|null $hmacSalt
     */
    private ?string $hmacSalt;

    /**
     * @param \App\Application\Settings\SettingsInterface $setting
     * @param \Cake\Utility\Security $security
     */
    public function __construct(
        \App\Application\Settings\SettingsInterface $setting,
        \Cake\Utility\Security $security
    ) {
        $this->security = $security;

        $encryptionSettings = $setting->get('encryption');
        $this->encryptionKey = $encryptionSettings['key'];
        $this->hmacSalt = $encryptionSettings['salt'] ?: null;
    }

    /**
     * Hash password with Argon2ID algorithm, encrypt it with AES-256 and encode to ASCII
     *
     * @param string $password
     * @return string
     */
    public function hash(string $password): string
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $encrypted = $this->security::encrypt($hash, $this->encryptionKey, $this->hmacSalt);

        return base64_encode($encrypted);
    }

    /**
     * Compare password with decoded and decrypted hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function compare(string $password, string $hash): bool
    {
        $decoded = base64_decode($hash);
        $decrypted = $this->security::decrypt($decoded, $this->encryptionKey, $this->hmacSalt);

        return password_verify($password, $decrypted);
    }
}
