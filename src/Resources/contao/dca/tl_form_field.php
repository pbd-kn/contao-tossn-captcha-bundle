<?php
/**
 * @copyright Thorsten Gading  <info@tossn.de>
 * @author Thorsten Gading <info@tossn.de>
 * @license LGPL
 * @package TossnCaptcha
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['tossn_captcha'] = '{type_legend},type,name,label;'.
																	'{expert_legend:hide},class,'.
																	'{submit_legend},addSubmit';
