<?php

namespace modules\components\models;

use craft\base\Model;
use yii\helpers\Html;

class Link extends Model
{
    const DATA_SMOOTH_SCROLL = 'data-smooth-scroll';

    public string $title = '';
    public string $url = '';
    public string $target = '';
    public string $rel = '';
    public bool $download = false;
    public bool $smoothScroll = false;

    public function render(string $content = '', $options = []): void
    {
        $text = $content;

        if ($text === '') {
            $text = $this->title;
        }

        if ($this->target !== '') {
            $options['target'] = $this->target;
        }

        if ($this->rel !== '') {
            $options['rel'] = $this->rel;
        }

        if ($this->download) {
            $options['download'] = true;
        }

        if ($this->smoothScroll) {
            $options[self::DATA_SMOOTH_SCROLL] = 'true'; // String true so it displays in HTML attribute
        }

        echo Html::a($text, $this->url, $options);
    }
}
