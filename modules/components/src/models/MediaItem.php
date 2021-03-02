<?php

namespace modules\components\models;

use Craft;
use craft\base\Model;
use craft\helpers\Html;
use craft\web\View;

class MediaItem extends Model
{
    public string $imageUrl = '';
    public string $description = '';
    public string $type = '';

    public function render(array $attrs = []): void
    {
        echo [
            'image' => Html::img($this->imageUrl, array_merge($attrs, [
                'alt' => $this->description,
            ])),
            'background' => Craft::$app->getView()->renderTemplate('_components/background-image', [
                'url' => $this->imageUrl,
            ], View::TEMPLATE_MODE_SITE),
        ][$this->type] ?? '';
    }
}
