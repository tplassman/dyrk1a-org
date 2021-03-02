<?php

namespace modules\forms\web\twig\variables;

use modules\forms\contracts\Form;
use modules\forms\services\Forms;

class FormsVariable
{
    public function newForm(string $handle, array $attrs = []): Form
    {
        return Forms::newForm($handle, $attrs);
    }
}
