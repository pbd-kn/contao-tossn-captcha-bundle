<?php

namespace PBDKN\ContaoCaptchaBundle\Service;

use Contao\System;
use Contao\Config;
use Contao\Database;
use Psr\Log\LoggerInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CaptchaService
{
    private string $rootDir;
    private Config $config;
    private Database $database;
    /**
     * @var \Database
     */
    protected $Database;


    /**
     * @var string
     */
    protected $lastImageName = '';

    /**
     * @var string
     */
    protected $lastHash = '';

    /**
     * @var string
     */
    protected $charsUpper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var string
     */
    protected $charsLower = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * @var string
     */
    protected $charsNum = '0123456789';

    /**
     * @var array
     */
    protected $colors = [
        [33, 33, 33],
        [55, 55, 55],
        [77, 77, 77],
        [99, 99, 99],
        [111, 111, 111],
        [133, 133, 133],
    ];

    /**
     * @var string
     */
    //protected $captchaImagePath = 'system/modules/tossn_captcha/assets/captcha';
    protected $captchaImagePath = '';        // Path in dem die vergebenen Images liegen
    /**
     * @var string
     */
//    protected $vendorPath = 'vendor/pbd-kn/contao-tossncaptcha-bundle/';
    protected $bundlePublicPath = '';
    /**
     * @var int
     */
    protected $numChars = 5;

    /**
     * @var int
     */
    protected $fontSize = 30;

    /**
     * @var string
     */
    protected $charPool = 'numalpha';

    /**
     * @var string
     */
    protected $VendorbackgroundImage = '';                     // Backgound Image
    protected $VendorblankImage = '';                          // Default background Image

    /**
     * @var string
     */
    protected $VendorcaptchaFont = '';
    protected $files_url = '';
    private LoggerInterface $logger;
    private bool $debug = false;


    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->rootDir = $params->get('kernel.project_dir') . '/';
        $this->config = Config::getInstance();      // ? kein Autowiring
        $this->database = Database::getInstance();  // ? ebenfalls Singleton
        $this->logger = $logger;
        $this->debug = $params->get('kernel.debug');
        $this->bundlePublicPath = $params->get('kernel.project_dir') . '/web/bundles/contaocaptcha';
        $this->setProperties(); // ? Hier aufrufen!
        $this->deleteOldEntries();
    }

    /**
     * @param string $hash
     * @param string $text
     *
     * @return bool
     */
    public function checkCode($hash, $text)
    {
        $query = 'SELECT text FROM tl_tossn_captcha WHERE hash = ? ';
        $data = $this->database->prepare($query)->execute($hash)->fetchAssoc();

        if ('alpha' !== $this->charPool && 'numalpha' !== $this->charPool) {
            if (isset($data['text'])) {
                $data['text'] = strtolower($data['text']);
                $text = strtolower($text);
            } else {
              $text="";
            }
        }

        if (isset($data['text']) && '' !== trim($data['text']) && trim($data['text']) === trim($text)) {
            return true;
        }

        return false;
    }

    public function createCaptcha(): void
    {
        //System::log('PBD -> Captcha Service createCaptcha blankimage '.$this->rootDir.$this->VendorblankImage, __METHOD__, 'TL_GENERAL');
        //\System::log('PBD .. Captcha Service createCaptcha VendorbackgroundImage '.$this->rootDir.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
        //$imagePath = realpath($this->rootDir . $this->VendorbackgroundImage);
        //$imagePath = $this->rootDir . $this->VendorbackgroundImage;
        $imagePath = $this->VendorbackgroundImage;

        if ($this->debug) $this->logger->info('CaptchaService: Hintergrundbild geladen: imagePath ' . $imagePath . ' VendorbackgroundImage '.$this->VendorbackgroundImage);

        $imagesize = getimagesize($imagePath);

        switch (strtolower(substr($this->VendorbackgroundImage, strrpos($this->VendorbackgroundImage, '.') + 1))) {
            case 'png':
                $image = imagecreatefrompng($this->VendorbackgroundImage);
            break;

            case 'gif':
                $image = imagecreatefromgif($this->VendorbackgroundImage);
            break;

            default:
                $image = imagecreatefromjpeg($this->VendorbackgroundImage);
                if (false === $image) {
                    //\System::log('PBD .. Captcha Service createCaptcha create jpg image false ', __METHOD__, 'TL_GENERAL');
                }
            break;
        }

        $hash = $this->createHash();

        $text = $this->getCharacters();
        $beginX = ceil(($imagesize[0] / 2) - (($this->fontSize / 14) * (\strlen($text) * imagefontwidth($this->fontSize) / 2)));
        $beginY = ceil(($imagesize[1] + imagefontheight($this->fontSize)) / 2);

        $count = \strlen($text);

        for ($i = 0; $i < $count; ++$i) {
            $color_array = $this->getColor();
            $color = imagecolorallocate($image, $color_array[0], $color_array[1], $color_array[2]);

            $x = $beginX + ceil($i * $this->fontSize / 1.5);
            $y = $beginY + random_int(-4, 4);
            $angle = random_int(-10, 10);

            $fontFile = $this->VendorcaptchaFont;
            if ($this->debug) $this->logger->info('PBD .. Captcha Service createCaptcha fontFile '.$fontFile.' x '.$x.' y '.$y);
            imagettftext($image, $this->fontSize, $angle,(int) $x, (int)$y, $color, $fontFile, $text[$i]);
        }

        //$imageName = TL_FILES_URL.$this->captchaImagePath.'/'.$hash.'.png';
        //imagepng($image, TL_ROOT.'/'.$imageName);

        $imageName = $this->captchaImagePath.$hash.'.png';
        if ($this->debug) $this->logger->info('PBD .. Captcha Service createCaptcha imageName neu'.$imageName);
        if (imagepng($image, $imageName)) {
            //\System::log('PBD .. Captcha Service createCaptcha return true ', __METHOD__, 'TL_GENERAL');
        }
        //\System::log('PBD .. Captcha Service createCaptcha return false ', __METHOD__, 'TL_GENERAL');

        imagedestroy($image);       // ist nun gespeichert unter $this->rootDir.'web/.$this->captchaImagePath.$hash.'.png'

        $insert = [
            'hash' => $hash,
            'text' => $text,
            'tstamp' => time(),
        ];
        $this->database->prepare('INSERT INTO tl_tossn_captcha %s ')->set($insert)->execute();   // erzeuge Eintrag in DB

        //$this->lastImageName = $this->captchaImagePath.$hash.'.png';   // wird so dem Template uebergeben, damit der Zugriff ueber <img src=... klappt
        $this->lastImageName = '/bundles/contaocaptcha/assets/captcha/'.$hash.'.png';   // wird so dem Template uebergeben, damit der Zugriff ueber <img src=... klappt
        $this->lastHash = $hash;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        //\System::log('PBD .. Captcha Service getImageName imageName '.$this->lastImageName, __METHOD__, 'TL_GENERAL');
        return $this->lastImageName;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->lastHash;
    }

    protected function setProperties(): void
    {
        $this->VendorblankImage = $this->bundlePublicPath.'/resource/image/blank.jpg';    // PBD png File bringt Fehler
        $this->VendorcaptchaFont = $this->bundlePublicPath.'/resource/font/default.ttf';
        $this->captchaImagePath = $this->bundlePublicPath.'/assets/captcha/'; // beim erzeugen des Bildes wird es unter web/... abgelegt
        $this->VendorbackgroundImage = $this->VendorblankImage;               // default
        if ($this->debug) $this->logger->info('PBD .. Captcha Service setProperties captchaImagePath '.$this->captchaImagePath.' bundlepublicPat '.$this->bundlePublicPath);

        if ($this->config->get('tc_length') && (int) $this->config->get('tc_length') > 0) {
            $this->numChars = (int) $this->config->get('tc_length');
        }
        if ($this->config->get('tc_fontsize') && (int) $this->config->get('tc_fontsize') > 0) {
            $this->fontSize = (int) $this->config->get('tc_fontsize');
        }
        if ($this->config->get('tc_chars')) {
            $this->charPool = $this->config->get('tc_chars');
        }
        if ($this->debug) $this->logger->info('PBD .. Captcha Service setProperties1 VendorbackgroundImage '.$this->VendorbackgroundImage);

        if ($this->config->get('tc_bgimage') && '' !== $this->config->get('tc_bgimage')) {
            //\System::log('PBD .. Captcha Service setProperties tc_bgimage '.(string)$this->config->get('tc_bgimage'), __METHOD__, 'TL_GENERAL');
            $objFile = \FilesModel::findByPk((string) $this->config->get('tc_bgimage'));
            //\System::log('PBD .. Captcha Service setProperties objFile Path '.$objFile->path, __METHOD__, 'TL_GENERAL');
            if ($objFile && is_file($this->rootDir.$objFile->path)) {
                //\System::log('PBD .. Captcha Service setProperties objFile found ', __METHOD__, 'TL_GENERAL');
                $this->VendorbackgroundImage = $objFile->path;
            } else {
                \System::log('Captcha Service setProperties background file NOT found ', __METHOD__, 'TL_ERROR');
            }
        }
        //\System::log('PBD .. Captcha Service setProperties2 VendorbackgroundImage '.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
        if ($this->config->get('tc_font') && '' !== $this->config->get('tc_font')) {
            $objFile = \FilesModel::findByPk((string) $this->config->get('tc_font'));
            if ($objFile && is_file($this->rootDir.'/'.TL_FILES_URL.$objFile->path)) {
                $this->captchaFont = $objFile->path;
            }
        }
        //\System::log('PBD <- Captcha Service setProperties VendorbackgroundImage: '.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
    }

    protected function deleteOldEntries(): void
    {
        $time = time() - 600;
        $query = 'SELECT hash FROM tl_tossn_captcha WHERE tstamp < ? ';
        $datas = $this->database->prepare($query)->execute($time)->fetchAllAssoc();
        if (\is_array($datas) && !empty($datas)) {
            foreach ($datas as $data) {
                $fi = $this->captchaImagePath.$data['hash'].'.png';
                //if ($this->debug) $this->logger->debug("PBD .. Captcha Service deleteOldEntries File $fi captchaImagePath ".$this->captchaImagePath);
                //\System::log('PBD .. Captcha Service deleteOldEntries delete File '.$fi, __METHOD__, 'TL_GENERAL');
                if (unlink($fi)) {
                    //\System::log('PBD .. Captcha Service deleteOldEntries delete File OK', __METHOD__, 'TL_GENERAL');
                } else {
                    if ($this->debug) $this->logger->debug("PBD .. Captcha Service deleteOldEntries File $fi unlinkerror ");
                }
            }
        }

        $query = 'DELETE FROM tl_tossn_captcha WHERE tstamp <  ? ';
        $datas = $this->database->prepare($query)->execute($time);
    }

    /**
     * @return array
     */
    protected function getColor()
    {
        return $this->colors[random_int(0, \count($this->colors) - 1)];
    }

    /**
     * @return string
     */
    protected function getCharacters()
    {
        switch ($this->charPool) {
            case 'num':
                $chars = $this->charsNum;
            break;

            case 'alphalower':
                $chars = $this->charsLower;
            break;

            case 'alphaupper':
                $chars = $this->charsUpper;
            break;

            case 'alpha':
                $chars = $this->charsUpper.$this->charsLower;
            break;

            case 'numalphalower':
                $chars = $this->charsNum.$this->charsLower;
            break;

            case 'numalphaupper':
                $chars = $this->charsNum.$this->charsUpper;
            break;

            default:
                $chars = $this->charsNum.$this->charsUpper.$this->charsLower;
            break;
        }

        $text = '';
        for ($i = 0; $i < $this->numChars; ++$i) {
            $text .= substr($chars, random_int(0, \strlen($chars) - 1), 1);
        }

        return $text;
    }

    /**
     * @return string
     */
    protected function createHash()
    {
        return md5(random_int(0, 99).date('YmdHis').random_int(0, 99));
    }
}
