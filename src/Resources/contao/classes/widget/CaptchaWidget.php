<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 *  (c) Leo Feyer
 *
 *  @license LGPL-3.0-or-later
 *  @copyright  Peter Broghammer 2020
 *  @author     Peter Broghammer (PBD)
 *
 *  @package TossnCaptcha
 *  @copyright Thorsten Gading  <info@tossn.de>
 *  @author Thorsten Gading <info@tossn.de>
 *
 *
 *  Porting TossnCaptcha to Contao 4.9
 *  Based on TossnCaptcha from Thorsten Gading
 *
 *  @package   pbd-kn/contao-tossncaptcha-bundle
 *  @author    Peter Broghammer <mail@pb-contao@gmx.de>
 *  @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 *  @copyright Peter Broghammer 2021-
 *
 *  Thorsten Gading TossnCaptcha package has been completely converted to contao 4.9
 */

namespace PBDKN\ContaoCaptchaBundle\Resources\contao\classes\widget;

class CaptchaWidget extends \Widget
{
    /**
     * @var string
     */
    protected $strTemplate = 'form_tossn_captcha';

    /**
     * The CSS class prefix.
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-text';

    /**
     * @var string
     */
    protected $captchaKey;

    /**
     * @var \TossnCaptcha\Service\CaptchaService
     */
    protected $CaptchaService;

    /**
     * @var \Config
     */
    protected $Config;

    /**
     * @param array $arrAttributes
     *
     * @return void
     */
    public function __construct($arrAttributes = null)
    {
        //$this->log("PBD -> constr. Captcha widget", __METHOD__, 'TL_GENERAL');
        parent::__construct($arrAttributes);
        $this->Config = \Contao\Config::getInstance();

        $this->arrAttributes['required'] = true;
        $this->arrConfiguration['mandatory'] = true;
        $this->CaptchaService = new \PBDKN\ContaoCaptchaBundle\Resources\contao\classes\service\CaptchaService();
        //$this->log("PBD .. constr. Captcha widget service created", __METHOD__, 'TL_GENERAL');
        $this->CaptchaService->createCaptcha();
        $this->arrConfiguration['captcha_hash'] = $this->CaptchaService->getHash();
        $this->arrConfiguration['captcha_image'] = $this->CaptchaService->getImageName();
        //$this->log("PBD <- constr. Captcha widget imagename ".$this->arrConfiguration['captcha_image'], __METHOD__, 'TL_GENERAL');
    }

    /**
     * @param array $atttibutes
     *
     * @return string
     */
    public function parse($atttibutes = null)
    {
        //$this->log("PBD .. Captcha widget parse ".$this->Config->get('tc_captchaimage'), __METHOD__, 'TL_GENERAL');
        if (!$this->Config->get('tc_captchaimage')) {
            return '';
        }

        return parent::parse($atttibutes);
    }

    /**
     * @return string
     */
    public function generate()
    {
        return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="text%s%s" value="%s"%s%s',
                        $this->type,
                        $this->strName,
                        $this->strId,
                        (('' !== $this->strClass) ? ' '.$this->strClass : ''),
                        specialchars($this->value),
                        $this->getAttributes(),
                        $this->strTagEnding).$this->addSubmit();
        //$this->log("PBD .. Captcha widget generate ".$res, __METHOD__, 'TL_GENERAL');
    }

    /**
     * @param string $varInput
     *
     * @return bool
     */
    protected function validator($varInput)
    {
        if (!$this->CaptchaService->checkCode(\Input::post($this->strName.'_hash'), $varInput)) {
            $this->addError($GLOBALS['TL_LANG']['tossn_captcha']['error']);
        }

        return parent::validator($varInput);
    }
}
