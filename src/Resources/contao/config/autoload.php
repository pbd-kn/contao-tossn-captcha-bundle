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

/*
 * Register the classes
 */
/*
\ClassLoader::addClasses(array(
    'TossnCaptcha\Widget\CaptchaWidget' => 'system/modules/tossn_captcha/classes/widget/CaptchaWidget.php',
    'TossnCaptcha\Service\CaptchaService' => 'system/modules/tossn_captcha/classes/service/CaptchaService.php',
));
*/

/*
 * Register the templates
 */
\TemplateLoader::addFiles([
    //'form_tossn_captcha' => 'system/modules/tossn_captcha/templates/forms',
    'form_tossn_captcha' => 'PBDKN/Efgco4/Resources/contao/templates/forms',
]);
