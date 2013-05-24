<?php

class ESES Extends CApplicationComponent
{
	public $access_key;
	public $secret_key;
	public $host; //email.us-east-1.amazonaws.com
	public $_ESimpleEmailService;

	public function init() {
		$this->_ESimpleEmailService = new ESimpleEmailService($this->access_key, $this->secret_key, $this->host);
		parent::init();
	}

	public function email()
	{
		return new ESimpleEmailServiceMessage($this->_ESimpleEmailService);
	}

	public function __call($name, $arguments)
	{
		return call_user_func_array(array($this->_ESimpleEmailService, $name), $arguments);
	}
}
