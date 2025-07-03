<?php

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

