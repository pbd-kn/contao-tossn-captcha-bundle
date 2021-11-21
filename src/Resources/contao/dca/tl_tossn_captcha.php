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

$GLOBALS['TL_DCA']['tl_tossn_captcha'] = [
    'config' => [
        'label' => 'tl_tossn_captcha',
        'dataContainer' => 'Table',
        'enableVersioning' => false,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(11) unsigned NOT NULL auto_increment',
        ],
        'hash' => [
            'eval' => [
                'doNotShow' => true,
            ],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'text' => [
            'eval' => [
                'doNotShow' => true,
            ],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'tstamp' => [
            'eval' => [
                'doNotShow' => true,
            ],
            'sql' => "int(11) unsigned NOT NULL default '0'",
        ],
    ],
];
