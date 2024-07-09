<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ViewCountRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ViewCountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('viewed_today', [ViewCountRuntime::class, 'isViewedToday']),
            new TwigFilter('viewed_this_month', [ViewCountRuntime::class, 'isViewedThisMont']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('views_count', [ViewCountRuntime::class, 'getViewsCount']),
            new TwigFunction('views_count_this_month', [ViewCountRuntime::class, 'getViewsCountThisMonths']),
            new TwigFunction('views_exceeded', [ViewCountRuntime::class, 'isViewsExceeded']),
            new TwigFunction('views_exceeded_this_month', [ViewCountRuntime::class, 'isViewsThisMonthExceeded']),
            new TwigFunction('views_left', [ViewCountRuntime::class, 'getViewsLeft']),
            new TwigFunction('views_left_this_month', [ViewCountRuntime::class, 'getViewsLeftThisMonth']),
        ];
    }
}
