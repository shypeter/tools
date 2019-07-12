<?php
define('CURL_COOKIE_TEMP_PATH', '/tmp/cookies/');
define('CURL_COOKIE_FILENAME', CURL_COOKIE_TEMP_PATH.'cookie.tmp');

class Curl {

	private $_userAgent;
	private $_curlInfo;
	private $_connTimeout;
	private $_timeout;
	private $_optHeader;
	private $_optHttpHeader;

	public function __construct() {
		$this->_userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0';
		$this->_curlInfo = array();
		$this->_connTimeout = 10;
		$this->_timeout = 10;
		$this->_optHeader = false;
		$this->_optHttpHeader = array();
	}

	public function setUserAgent(string $userAgent) {
		$this->_userAgent = $userAgent;
		return $this;
	}

	public function setOptHeader(bool $header) {
		$this->_optHeader = $header;
		return $this;
	}

	public function setOptHttpHeader(array $httpHeader) {
		$this->_optHttpHeader = $httpHeader;
		return $this;
	}

	public function setConnTimeout(int $connTimeout) {
		$this->_connTimeout = $connTimeout;
		return $this;
	}

	public function setTimeout(int $timeout) {
		$this->_timeout = $timeout;
		return $this;
	}

	public function getCurlInfo($key = '') {
		$rtn = false;

		if ($key && is_string($key)) {
			if (isset($this->_curlInfo[$key])) {
				$rtn = $this->_curlInfo[$key];
			}
		}
		else {
			$rtn = $this->_curlInfo;
		}

		return $rtn;
	}

	public function getHttpStatus() {
		return $this->getCurlInfo('http_code');
	}

  public function send($url, $data = array()) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $this->_optHeader);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_optHttpHeader);
    if ($data) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($ch, CURLOPT_USERAGENT, $this->_userAgent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_connTimeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
    $rtn = curl_exec($ch);
    $this->_curlInfo = curl_getinfo($ch);
    curl_close($ch);

    return $rtn;
  }


}
