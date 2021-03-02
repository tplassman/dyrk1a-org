<?php

namespace modules\forms\contracts;

use Craft;
use craft\helpers\App;
use modules\components\contracts\Form as BaseForm;

abstract class Form extends BaseForm
{
    const CRAFT_SECTION_ID = '2'; // Form Submissions
    const CRAFT_TYPE_ID = '';

    public string $method = 'post';

    abstract public function getHandle(): string;

    public function getActionPath(): string
    {
        return 'forms-module/forms/submit';
    }

    public function getFields(): array
    {
        return $this->scenarios()[$this->scenario];
    }

    public function getSubmitText(): string
    {
        return 'Submit';
    }

    abstract public function saveLocal(): bool;

    abstract public function saveRemote(): bool;

    abstract public function sendNotificationEmail(): bool;

    abstract public function sendConfirmationEmail(): bool;

    abstract public function getSuccessMessage(): string;

    /**
     * Helper function to validate hashed hidden inputs from frontend forms.
     * https://www.yiiframework.com/doc/guide/2.0/en/input-validation#creating-validators.
     *
     * @param string                          $attribute the attribute currently being validated
     * @param mixed                           $params    the value of the "params" given in the rule
     * @param \yii\validators\InlineValidator $validator related InlineValidator instance
     * @param mixed                           $current   the currently validated value of attribute
     */
    public function validateHash($attribute, $params, $validator, $current)
    {
        $unhashed = Craft::$app->getSecurity()->validateData($this->{$attribute});

        if ($unhashed === false) {
            $this->addErrors([$attribute => 'Invalid hashed input.']);

            return false;
        }

        $this->{$attribute} = $unhashed;
    }

    public function validRecaptcha(string $token): bool
    {
        $endpoint = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => App::env('RECAPTCHA_SECRET_KEY'),
            'response' => $token,
        ];

        $res = Craft::createGuzzleClient()->request('POST', $endpoint, ['query' => $data]);

        return json_decode($res->getBody(), true)['success'];
    }

    public function getAttributesSummaryTable(array $mapper = []): string
    {
        $table = '';
        $table .= '<h3>Submission Details</h3>';
        $table .= '<table border="0" cellpadding="10">';
        $table .= '<tbody>';
        foreach ($this->getAttributes() as $name => $value) {
            if ($this->getAttributeInputType($name) === 'hidden') {
                continue;
            }

            $value = is_array($value) ? implode(', ', $value) : nl2br($value);

            // Allow caller to override printed value in table w/ mapping function
            if (array_key_exists($name, $mapper)) {
                $value = $mapper[$name]($value);
            }

            $table .= '<tr><td>';
            $table .= sprintf('<strong>%s</strong><br />', $this->getAttributeLabel($name));
            $table .= $value;
            $table .= '</td></tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';

        return $table;
    }
}
