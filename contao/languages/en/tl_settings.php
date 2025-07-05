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


$GLOBALS['TL_LANG']['tl_settings']['tossn_captcha_legend'] = 'Captcha Settings';

$GLOBALS['TL_LANG']['tl_settings']['tc_captchaimage'] = ['Activate captcha', 'Please choose whether the captcha should be activated.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_length'] = ['Number of characters (default value: 5)', 'Please enter how many characters the captcha should show.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_fontsize'] = ['Font size (default value: 30)', 'Please enter the captchas characters font size. Values, which make sense, are between 20 and 40.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_chars'] = ['Characters', 'Please select which characters the captcha should show.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_bgimage'] = ['Captcha Vorlage', 'Choose an image, which should be used as template for the captcha. Allowed file types are JPEG, GIF and PNG.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_font'] = ['Font', 'Select a font as TTF file.'];

$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['num'] = '0-9';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphalower'] = 'a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphaupper'] = 'A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alpha'] = 'a-zA-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphalower'] = '0-9a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphaupper'] = '0-9A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalpha'] = '0-9a-zA-Z';
