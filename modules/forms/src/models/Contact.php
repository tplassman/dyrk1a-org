<?php

namespace modules\forms\models;

use Craft;
use craft\elements\Entry;
use modules\forms\contracts\Form;

class Contact extends Form
{
    use StatesTrait;

    const CRAFT_TYPE_ID = '2'; // Contct

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $contactMethod = '';
    public string $reason = '';
    public string $message = '';

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'email', 'phone', 'contactMethod', 'reason', 'message'],
        ];
    }

    public function rules()
    {
        return [
            [['name', 'email', 'phone'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'phone' => 'Phone Number',
            'contactMethod' => 'Preferred Method of Contact',
            'reason' => 'How can we help you?',
        ];
    }

    public function attributeTypes(): array
    {
        return [
            'email' => 'email',
            'phone' => 'tel',
            'contactMethod' => 'select',
            'reason' => 'select',
            'comments' => 'textarea',
        ];
    }

    public function attributeOptions(): array
    {
        return [
            'contactMethod' => [
                ['value' => 'phone', 'label' => 'Phone Call'],
                ['value' => 'email', 'label' => 'Email Message'],
            ],
        ];
    }

    public function getHandle(): string
    {
        return 'contact';
    }

    public function getSubmitText(): string
    {
        return 'Submit Info';
    }

    public function saveLocal(): bool
    {
        $entry = new Entry([
            'sectionId' => self::CRAFT_SECTION_ID,
            'typeId' => self::CRAFT_TYPE_ID,
            'authorId' => 3, // tplassman@wrayward.com
            'enabled' => true,
        ]);

        $entry->setFieldValues([
            'formName' => $this->name,
            'formEmailAddress' => $this->email,
            'formPhoneNumber' => $this->phone,
            'formContactMethod' => $this->contactMethod,
            'formContactReason' => $this->reason,
            'formMessage' => $this->message,
        ]);

        if (!Craft::$app->getElements()->saveElement($entry)) {
            $this->addModelErrors($entry->getErrors());

            return false;
        }

        return true;
    }

    public function saveRemote(): bool
    {
        return true;
    }

    public function sendNotificationEmail(): bool
    {
        $to = 'tplassman@wrayward.com'; // TODO: Pull from CMS
        $heading = sprintf('New Contact Form Submission');
        $body = $this->getAttributesSummaryTable();

        return Craft::$app->getMailer()->compose()
            ->setTo($to)
            ->setFrom($this->email)
            ->setSubject($heading)
            ->setHtmlBody($body)
            ->send();
    }

    public function sendConfirmationEmail(): bool
    {
        $heading = 'Thanks for reaching out.'; // TODO: Pull from CMS
        $body = 'TODO: Replace w/ confirmation body text';

        return Craft::$app->getMailer()->compose()
            ->setTo($this->email)
            ->setSubject($heading)
            ->setHtmlBody($body)
            ->send();
    }

    public function getSuccessMessage(): string
    {
        return 'Thanks for your message. We will be in touch soon.';
    }
}
