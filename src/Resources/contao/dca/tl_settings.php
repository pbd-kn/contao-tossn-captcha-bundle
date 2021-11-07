<?php
/**
 * @copyright Thorsten Gading  <info@tossn.de>
 * @author Thorsten Gading <info@tossn.de>
 * @license LGPL
 * @package TossnCaptcha
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');
 
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{tossn_captcha_legend},tc_captchaimage,tc_length,tc_fontsize,tc_chars,tc_bgimage,tc_font';

$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_captchaimage'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_captchaimage'],
	'inputType' => 'checkbox',
	'eval' => array(
		'tl_class' => 'clr',
	),
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_length'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_length'],
	'default' => 5,
	'inputType' => 'text',
	'eval' => array(
		'rgxp' => 'digit',
		'tl_class' => 'clr',
	),
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_fontsize'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_fontsize'],
	'default' => 30,
	'inputType' => 'text',
	'eval' => array(
		'rgxp' => 'digit',
		'tl_class' => 'clr',
	),
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_chars'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_chars'],
	'inputType' => 'select',
	'options' => array(
		'num',
		'alphalower',
		'alphaupper',
		'alpha',
		'numalphalower',
		'numalphaupper',
		'numalpha',
	),
	'reference' => &$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel'],
	'eval' => array(
		'tl_class' => 'clr',
	),
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_bgimage'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_bgimage'],
	'inputType' => 'fileTree',
	'eval' => array(
		'files' => true,
		'filesOnly' => true,
		'extensions' => 'jpg,png,gif',
		'fieldType' => 'radio',
		'tl_class' => 'clr',
	),
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['tc_font'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_settings']['tc_font'],
	'inputType' => 'fileTree',
	'eval' => array(
		'files' => true,
		'filesOnly' => true,
		'extensions' => 'ttf',
		'fieldType' => 'radio',
		'tl_class' => 'clr',
	),
);
