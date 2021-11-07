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
	protected $captchaImagePath = 'system/modules/tossn_captcha/assets/captcha';

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
	protected $backgroundImage = 'system/modules/tossn_captcha/resource/image/blank.png';

	/**
	 * @var string
	 */
	protected $captchaFont = 'system/modules/tossn_captcha/resource/font/default.ttf';

	/**
	 * @return void
	 */
	public function __construct() {
		$this->Config = \Contao\Config::getInstance();
		$this->setProperties();

		if (!is_dir(TL_ROOT.'/'.TL_FILES_URL.$this->captchaImagePath)) {
			mkdir(TL_ROOT.'/'.TL_FILES_URL.$this->captchaImagePath, 0777, true);
		}
		$this->Database = \Database::getInstance();
		$this->deleteOldEntries();
	}

	/**
	 * @return void
	 */
	protected function setProperties() {
		if ($this->Config->get('tc_length') && (int)$this->Config->get('tc_length') > 0) {
			$this->numChars = (int)$this->Config->get('tc_length');
		}
		if ($this->Config->get('tc_fontsize') && (int)$this->Config->get('tc_fontsize') > 0) {
			$this->fontSize = (int)$this->Config->get('tc_fontsize');
		}
		if ($this->Config->get('tc_chars')) {
			$this->charPool = $this->Config->get('tc_chars');
		}
		if ($this->Config->get('tc_bgimage') && $this->Config->get('tc_bgimage') != '') {
			$objFile = \FilesModel::findByPk((string)$this->Config->get('tc_bgimage'));
			if ($objFile && is_file(TL_ROOT.'/'.TL_FILES_URL.$objFile->path)) {
				$this->backgroundImage = $objFile->path;
			}
		}
		if ($this->Config->get('tc_font') && $this->Config->get('tc_font') != '') {
			$objFile = \FilesModel::findByPk((string)$this->Config->get('tc_font'));
			if ($objFile && is_file(TL_ROOT.'/'.TL_FILES_URL.$objFile->path)) {
				$this->captchaFont = $objFile->path;
			}
		}
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
				@unlink(TL_ROOT.'/'.TL_FILES_URL.$this->captchaImagePath.'/'.$data['hash'].'.png');
			}
		}

		$query = "DELETE FROM tl_tossn_captcha WHERE tstamp <  ? ";
		$datas = $this->Database->prepare($query)->execute($time);
	}

	/**
	 * @return void
	 */
	public function createCaptcha() {
		$blankImage = TL_ROOT.'/'.TL_FILES_URL.$this->backgroundImage;
		$imagesize = getimagesize($blankImage);

		switch (strtolower(substr($this->backgroundImage, strrpos($this->backgroundImage, '.') + 1))) {
			case 'png':
				$image = imagecreatefrompng($blankImage);
			break;

			case 'gif':
				$image = imagecreatefromgif($blankImage);
			break;

			default:
				$image = imagecreatefromjpeg($blankImage);
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

			$fontFile = TL_ROOT.'/'.TL_FILES_URL.$this->captchaFont;
			imagettftext($image, $this->fontSize, $angle, $x, $y, $color, $fontFile, $text{$i});
		}

		$imageName = TL_FILES_URL.$this->captchaImagePath.'/'.$hash.'.png';
		imagepng($image, TL_ROOT.'/'.$imageName);
		imagedestroy($image);

		$insert = array(
			'hash' => $hash,
			'text' => $text,
			'tstamp' => time()
		);
		$this->Database->prepare("INSERT INTO tl_tossn_captcha %s ")->set($insert)->execute();

		$this->lastImageName = $imageName;
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
		return $this->lastImageName;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->lastHash;
	}
}