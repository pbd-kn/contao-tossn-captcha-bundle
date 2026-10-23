<?php

namespace PBDKN\ContaoCaptchaBundle\Widget;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\Input;
use Contao\System;
use Contao\Widget;
use PBDKN\ContaoCaptchaBundle\Service\CaptchaService;

class CaptchaWidget extends Widget
{
    protected $strTemplate = 'form_tossn_captcha';
    protected $strPrefix = 'widget widget-text';

    private function isBackendRequest(): bool
    {
        $container = System::getContainer();
        $request = $container->get('request_stack')->getCurrentRequest();

        return $request !== null
            && $container->get('contao.routing.scope_matcher')->isBackendRequest($request);
    }

    private function getCaptchaService(): CaptchaService
    {
        return System::getContainer()->get(CaptchaService::class);
    }

    public function parse($attributes = null): string
    {
        if ($this->isBackendRequest()) {
            $template = new BackendTemplate('be_wildcard');
            $template->wildcard = '### TOSSN CAPTCHA ###';
            $template->title = $this->label;
            $template->id = $this->id;
            $template->link = '';
            $template->href = '';

            return $template->parse();
        }

        if (!Config::get('tc_captchaimage')) {
            return '';
        }

        if (empty($this->arrConfiguration['captcha_hash'])) {
            $service = $this->getCaptchaService();
            $service->createCaptcha();
            $this->arrConfiguration['captcha_hash'] = $service->getHash();
            $this->arrConfiguration['captcha_image'] = $service->getImageName();
        }

        return parent::parse($attributes);
    }

    public function generate(): string
    {
        return $this->parse();
    }

    protected function validator($varInput)
    {
        if (!Config::get('tc_captchaimage') || $this->isBackendRequest()) {
            return $varInput;
        }

        if (!is_string($varInput)) {
            $this->addError($GLOBALS['TL_LANG']['tossn_captcha']['error']);

            return '';
        }

        $value = parent::validator($varInput);
        $hash = Input::post($this->strName . '_hash');

        if (!is_string($hash) || !$this->getCaptchaService()->checkCode($hash, $value)) {
            $this->addError($GLOBALS['TL_LANG']['tossn_captcha']['error']);

            return '';
        }

        return $value;
    }
}
