<?php

namespace modules\forms\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use modules\forms\services\Forms;
use yii\web\ServerErrorHttpException;

class FormsController extends Controller
{
    protected $allowAnonymous = ['submit'];

    public function actionSubmit(): ?Response
    {
        $this->requirePostRequest();

        // Instantiate form
        $handle = $this->request->getValidatedBodyParam('handle');
        $form = Forms::newForm($handle, $this->request->getBodyParams());

        // Validate
        if (!$form->validate()) {
            // Send the global set back to the template
            Craft::$app->getSession()->setError('Something\'s not right. Please see errors below');
            Craft::$app->getUrlManager()->setRouteParams(['form' => $form]);

            return null;
        }

        // Remove this is you want to test on development
        if (CRAFT_ENVIRONMENT === 'development') {
            Craft::$app->getSession()->setFlash('successMessage', [($handle) => $form->getSuccessMessage()]);

            return $this->redirectToPostedUrl();
        }

        // Save local copy of submission
        if (!$form->saveLocal()) {
            throw ServerErrorHttpException;
        }

        // Save remote copy of submission
        if (!$form->saveRemote()) {
            throw ServerErrorHttpException;
        }

        // Send notification
        if (!$form->sendNotificationEmail()) {
            throw ServerErrorHttpException;
        }

        // Send confirmation
        if (!$form->sendConfirmationEmail()) {
            throw ServerErrorHttpException;
        }

        // All is well
        Craft::$app->getSession()->setFlash('successMessage', [($handle) => $form->getSuccessMessage()]);

        return $this->redirectToPostedUrl();
    }
}
