<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AddHttpsRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AddHttpsExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('add_https', [AddHttpsRuntime::class, 'addHttps']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('add_https', [AddHttpsRuntime::class, 'addHttps']),
        ];
    }
}
