<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TruncateTextExtension extends AbstractExtension
{
    public function getFilters(): array
    {

        return [
            new TwigFilter('ellipsis', array($this, 'ellipsisFilter')),
        ];
//        return [
//            // If your filter generates SAFE HTML, you should add a third
//            // parameter: ['is_safe' => ['html']]
//            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//            new TwigFilter('filter_name', [$this, 'doSomething']),
//        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function ellipsisFilter($text, $maxLen = 50, $ellipsis = '...')
    {
        if ( strlen($text) <= $maxLen)
            return $text;
        return substr($text, 0, $maxLen-3).$ellipsis;

    }
}
