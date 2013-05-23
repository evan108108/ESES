<?php

final class ESimpleEmailServiceMessage {

	// these are public for convenience only
	// these are not to be used outside of the ESimpleEmailService class!
	public $to, $cc, $bcc, $replyto;
	public $from, $returnpath;
	public $subject, $messagetext, $messagehtml;
	public $subjectCharset, $messageTextCharset, $messageHtmlCharset;
	public $_ses;

	function __construct(ESimpleEmailService $ses = null) {
		$this->_ses = $ses;

		$this->to = array();
		$this->cc = array();
		$this->bcc = array();
		$this->replyto = array();

		$this->from = null;
		$this->returnpath = null;

		$this->subject = null;
		$this->messagetext = null;
		$this->messagehtml = null;

		$this->subjectCharset = null;
		$this->messageTextCharset = null;
		$this->messageHtmlCharset = null;
	}


	/**
	* addTo, addCC, addBCC, and addReplyTo have the following behavior:
	* If a single address is passed, it is appended to the current list of addresses.
	* If an array of addresses is passed, that array is merged into the current list.
	*/
	function addTo($to) {
		if(!is_array($to)) {
			$this->to[] = $to;
		}
		else {
			$this->to = array_merge($this->to, $to);
		}
		return $this;
	}

	function addCC($cc) {
		if(!is_array($cc)) {
			$this->cc[] = $cc;
		}
		else {
			$this->cc = array_merge($this->cc, $cc);
		}
		return $this;
	}

	function addBCC($bcc) {
		if(!is_array($bcc)) {
			$this->bcc[] = $bcc;
		}
		else {
			$this->bcc = array_merge($this->bcc, $bcc);
		}
		return $this;
	}

	function addReplyTo($replyto) {
		if(!is_array($replyto)) {
			$this->replyto[] = $replyto;
		}
		else {
			$this->replyto = array_merge($this->replyto, $replyto);
		}
		return $this;
	}

	function setFrom($from) {
		$this->from = $from;
		return $this;
	}

	function setReturnPath($returnpath) {
		$this->returnpath = $returnpath;
	}

	function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}

	function setSubjectCharset($charset) {
		$this->subjectCharset = $charset;
		return $this;
	}

	function setMessageFromString($text, $html = null) {
		$this->messagetext = $text;
		$this->messagehtml = $html;
		return $this;
	}

	function setMessageFromFile($textfile, $htmlfile = null) {
		if(file_exists($textfile) && is_file($textfile) && is_readable($textfile)) {
			$this->messagetext = file_get_contents($textfile);
		} else {
			$this->messagetext = null;
		}
		if(file_exists($htmlfile) && is_file($htmlfile) && is_readable($htmlfile)) {
			$this->messagehtml = file_get_contents($htmlfile);
		} else {
			$this->messagehtml = null;
		}
		return $this;
	}

	function setMessageFromURL($texturl, $htmlurl = null) {
		if($texturl !== null) {
			$this->messagetext = file_get_contents($texturl);
		} else {
			$this->messagetext = null;
		}
		if($htmlurl !== null) {
			$this->messagehtml = file_get_contents($htmlurl);
		} else {
			$this->messagehtml = null;
		}
		return $this;
	}

	function setMessageCharset($textCharset, $htmlCharset = null) {
		$this->messageTextCharset = $textCharset;
		$this->messageHtmlCharset = $htmlCharset;
		return $this;
	}

	/**
	* Validates whether the message object has sufficient information to submit a request to SES.
	* This does not guarantee the message will arrive, nor that the request will succeed;
	* instead, it makes sure that no required fields are missing.
	*
	* This is used internally before attempting a SendEmail or SendRawEmail request,
	* but it can be used outside of this file if verification is desired.
	* May be useful if e.g. the data is being populated from a form; developers can generally
	* use this function to verify completeness instead of writing custom logic.
	*
	* @return boolean
	*/
	public function validate() {
		if(count($this->to) == 0)
			return false;
		if($this->from == null || strlen($this->from) == 0)
			return false;
		// messages require at least one of: subject, messagetext, messagehtml.
		if(($this->subject == null || strlen($this->subject) == 0)
			&& ($this->messagetext == null || strlen($this->messagetext) == 0)
			&& ($this->messagehtml == null || strlen($this->messagehtml) == 0))
		{
			return false;
		}

		return true;
	}

	public function send()
	{
		return $this->_ses->sendEmail($this);
	}
}
