<?php

declare(strict_types=1);


namespace PBDKN\ContaoCaptchaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoCaptchaBundle extends Bundle
{
        public function __construct()
        {
            echo "constructor PBDKN\ContaoCaptchaBundle\ContaoCaptchaBundle";      // PBD ob das geht ???
        }

}
