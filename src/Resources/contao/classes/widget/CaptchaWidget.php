<?php

namespace PBDKN\ContaoCaptchaBundle\Resources\contao\classes\widget;


class CaptchaWidget extends \Widget {

	/**
	 * @var string
	 */
	protected $strTemplate = 'form_tossn_captcha';

	/**
	 * The CSS class prefix
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
	 * @return void
	 */
	public function __construct($arrAttributes = null) {
$this->log("PBD -> constr. Captcha widget", __METHOD__, 'TL_GENERAL');
$kasd=;
		parent::__construct($arrAttributes);
		$this->Config = \Contao\Config::getInstance();

		$this->arrAttributes['required'] = true;
		$this->arrConfiguration['mandatory'] = true;
		$this->CaptchaService = new \PBDKN\ContaoCaptchaBundle\Resources\contao\classes\service\CaptchaService;
		$this->CaptchaService->createCaptcha();
		$this->arrConfiguration['captcha_hash'] = $this->CaptchaService->getHash();
		$this->arrConfiguration['captcha_image'] = $this->CaptchaService->getImageName();
$this->log("PBD <- constr. Captcha widget", __METHOD__, 'TL_GENERAL');
	}

	/**
	 * @param string $varInput
	 * @return bool
	 */
	protected function validator($varInput) {
		if (!$this->CaptchaService->checkCode(\Input::post($this->strName.'_hash'), $varInput)) {
			$this->addError($GLOBALS['TL_LANG']['tossn_captcha']['error']);
		}

		return parent::validator($varInput);
	}

	/**
	 * @param array $atttibutes
	 * @return string
	 */
	public function parse($atttibutes = null) {
//		if (!$this->Config->get('tc_captchaimage')) {
//			return '';
//		}

		return parent::parse($atttibutes);
	}

	/**
	 * @return string
	 */
	public function generate() {
		return sprintf('<input type="%s" name="%s" id="ctrl_%s" class="text%s%s" value="%s"%s%s',
						$this->type,
						$this->strName,
						$this->strId,
						(($this->strClass != '') ? ' ' . $this->strClass : ''),
						specialchars($this->value),
						$this->getAttributes(),
						$this->strTagEnding) . $this->addSubmit();
	}

}