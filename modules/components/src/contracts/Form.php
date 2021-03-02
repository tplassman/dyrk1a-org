<?php

namespace modules\components\contracts;

use Craft;
use craft\base\Model;
use modules\components\models\InputConditional;
use modules\components\models\InputOption;

abstract class Form extends Model
{
    public function attributeTypes(): array
    {
        return [];
    }

    public function getAttributeInputType(string $name): string
    {
        return $this->attributeTypes()[$name] ?? 'text';
    }

    public function attributeOptions(): array
    {
        return [];
    }

    /**
     * @return InputOption[]
     */
    public function getAttributeInputOptions(string $name): array
    {
        return $this->attributeOptions()[$name] ?? [];
    }

    public function getAttributeInputOption(string $name): ?InputOption
    {
        $options = $this->getAttributeInputOptions($name);
        $value = $this->{$name};

        foreach ($options as $o) {
            if ($o->value === $value) {
                return $o;
            }
        }

        return null;
    }

    public function attributeSizes(): array
    {
        return [];
    }

    public function getAttributeInputSize(string $name): string
    {
        return $this->attributeSizes()[$name] ?? 'full';
    }

    public function attributeCaptions(): array
    {
        return [];
    }

    public function getAttributeInputCaption(string $name): string
    {
        return $this->attributeCaptions()[$name] ?? '';
    }

    public function attributeConditionals(): array
    {
        return [];
    }

    public function getAttributeInputConditional(string $name): ?InputConditional
    {
        if (!method_exists($this, 'attributeConditionals')) {
            return null;
        }

        return $this->attributeConditionals()[$name] ?? null;
    }

    /**
     * @return AttributeDetail[]
     */
    public function attributeDetails(): array
    {
        return [];
    }

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
}
