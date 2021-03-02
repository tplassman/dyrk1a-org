<?php

namespace modules\components\web\twig\extensions;

use craft\helpers\StringHelper;
use modules\shop\services\Adjustments;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class ComponentsExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'Components Twig Extension';
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('annotate', [$this, 'annotate'], ['is_safe' => 'html']),
            new TwigFilter('exceprt', [$this, 'exceprt']),
            new TwigFilter('emphasize', [$this, 'emphasize'], ['is_safe' => 'html']),
            new TwigFilter('format_cents', [$this, 'formatCents']),
            new TwigFilter('link_phone', [$this, 'linkPhone']),
            new TwigFilter('search', 'array_search'),
            new TwigFilter('sum', 'array_sum'),
        ];
    }

    public function annotate(?string $content): Markup
    {
        return new Markup(
            preg_replace(
                '/(\s[\w\/\-\'|\"|\™|\°F]+)\[(\d+)\]/',
                ' <span style="white-space:nowrap"> $1<a href="#footnote-$2" class="footnote">[$2]</a></span>',
                $content
            ),
            StringHelper::UTF8
        );
    }

    public function exceprt(?string $content, int $len = 255, $append = '...'): string
    {
        if ($content === null) {
            return '';
        }

        return StringHelper::safeTruncate(StringHelper::stripHtml($content), $len, $append);
    }

    public function emphasize(?string $content = ''): Markup
    {
        return new Markup(
            preg_replace(
                // bold, italics, superscript, subscript
                ['/\*([^\*]*)\*/', '/\~([^\~]*)\~/', '/\^([^\^]*)\^/', '/\_([^\_]*)\_/'],
                ['<strong>$1</strong>', '<em>$1</em>', '<sup>$1</sup>', '<sub>$1</sub>'],
                $content
            ),
            StringHelper::UTF8
        );
    }

    public function formatCents(?string $content, int $decimals = 2): string
    {
        return Adjustments::formatCents((int) $content, $decimals);
    }

    public function linkPhone(?string $content): string
    {
        return preg_replace('/[^0-9]/', '', $content);
    }
}
