<?php

class MotionCaptcha
{

	/**
	 * @var int
	 */
	protected $minimumScore = 5;

	/**
	 * @var MotionCaptcha
	 */
	static protected $_instance = null;

	/**
	 * @var phpDollar
	 */
	protected $dollar;

	protected function __construct()
	{
		require_once 'library/phpDollar/phpDollar.php';
		$this->dollar = new phpDollar;
		$this->dollar->removeTemplate('left square bracket');
		$this->dollar->removeTemplate('right square bracket');
		$this->dollar->removeTemplate('left curly brace');
	}

	protected function __clone() {}

	/**
	 * @static
	 * @return MotionCaptcha
	 */
	static public function getInstance()
	{
		if (self::$_instance === null) {
			return self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * @static
	 *
	 * @param string $canvas which canvas are we validating up against?
	 * @param string $points JSON string of the points
	 */
	public function validate($uniqueid, $points)
	{
		$points = json_decode($points, true);
		switch (json_last_error()) {
			case JSON_ERROR_NONE: break;
			case JSON_ERROR_DEPTH: throw new InvalidArgumentException('Maximum stack depth exceeded'); break;
			case JSON_ERROR_STATE_MISMATCH: throw new InvalidArgumentException('Underflow or the modes mismatch'); break;
			case JSON_ERROR_CTRL_CHAR: throw new InvalidArgumentException('Unexpected control character found'); break;
			case JSON_ERROR_SYNTAX: throw new InvalidArgumentException('Syntax error, malformed JSON'); break;
			case JSON_ERROR_UTF8: throw new InvalidArgumentException('Malformed UTF-8 characters, possibly incorrectly encoded'); break;
			default: throw new InvalidArgumentException('Unknown error'); break;
		}

		array_walk($points, function(&$item, $key) {
			$item['x'] = $item['X'];
			$item['y'] = $item['Y'];
			unset($item['X']);
			unset($item['Y']);
		});

		$usedCanvas = $this->dollar->recognizeStroke($points);

		if ($usedCanvas['strokeName'] == $this->getUsedTemplate($uniqueid) && $usedCanvas['strokeScore'] >= $this->minimumScore) {
			return true;
		} else {
			return false;
		}

	}

	public function getRandomTemplate()
	{
		$templates = $this->dollar->getTemplates();
		if (is_array($templates)) {
			$random = array_rand($templates, 1);
			return $templates[$random];
		}
		return false;
	}

	public function setSession($uniqueid)
	{
		$template = $this->getRandomTemplate();
		$_SESSION['motioncaptcha'][$uniqueid]['canvas'] = $template['templName'];
		return $template['templName'];
	}

	protected function getUsedTemplate($uid)
	{
		return $_SESSION['motioncaptcha'][$uid]['canvas'];
	}

}

session_start();

if (isset($_GET, $_GET['method'], $_GET['uniqueid']) && $_GET['method'] == 'gettemplate') {
	echo MotionCaptcha::getInstance()->setSession($_GET['uniqueid']);
	exit;
}

try {
	$validation = MotionCaptcha::getInstance()->validate($_POST['uniqueid'], $_POST['points']);
	if ($validation === true) {
		// Do your other validations here!
		// And insert your POST and other stuff
		echo 'We are validated!';
		exit;
	} else {
		echo 'Validation failed';
	}
} catch (Exception $e) {
	echo $e->getMessage();
}