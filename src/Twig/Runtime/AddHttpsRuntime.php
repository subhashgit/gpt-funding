<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class AddHttpsRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function addHttps(string $url): string
    {
        return str_starts_with($url, 'http') ? $url : 'https://'.$url;
    }
}
