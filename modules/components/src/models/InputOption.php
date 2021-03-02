<?php

namespace modules\components\models;

use craft\base\Model;

class InputOption extends Model
{
    public string $value = '';
    public string $label = '';
    public string $icon = '';
    public bool $disabled = false;
}
