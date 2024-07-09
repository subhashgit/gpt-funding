<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class IntRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function int($value): int
    {
        return (int) $value;
    }
}
