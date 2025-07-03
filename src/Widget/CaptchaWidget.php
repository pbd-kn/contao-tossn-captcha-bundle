<?php

namespace PBDKN\ContaoCaptchaBundle\Widget;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\Input;
use Contao\Widget;
use Contao\System;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

use PBDKN\ContaoCaptchaBundle\Service\CaptchaService;

class CaptchaWidget extends Widget
{
    protected $strTemplate = 'form_tossn_captcha';
    protected $strPrefix = 'widget widget-text';

    private CaptchaService $captchaService;
    private Config $config;
    private RequestStack $requestStack;

    public function __construct(array $arrAttributes = null)
    {
        parent::__construct($arrAttributes);

        $this->config = Config::getInstance();
        $this->captchaService = System::getContainer()->get(CaptchaService::class);

        $this->captchaService->createCaptcha();
        $this->arrConfiguration['captcha_hash'] = $this->captchaService->getHash();
        $this->arrConfiguration['captcha_image'] = $this->captchaService->getImageName();
    }

    public function parse($attributes = null): string
    {
        if (!$this->config->get('tc_captchaimage')) {
            return '';
        }

        return $this->generate();
    }

public function generate(): string
{
    // Backend: Wildcard anzeigen
    if (\defined('BE_USER_LOGGED_IN') && BE_USER_LOGGED_IN === true) {
        $template = new \Contao\BackendTemplate('be_wildcard');
        $template->wildcard = '### TOSSN CAPTCHA ###';
        $template->title = $this->name ?: 'TOSSN Captcha';
        $template->id = $this->id;
        $template->link = $this->name;
        $template->href = 'contao?do=form&table=tl_form_field&id=' . $this->id;
        return $template->parse();
    }

    // Fehlerbehandlung
    $hasErrors = $this->hasErrors();
    $errorMessage = $hasErrors ? $this->getErrorAsString() : '';
    $errorClass = $hasErrors ? ' has-error' : '';

    // Template manuell erzeugen
    $template = new \Contao\FrontendTemplate('form_tossn_captcha');
$template->type = $this->type;
$template->addSubmit = $this->addSubmit ?? false;
$template->slabel = $this->slabel ?? 'Absenden';
    
    $template->id = $this->id;
    $template->name = $this->strName;
    $template->label = $this->label;
    $template->class = trim('widget captcha-widget ' . $this->strClass);
    $template->value = $this->value;
    $template->mandatory = $this->mandatory;
    $template->attributes = $this->getAttributes();
    $template->captcha_hash = $this->arrConfiguration['captcha_hash'];
    $template->captcha_image = '/' . ltrim((string) $this->arrConfiguration['captcha_image'], '/');
    $template->hasErrors = $hasErrors;
    $template->error = $errorMessage;
    $template->errorClass = $errorClass;

    return $template->parse();
}
protected function validator($varInput): bool
{
    $isValid = parent::validator($varInput); // wichtig: zuerst den Standard-Validator durchlaufen

    if (!$this->captchaService->checkCode(Input::post($this->strName . '_hash'), $varInput)) {
        $this->addError($GLOBALS['TL_LANG']['tossn_captcha']['error']);
        $this->value = ''; // Eingabe explizit löschen
        return false; // Validierung fehlgeschlagen
    }

    return $isValid;
}

}
