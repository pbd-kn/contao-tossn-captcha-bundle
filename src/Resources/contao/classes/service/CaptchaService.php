<?php

namespace PBDKN\ContaoCaptchaBundle\Resources\contao\classes\service;


class CaptchaService {

	/**
	 * @var \Database
	 */
	protected $Database;

	/**
	 * @var \Config
	 */
	protected $Config;

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
	protected $colors = array(
		array(33, 33, 33),
		array(55, 55, 55),
		array(77, 77, 77),
		array(99, 99, 99),
		array(111, 111, 111),
		array(133, 133, 133),
	);

	/**
	 * @var string
	 */
	//protected $captchaImagePath = 'system/modules/tossn_captcha/assets/captcha';
	protected $captchaImagePath = '';        // Path in dem die vergebenen Images liegen
	/**
	 * @var string
	 */
    protected $vendorPath = 'vendor/pbd-kn/contao-tossncaptcha-bundle/';
	/**
	 * @var integer
	 */
	protected $numChars = 5;

	/**
	 * @var integer
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
    protected $rootDir='';

	/**
	 * @var string
	 */
	protected $VendorcaptchaFont = '';
    protected $files_url ='';
	/**
	 * @return void
	 */
	public function __construct() {
//\System::log('PBD -> constr. Captcha Service --------------------------------------------', __METHOD__, 'TL_GENERAL');
        $container = \System::getContainer();
        $this->rootDir = $container->getParameter('kernel.project_dir').'/';
//\System::log('PBD .. Captcha Service rootDir '.$this->rootDir, __METHOD__, 'TL_GENERAL');

		$this->Config = \Contao\Config::getInstance();
		$this->setProperties();

		if (!is_dir($this->rootDir.'web/'.$this->captchaImagePath)) {
// imagepath enthaelt die schon vergebenen Images
//\System::log('PBD .. constr. make dir createimgepath '.'web/'.$this->rootDir.$this->captchaImagePath, __METHOD__, 'TL_GENERAL');
			mkdir($this->rootDir.'web/'.$this->captchaImagePath, 0777, true);
		}
		$this->Database = \Database::getInstance();
		$this->deleteOldEntries();
//\System::log('PBD <- constr. Captcha Service --------------------------------------------', __METHOD__, 'TL_GENERAL');
	}

	/**
	 * @return void
	 */
	protected function setProperties() {
//\System::log("PBD -> Captcha Service setProperties", __METHOD__, 'TL_GENERAL');
        $this->VendorblankImage  = $this->vendorPath.'src/Resources/contao/resource/image/blank.png';
		$this->VendorcaptchaFont = $this->vendorPath.'src/Resources/contao/resource/font/default.ttf';
		$this->captchaImagePath = 'bundles/contaocaptcha/assets/captcha/'; // beim erzeugen des Bildes wird es unter web/... abgelegt
        $this->VendorbackgroundImage =$this->VendorblankImage;               // default
//\System::log('PBD .. Captcha Service setProperties captchaImagePath '.$this->captchaImagePath, __METHOD__, 'TL_GENERAL');

		if ($this->Config->get('tc_length') && (int)$this->Config->get('tc_length') > 0) {
			$this->numChars = (int)$this->Config->get('tc_length');
		}
		if ($this->Config->get('tc_fontsize') && (int)$this->Config->get('tc_fontsize') > 0) {
			$this->fontSize = (int)$this->Config->get('tc_fontsize');
		}
		if ($this->Config->get('tc_chars')) {
			$this->charPool = $this->Config->get('tc_chars');
		}
//\System::log('PBD .. Captcha Service setProperties1 VendorbackgroundImage '.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
		if ($this->Config->get('tc_bgimage') && $this->Config->get('tc_bgimage') != '') {
//\System::log('PBD .. Captcha Service setProperties tc_bgimage '.(string)$this->Config->get('tc_bgimage'), __METHOD__, 'TL_GENERAL');
			$objFile = \FilesModel::findByPk((string)$this->Config->get('tc_bgimage'));
//\System::log('PBD .. Captcha Service setProperties objFile Path '.$objFile->path, __METHOD__, 'TL_GENERAL');
			if ($objFile && is_file($this->rootDir.$objFile->path)) {
//\System::log('PBD .. Captcha Service setProperties objFile found ', __METHOD__, 'TL_GENERAL');
				$this->VendorbackgroundImage = $objFile->path;
			} else {
\System::log('Captcha Service setProperties background file NOT found ', __METHOD__, 'TL_ERROR');
            }
		}
//\System::log('PBD .. Captcha Service setProperties2 VendorbackgroundImage '.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
		if ($this->Config->get('tc_font') && $this->Config->get('tc_font') != '') {
			$objFile = \FilesModel::findByPk((string)$this->Config->get('tc_font'));
			if ($objFile && is_file($this->rootDir.'/'.TL_FILES_URL.$objFile->path)) {
				$this->captchaFont = $objFile->path;
			} 
		}
//\System::log('PBD <- Captcha Service setProperties VendorbackgroundImage: '.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
	}

	/**
	 * @param string $hash
	 * @param string $text
	 * @return boolean
	 */
	public function checkCode($hash, $text) {
		$query = "SELECT text FROM tl_tossn_captcha WHERE hash = ? ";
		$data = $this->Database->prepare($query)->execute($hash)->fetchAssoc();

		if ($this->charPool != 'alpha' && $this->charPool != 'numalpha') {
			$data['text'] = strtolower($data['text']);
			$text = strtolower($text);
		}

		if (trim($data['text']) != '' && trim($data['text']) == trim($text)) {
			return true;
		}

		return false;
	}

	/**
	 * @return void
	 */
	protected function deleteOldEntries() {
		$time = time() - 600;
		$query = "SELECT hash FROM tl_tossn_captcha WHERE tstamp < ? ";
		$datas = $this->Database->prepare($query)->execute($time)->fetchAllAssoc();
		if (is_array($datas) && !empty($datas)) {
			foreach ($datas as $data) {
                $fi=$this->rootDir.'web/'.$this->captchaImagePath.'/'.$data['hash'].'.png';
//\System::log('PBD .. Captcha Service deleteOldEntries delete File '.$fi, __METHOD__, 'TL_GENERAL');
				if (unlink($fi)) {
//\System::log('PBD .. Captcha Service deleteOldEntries delete File OK', __METHOD__, 'TL_GENERAL');
                } else {
//\System::log('PBD .. Captcha Service deleteOldEntries delete File NOK', __METHOD__, 'TL_GENERAL');
                }
			}
		}

		$query = "DELETE FROM tl_tossn_captcha WHERE tstamp <  ? ";
		$datas = $this->Database->prepare($query)->execute($time);
	}

	/**
	 * @return void
	 */
	public function createCaptcha() {
//\System::log('PBD -> Captcha Service createCaptcha blankimage '.$this->rootDir.$this->VendorblankImage, __METHOD__, 'TL_GENERAL');
//\System::log('PBD .. Captcha Service createCaptcha VendorbackgroundImage '.$this->rootDir.$this->VendorbackgroundImage, __METHOD__, 'TL_GENERAL');
		$imagesize = getimagesize($this->rootDir.$this->VendorbackgroundImage);

		switch (strtolower(substr($this->VendorbackgroundImage, strrpos($this->VendorbackgroundImage, '.') + 1))) {
			case 'png':
				$image = imagecreatefrompng($this->rootDir.$this->VendorbackgroundImage);
			break;

			case 'gif':
				$image = imagecreatefromgif($this->rootDir.$this->VendorbackgroundImage);
			break;

			default:
				$image = imagecreatefromjpeg($this->rootDir.$this->VendorbackgroundImage);
                if (false === $image) {
//\System::log('PBD .. Captcha Service createCaptcha create jpg image false ', __METHOD__, 'TL_GENERAL');
                }
			break;
		}

		$hash = $this->createHash();

		$text = $this->getCharacters();
		$beginX = ceil(($imagesize[0] / 2) - (($this->fontSize / 14) * (strlen($text) * imagefontwidth($this->fontSize) / 2)));
		$beginY = ceil(($imagesize[1] + imagefontheight($this->fontSize)) / 2);

		$count = strlen($text);

		for ($i = 0; $i < $count; $i++) {
			$color_array = $this->getColor();
			$color = imagecolorallocate($image, $color_array[0], $color_array[1], $color_array[2]);

			$x = $beginX + ceil($i * $this->fontSize / 1.5);
			$y = $beginY + rand(-4, 4);
			$angle = rand(-10, 10);

			$fontFile = $this->rootDir.$this->VendorcaptchaFont;
//\System::log('PBD .. Captcha Service createCaptcha fontFile '.$fontFile.' x '.$x.' y '.$y, __METHOD__, 'TL_GENERAL');
			imagettftext($image, $this->fontSize, $angle, $x, $y, $color, $fontFile, $text{$i});
		}

		//$imageName = TL_FILES_URL.$this->captchaImagePath.'/'.$hash.'.png';
		//imagepng($image, TL_ROOT.'/'.$imageName);

		$imageName = $this->rootDir.'web/'.$this->captchaImagePath.$hash.'.png';
//\System::log('PBD .. Captcha Service createCaptcha imageName '.$imageName, __METHOD__, 'TL_GENERAL');
		if (imagepng($image,$imageName)){
//\System::log('PBD .. Captcha Service createCaptcha return true ', __METHOD__, 'TL_GENERAL');
        }else {
//\System::log('PBD .. Captcha Service createCaptcha return false ', __METHOD__, 'TL_GENERAL');
        }
		imagedestroy($image);       // ist nun gespeichert unter $this->rootDir.'web/.$this->captchaImagePath.$hash.'.png' 

		$insert = array(
			'hash' => $hash,
			'text' => $text,
			'tstamp' => time()
		);
		$this->Database->prepare("INSERT INTO tl_tossn_captcha %s ")->set($insert)->execute();   // erzeuge Eintrag in DB

        $this->lastImageName = $this->captchaImagePath.$hash.'.png';   // wird so dem Template uebergeben, damit der Zugriff ueber <img src=... klappt
		$this->lastHash = $hash;
	}

	/**
	 * @return array
	 */
	protected function getColor() {
		return $this->colors[rand(0, count($this->colors) - 1)];
	}

	/**
	 * @return string
	 */
	protected function getCharacters() {
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
		for ($i = 0; $i < $this->numChars; $i++) {
			$text .= substr($chars, rand(0, strlen($chars) - 1), 1);
		}

		return $text;
	}

	/**
	 * @return string
	 */
	protected function createHash() {
		return md5(rand(0, 99).date('YmdHis').rand(0, 99));
	}

	/**
	 * @return string
	 */
	public function getImageName() {
//\System::log('PBD .. Captcha Service getImageName imageName '.$this->lastImageName, __METHOD__, 'TL_GENERAL');
		return $this->lastImageName;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->lastHash;
	}
}