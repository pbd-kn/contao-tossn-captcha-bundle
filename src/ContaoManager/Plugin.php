<?php

declare(strict_types=1);



namespace PBDKN\ContaoCaptchaBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use PBDKN\ContaoCaptchaBundle\ContaoCaptchaBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoCaptchaBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])),
               ];
    }
}
