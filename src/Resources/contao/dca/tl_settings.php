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

if (!\defined('TL_ROOT')) {
    exit('You can not access this file directly!');
}

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{tossn_captcha_legend},tc_captchaimage,tc_length,tc_fontsize,tc_chars,tc_bgimage,tc_font';

$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_captchaimage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_captchaimage'],
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class' => 'clr',
    ],
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_length'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_length'],
    'default' => 5,
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'digit',
        'tl_class' => 'clr',
    ],
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_fontsize'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_fontsize'],
    'default' => 30,
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'digit',
        'tl_class' => 'clr',
    ],
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_chars'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_chars'],
    'inputType' => 'select',
    'options' => [
        'num',
        'alphalower',
        'alphaupper',
        'alpha',
        'numalphalower',
        'numalphaupper',
        'numalpha',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel'],
    'eval' => [
        'tl_class' => 'clr',
    ],
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_bgimage'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_bgimage'],
    'inputType' => 'fileTree',
    'eval' => [
        'files' => true,
        'filesOnly' => true,
        'extensions' => 'jpg,png,gif',
        'fieldType' => 'radio',
        'tl_class' => 'clr',
    ],
];
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_font'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_font'],
    'inputType' => 'fileTree',
    'eval' => [
        'files' => true,
        'filesOnly' => true,
        'extensions' => 'ttf',
        'fieldType' => 'radio',
        'tl_class' => 'clr',
    ],
];
