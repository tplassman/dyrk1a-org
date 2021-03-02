<?php

namespace modules\components\web\twig\variables;

use Craft;
use craft\base\Element;
use craft\elements\Asset;
use craft\elements\MatrixBlock;
use modules\components\models\Link;
use modules\components\models\MediaItem;
use verbb\supertable\elements\SuperTableBlockElement;

class ComponentsVariable
{
    public static function uniqueId(): string
    {
        return uniqid('_');
    }

    /**
     * Wrapper for our getLinks methods so the templates don't have to care about data types.
     *
     * @param null|MatrixBlock|SuperTableBlockElement $cta
     */
    public static function getLink($cta = null, array $options = []): ?Link
    {
        if ($cta === null) {
            return null;
        }

        // Normalize cta handles based on CMS conventions
        $title = $cta->ctaTitle ?? $cta->linkTitle ?? '';
        $pageField = $cta->ctaPage ?? $cta->linkPage ?? null;
        $url = $cta->ctaUrl ?? $cta->linkUrl ?? '';
        $anchor = $cta->ctaAnchor ?? $cta->linkAnchor ?? '';
        $email = $cta->ctaEmail ?? $cta->linkEmail ?? '';
        $downloadField = $cta->ctaDownload ?? $cta->linkDownload ?? null;

        // Internal CMS page
        $page = method_exists($pageField, 'one')
            ? $pageField->one()
            : $pageField[0] ?? null;

        if ($page !== null) {
            $url = $page->url;

            return new Link(array_merge($options, [
                'title' => $title,
                'url' => $url,
            ]));
        }

        // External link
        if ($url !== '') {
            return new Link(array_merge($options, [
                'title' => $title,
                'url' => $url,
                'target' => '_blank',
                'rel' => 'noopener',
            ]));
        }

        // Anchor link
        if ($anchor !== '') {
            return new Link(array_merge($options, [
                'title' => $title,
                'url' => sprintf('%s#%s', Craft::$app->getRequest()->url, $anchor),
                'smoothScroll' => true,
            ]));
        }

        // Mail Link
        if ($email !== '') {
            return new Link(array_merge($options, [
                'title' => $title,
                'url' => sprintf('mailto:%s', $email),
                'target' => '_blank',
                'rel' => 'noopener',
            ]));
        }

        // Asset download link
        $asset = method_exists($downloadField, 'one')
            ? $downloadField->one()
            : $downloadField[0] ?? null;

        if ($asset !== null) {
            return new Link(array_merge($options, [
                'title' => $title,
                'url' => $asset->url ?? '',
                'target' => '_blank',
                'rel' => 'noopener',
                'download' => true,
            ]));
        }

        return new Link(array_merge($options, [
            'title' => $title,
        ]));
    }

    public static function getLinkFromElement(Element $el = null, string $title = null, $download = false): ?Link
    {
        if ($el === null) {
            return null;
        }

        $options = [];

        if ($download) {
            $options = array_merge($options, [
                'target' => '_blank',
                'rel' => 'noopener',
                'download' => true,
            ]);
        }

        return new Link(array_merge($options, [
            'title' => $title ?? $el->title,
            'url' => $el->url,
        ]));
    }

    public static function getMediaItem(Asset $asset = null, string $type = 'image'): ?MediaItem
    {
        if ($asset === null) {
            return null;
        }

        return new MediaItem([
            'imageUrl' => $asset->url,
            'description' => $asset->title,
            'type' => $type,
        ]);
    }

    public static function getFirstDay(string $month, string $year): int
    {
        return mktime(0, 0, 0, (int) $month, 1, (int) $year);
    }

    public static function getDayOfWeek(int $timestamp): int
    {
        return getdate($timestamp)['wday'];
    }

    public static function getPrevMonth(int $timestamp): int
    {
        return strtotime(date('Y-m-d', $timestamp).' -1 month');
    }

    public static function getNextMonth(int $timestamp): int
    {
        return strtotime(date('Y-m-d', $timestamp).' +1 month');
    }
}
