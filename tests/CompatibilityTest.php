<?php

// Run against an installed Contao dependency tree, without booting the site or using its database.
require $argv[1];
require dirname(__DIR__) . '/src/Service/CaptchaService.php';
require dirname(__DIR__) . '/src/Widget/CaptchaWidget.php';

use Contao\Config;
use Contao\Database;
use Contao\System;
use PBDKN\ContaoCaptchaBundle\Service\CaptchaService;
use PBDKN\ContaoCaptchaBundle\Widget\CaptchaWidget;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

function check($condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
    echo "OK: $message\n";
}

class TestDatabase extends Database
{
    public array $insert = [];
    public array $arguments = [];
    public function __construct() {}
    public function prepare($sql)
    {
        return new class($this) {
            private TestDatabase $db;
            public function __construct(TestDatabase $db) { $this->db = $db; }
            public function set($data) { $this->db->insert = $data; return $this; }
            public function execute(...$arguments) { $this->db->arguments = $arguments; return $this; }
            public function fetchAssoc() { return ['text' => 'Ab123']; }
        };
    }
}

class TestService extends CaptchaService
{
    public bool $valid = true;
    public array $checked = [];
    public function __construct() {}
    public function checkCode($hash, $text) { $this->checked = [$hash, $text]; return $this->valid; }
}

$container = new ContainerBuilder();
$container->setParameter('kernel.charset', 'UTF-8');
$container->setParameter('kernel.project_dir', dirname(__DIR__));
$stack = new RequestStack();
$stack->push(Request::create('/contact'));
$container->set('request_stack', $stack);
$container->set('contao.routing.scope_matcher', new class {
    public function isBackendRequest($request) { return false; }
});
$fake = new TestService();
$container->set(CaptchaService::class, $fake);
System::setContainer($container);
Config::set('tc_captchaimage', true);
$GLOBALS['TL_LANG']['tossn_captcha']['error'] = 'Incorrect captcha';

$widget = (new ReflectionClass(CaptchaWidget::class))->newInstanceWithoutConstructor();
$widget->name = 'captcha';
$_POST['captcha_hash'] = str_repeat('a', 32);
$stack->getCurrentRequest()->request->set('captcha_hash', str_repeat('a', 32));
$validator = new ReflectionMethod(CaptchaWidget::class, 'validator');
$validator->setAccessible(true);
check($validator->invoke($widget, ' Ab123 ') === 'Ab123', 'Valid input remains a string and is trimmed');
$fake->valid = false;
check($validator->invoke($widget, 'wrong') === '' && $widget->hasErrors(), 'Wrong answer creates a field error');
check($validator->invoke($widget, ['invalid']) === '', 'Array input is rejected');
Config::set('tc_captchaimage', false);
check($validator->invoke($widget, '') === '', 'Disabled captcha does not reject the form');

require dirname(__DIR__) . '/contao/config/config.php';
require dirname(__DIR__) . '/contao/dca/tl_form_field.php';
check($GLOBALS['TL_FFL']['tossn_captcha'] === CaptchaWidget::class && $GLOBALS['TL_FFL']['tossnCaptcha'] === CaptchaWidget::class, 'Both stored field types are supported');
check($GLOBALS['TL_DCA']['tl_form_field']['palettes']['tossn_captcha'] === $GLOBALS['TL_DCA']['tl_form_field']['palettes']['tossnCaptcha'], 'Both field types have an editable palette');

$service = (new ReflectionClass(CaptchaService::class))->newInstanceWithoutConstructor();
$db = new TestDatabase();
foreach (['database' => $db, 'logger' => new Psr\Log\NullLogger()] as $name => $value) {
    $property = new ReflectionProperty(CaptchaService::class, $name);
    $property->setAccessible(true);
    $property->setValue($service, $value);
}
check($service->checkCode(str_repeat('a', 32), 'Ab123'), 'Correct stored code is accepted');
check(count($db->arguments) === 2 && $db->arguments[1] >= time() - 601, 'Database lookup checks expiry');
check(!$service->checkCode('../invalid', 'Ab123'), 'Malformed hashes are rejected');
check(!$service->checkCode(str_repeat('a', 32), 'wrong'), 'Wrong stored code is rejected');

$imageDir = sys_get_temp_dir() . '/tossn-test-' . bin2hex(random_bytes(8)) . '/';
mkdir($imageDir);
try {
    foreach (['VendorbackgroundImage' => dirname(__DIR__) . '/public/resource/image/blank.jpg', 'VendorcaptchaFont' => dirname(__DIR__) . '/public/resource/font/default.ttf', 'captchaImagePath' => $imageDir] as $name => $value) {
        $property = new ReflectionProperty(CaptchaService::class, $name);
        $property->setAccessible(true);
        $property->setValue($service, $value);
    }
    $service->createCaptcha();
    $image = $imageDir . $service->getHash() . '.png';
    check(is_file($image) && getimagesize($image)[2] === IMAGETYPE_PNG, 'GD generates a valid captcha PNG');
    check(strlen($db->insert['text']) === 5 && $db->insert['hash'] === $service->getHash(), 'Image and database challenge match');
} finally {
    foreach (glob($imageDir . '*.png') as $image) { unlink($image); }
    rmdir($imageDir);
}
