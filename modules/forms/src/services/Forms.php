<?php

namespace modules\forms\services;

use modules\forms\contracts\Form;
use modules\forms\models\Contact;

class Forms
{
    public static function newForm(string $handle, array $attrs = []): Form
    {
        $f = [
            'contact' => new Contact(),
        ][$handle] ?? new Contact();

        if (count($attrs) > 0) {
            $f->setAttributes($attrs, false);
        }

        return $f;
    }
}
