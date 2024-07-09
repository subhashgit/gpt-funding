<?php

namespace App\Twig\Runtime;

use Carbon\Carbon;
use Twig\Extension\RuntimeExtensionInterface;

class CarbonRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function carbon($value)
    {
        return Carbon::make($value);
    }
}
