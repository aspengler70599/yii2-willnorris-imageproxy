<?php

namespace dmstr\willnorrisImageproxy;

use dmstr\willnorrisImageproxy\UrlGeneratorInterface;
use Aws\CloudFront\CloudFrontClient;

class CludFrontUrlSigner implements UrlGeneratorInterface
{
    public function __construct(
        private CloudFrontClient $client,
        private string           $keyPairId,
        private string           $privateKeyPath)
    {
    }

    public function createUrl(
        string $baseUrl,
        string $preset,
        string $remoteUrl): string
    {
       $url = implode('/', array_filter([
           $baseUrl,
           $preset,
           $remoteUrl
       ]));

       return $this->client->getSignedUrl([
           'url' => $url,
           'expires' => time() + 3600,
           'private_key' => $this->privateKey,
           'key_pair_id' => $this->keyPairId
       ]);
    }
}
