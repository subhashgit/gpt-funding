<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\IntRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class IntExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('int', [IntRuntime::class, 'int']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('int', [IntRuntime::class, 'int']),
        ];
    }
}
