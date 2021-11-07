<?php
/**
 * @copyright Thorsten Gading  <info@tossn.de>
 * @author Thorsten Gading <info@tossn.de>
 * @license LGPL
 * @package TossnCaptcha
 */
 
$Config = \Contao\Config::getInstance();
if ($Config->get('tc_captchaimage')) {
	$GLOBALS['TL_FFL']['tossn_captcha'] = 'TossnCaptcha\Widget\CaptchaWidget';
}