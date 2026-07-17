<?php

namespace dmstr\willnorrisImageproxy;

interface UrlGeneratorInterface
{

    public function createUrl(
        string $baseUrl,
        string $preset,
        string $remoteUrl
    ): string;
}