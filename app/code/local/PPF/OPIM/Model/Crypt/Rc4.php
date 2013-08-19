<?php

if (!defined('CRYPT_RC4_MODE')) define('CRYPT_RC4_MODE', CRYPT_RC4_MODE_INTERNAL);
require_once('phpseclib/Crypt/RC4.php');

class PPF_OPIM_Model_Crypt_Rc4 extends Crypt_RC4
{
	var	$modkey = 256;
	var	$methods = array('hexadecimal', 'urlencode');
	var	$method = 'hexadecimal';

	function setMethod($method)
	{
		if (in_array($method, $this->methods))
			$this->method = $method;
		return $this;
	}

    function setKey($key)
    {
        $this->key = $key;

        $keyLength = strlen($key);
        $keyStream = array();
        for ($i = 0; $i < 256; $i++) {
            $keyStream[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $keyStream[$i] + ord($key[$i % $keyLength])) % $this->modkey;
            $temp = $keyStream[$i];
            $keyStream[$i] = $keyStream[$j];
            $keyStream[$j] = $temp;
        }

        $this->encryptIndex = $this->decryptIndex = array(0, 0);
        $this->encryptStream = $this->decryptStream = $keyStream;
		return $this;
    }

    function _crypt($text, $mode)
    {
        if ($this->encryptStream === false) {
            $this->setKey($this->key);
        }

        switch ($mode) {
            case CRYPT_RC4_ENCRYPT:
                $keyStream = $this->encryptStream;
                list($i, $j) = $this->encryptIndex;
                break;
            case CRYPT_RC4_DECRYPT:
                $keyStream = $this->decryptStream;
                list($i, $j) = $this->decryptIndex;
        }

        $newText = '';
        for ($k = 0; $k < strlen($text); $k++) {
            $i = ($i + 1) % $this->modkey;
            $j = ($j + $keyStream[$i]) % $this->modkey;
            $temp = $keyStream[$i];
            $keyStream[$i] = $keyStream[$j];
            $keyStream[$j] = $temp;
            $temp = $keyStream[($keyStream[$i] + $keyStream[$j]) % $this->modkey];
            $c = chr(ord($text[$k]) ^ $temp);
            $newText .= (($this->method == 'urlencode') ? iconv('ISO-8859-1', 'UTF-8', $c) : $c);
        }

        if ($this->continuousBuffer) {
            switch ($mode) {
                case CRYPT_RC4_ENCRYPT:
                    $this->encryptStream = $keyStream;
                    $this->encryptIndex = array($i, $j);
                    break;
                case CRYPT_RC4_DECRYPT:
                    $this->decryptStream = $keyStream;
                    $this->decryptIndex = array($i, $j);
            }
        }

        return $newText;
    }
}

?>
