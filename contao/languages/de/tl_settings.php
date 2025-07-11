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



$GLOBALS['TL_LANG']['tl_settings']['tossn_captcha_legend'] = 'Captcha Einstellungen';

$GLOBALS['TL_LANG']['tl_settings']['tc_captchaimage'] = ['Captcha aktivieren', 'Bitte wählen Sie aus, ob das Captcha innerhalb des Formulargenerators aktiv ist.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_length'] = ['Anzahl der Zeichen (Standardwert: 5)', 'Bitte geben Sie an, wieviele Zeichen das Captcha anzeigen soll.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_fontsize'] = ['Schriftgröße (Standardwert: 30)', 'Bitte geben Sie die Schriftgröße, der auf dem Captcha dargestellten Zeichen, an. Sinnvoll Werte liegen - je nach Größe des Captcha-Bildes - zwischen 20 und 40'];
$GLOBALS['TL_LANG']['tl_settings']['tc_chars'] = ['Zeichen', 'Bitte wählen Sie aus, welche Zeichen im Captcha enthalten sein sollen.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_bgimage'] = ['Captcha Vorlage', 'Wählen Sie ein Bild aus, welches als Vorlage für das Captcha dienen soll. Erlaubte Dateitypen sind JPEG, GIF und PNG.'];
$GLOBALS['TL_LANG']['tl_settings']['tc_font'] = ['Schriftart', 'Wählen Sie eine Schriftart als TTF Datei aus.'];

$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['num'] = '0-9';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphalower'] = 'a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alphaupper'] = 'A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['alpha'] = 'a-zA-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphalower'] = '0-9a-z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalphaupper'] = '0-9A-Z';
$GLOBALS['TL_LANG']['tl_settings']['tc_charslabel']['numalpha'] = '0-9a-zA-Z';
