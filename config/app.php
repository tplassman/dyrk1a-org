<?php
/**
 * Yii Application Config.
 *
 * Edit this file at your own risk!
 *
 * The array returned by this file will get merged with
 * vendor/craftcms/cms/src/config/app.php and app.[web|console].php, when
 * Craft's bootstrap script is defining the configuration for the entire
 * application.
 *
 * You can define custom modules and system components, and even override the
 * built-in system components.
 *
 * If you want to modify the application config for *only* web requests or
 * *only* console requests, create an app.web.php or app.console.php file in
 * your config/ folder, alongside this one.
 */
use craft\helpers\App;
use modules\components\ComponentsModule;
use modules\forms\FormsModule;

// Start: Basic auth for staging.
$passwords = ['grubb' => 'properties'];
$users = array_keys($passwords);

$user = $_SERVER['PHP_AUTH_USER'] ?? '';
$pass = $_SERVER['PHP_AUTH_PW'] ?? '';

$path = $_SERVER['REDIRECT_URL'] ?? $_SERVER['REQUEST_URI'];
$exceptions = [
    //     '/cmd/appointments/free',
    //     '/cmd/calendly/webhooks/receive',
];

if (
    CRAFT_ENVIRONMENT !== 'development' && // Check non development environments
    (!in_array($user, $users, true) || !$pass === $passwords[$user]) && // Check for active user and corresponding password
    (!in_array($path, $exceptions, true)) // Allow requests in white-listed paths
) {
    header('WWW-Authenticate: Basic realm="Palmetto Bludd"');
    header('HTTP/1.0 401 Unauthorized');
    exit('Not authorized');
}
// End: Basic auth for staging.

$modules = [
    'components-module' => ComponentsModule::class,
    'forms-module' => FormsModule::class,
];

return [
    'id' => App::env('APP_ID') ?: 'CraftCMS',
    'modules' => $modules,
    'bootstrap' => array_keys($modules),
];
