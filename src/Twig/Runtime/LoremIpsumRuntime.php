<?php

namespace App\Twig\Runtime;

use App\Service\LoremIpsum\Generator;
use Twig\Extension\RuntimeExtensionInterface;

class LoremIpsumRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getLoremIpsum($count = 10): array
    {
        return (new Generator())->getSentences($count);
    }
}
