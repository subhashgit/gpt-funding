<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;
use YoHang88\LetterAvatar\LetterAvatar;

class AvatarRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function avatar($userCompleteName, $size, $shape = 'circle')
    {
        return new LetterAvatar($userCompleteName, $shape, $size);
    }
}
