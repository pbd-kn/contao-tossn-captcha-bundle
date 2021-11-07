<?php
/**
 * @copyright Thorsten Gading  <info@tossn.de>
 * @author Thorsten Gading <info@tossn.de>
 * @license LGPL
 * @package TossnCaptcha
 */

if (!defined('TL_ROOT')) die('You can not access this file directly!');

$GLOBALS['TL_DCA']['tl_tossn_captcha'] = array(
	'config' => array(
		'label' => 'tl_tossn_captcha',
		'dataContainer' => 'Table',
		'enableVersioning' => false,
		'sql' => array(
			'keys' => array(
				'id' => 'primary',
			),
		),
	),
	'fields' => array(
		'id' => array(
			'sql' => "int(11) unsigned NOT NULL auto_increment",
		),
		'hash' => array(
			'eval' => array(
				'doNotShow' => true,
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'text' => array(
			'eval' => array(
				'doNotShow' => true,
			),
			'sql' => "varchar(255) NOT NULL default ''",
		),
		'tstamp' => array(
			'eval' => array(
				'doNotShow' => true,
			),
			'sql' => "int(11) unsigned NOT NULL default '0'",
		),
	),
);