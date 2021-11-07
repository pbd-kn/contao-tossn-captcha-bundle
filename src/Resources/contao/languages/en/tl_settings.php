<?php
/**
 * @copyright Thorsten Gading  <info@tossn.de>
 * @author Thorsten Gading <info@tossn.de>
 * @license LGPL
 * @package TossnCaptcha
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');

$GLOBALS['TL_LANG']['tl_settings']['tossn_captcha_legend'] = 'Captcha Settings';
 
$GLOBALS['TL_LANG']['tl_settings']['tc_captchaimage'] = array('Activate captcha', 'Please choose whether the captcha should be activated.');
$GLOBALS['TL_LANG']['tl_settings']['tc_length'] = array('Number of characters (default value: 5)', 'Please enter how many characters the captcha should show.');
$GLOBALS['TL_LANG']['tl_settings']['tc_fontsize'] = array('Font size (default value: 30)', 'Please enter the captchas characters font size. Values, which make sense, are between 20 and 40.');
$GLOBALS['TL_LANG']['tl_settings']['tc_chars'] = array('Characters', 'Please select which characters the captcha should show.');
$GLOBALS['TL_LANG']['tl_settings']['tc_bgimage'] = array('Captcha Vorlage', 'Choose an image, which should be used as template for the captcha. Allowed file types are JPEG, GIF and PNG.');
$GLOBALS['TL_LANG']['tl_settings']['tc_font'] = array('Font', 'Select a font as TTF file.');

$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['num'] = '0-9';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphalower'] = 'a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphaupper'] = 'A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alpha'] = 'a-zA-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphalower'] = '0-9a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphaupper'] = '0-9A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalpha'] = '0-9a-zA-Z';
