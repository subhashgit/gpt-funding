<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ParseUrlRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ParseUrlExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('parse_url', [ParseUrlRuntime::class, 'parseUrl']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('parse_url', [ParseUrlRuntime::class, 'parseUrl']),
        ];
    }
}
