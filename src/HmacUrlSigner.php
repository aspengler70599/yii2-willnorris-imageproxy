<?php

namespace dmstr\willnorrisImageproxy;

use dmstr\willnorrisImageproxy\UrlGeneratorInterface;


class HmacUrlSigner implements UrlGenerator
{
    private ?string $signatureKey;

    public function __construct()
    {
        $this->signatureKey = static::getSignatureKey();
    }

    public function createUrl(
        string $basePUrl,
        string $preset,
        string $remoteUrl
    ): string {
        if ($this->signatureKey) {
            $preset .= ',s' . strtr(
                    base64_encode(hash_hmac('sha256', $remoteUrl, $this->signatureKey, 1)),
                    '/+',
                    '_-'
                );
        }

        return implode('/', array_filter([$basePUrl, $preset, $remoteUrl]));
    }

    /**
     * if set, will be used as HMAC sign key for imageproxy preset
     *
     * @return string|null
     */
    protected static function getSignatureKey()
    {
        if (!isset(static::$_paramCache['signatureKey'])) {
            static::$_paramCache['signatureKey'] = getenv('IMAGEPROXY_SIGNATURE_KEY');
        }
        return static::$_paramCache['signatureKey'];
    }
}
