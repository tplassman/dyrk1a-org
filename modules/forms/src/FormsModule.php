<?php

namespace modules\forms;

use craft\web\twig\variables\CraftVariable;
use modules\forms\web\twig\variables\FormsVariable;
use yii\base\Event;
use yii\base\Module;

class FormsModule extends Module
{
    public function init()
    {
        parent::init();

        $this->controllerNamespace = 'modules\forms\controllers';

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, [$this, 'registerVariables']);
    }

    public function registerVariables(Event $event): void
    {
        $event->sender->set('forms', FormsVariable::class);
    }
}
