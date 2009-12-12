<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

// CTracker_Ignore: File checked by human

//
// The emailer class has support for attaching files, that isn't implemented
// in the 2.0 release but we can probable find some way of using it in a future
// release
//
class emailer
{
	var $msg, $subject, $extra_headers;
	var $addresses, $reply_to, $from;
	var $use_smtp;

	var $tpl_msg = array();

	function emailer($use_smtp)
	{
		$this->reset();
		$this->use_smtp = $use_smtp;
		$this->reply_to = $this->from = '';
	}

	// Resets all the data (address, template file, etc etc to default
	function reset()
	{
		$this->addresses = array();
		$this->vars = $this->msg = $this->extra_headers = '';
	}

	// Sets an email address to send to
	function email_address($address)
	{
		$this->addresses['to'] = trim($address);
	}

	function cc($address)
	{
		$this->addresses['cc'][] = trim($address);
	}

	function bcc($address)
	{
		$this->addresses['bcc'][] = trim($address);
	}

	function replyto($address)
	{
		$this->reply_to = trim($address);
	}

	function from($address)
	{
		$this->from = trim($address);
	}

	// set up subject for mail
	function set_subject($subject = '')
	{
		$this->subject = trim(preg_replace('#[\n\r]+#s', '', $subject));
	}

	// set up extra mail headers
	function extra_headers($headers)
	{
		$this->extra_headers .= trim($headers) . "\n";
	}

	function use_template($template_file, $template_lang = '', $no_template = false)
	{
		global $config;

		if (trim($template_file) == '')
		{
			message_die(GENERAL_ERROR, 'No template file set', '', __LINE__, __FILE__);
		}

		if (trim($template_lang) == '')
		{
			$template_lang = $config['default_lang'];
		}

		if ($config['html_email'])
		{
			if (empty($this->tpl_msg[$template_lang . $template_file]))
			{
				$tpl_file = IP_ROOT_PATH . 'language/lang_' . $template_lang . '/email/html/' . $template_file . '.tpl';

				if (!@file_exists(@phpbb_realpath($tpl_file)))
				{
					$tpl_file = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/email/html/' . $template_file . '.tpl';

					if (!@file_exists(@phpbb_realpath($tpl_file)))
					{
						message_die(GENERAL_ERROR, 'Could not find email template file :: ' . $template_file, '', __LINE__, __FILE__);
					}
				}

				if (!($fd = @fopen($tpl_file, 'r')))
				{
					message_die(GENERAL_ERROR, 'Failed opening template file :: ' . $tpl_file, '', __LINE__, __FILE__);
				}

				$this->tpl_msg[$template_lang . $template_file] = fread($fd, filesize($tpl_file));
				fclose($fd);
			}

			$mail_header = '';
			$mail_footer = '';
			if (!$no_template)
			{
				$tpl_header = IP_ROOT_PATH . 'language/lang_' . $template_lang . '/email/html/html_mail_header.tpl';

				if (!@file_exists(@phpbb_realpath($tpl_header)))
				{
					$tpl_header = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/email/html/html_mail_header.tpl';
				}

				if (!($fd = @fopen($tpl_header, 'r')))
				{
					message_die(GENERAL_ERROR, 'Failed opening template file :: ' . $tpl_header, '', __LINE__, __FILE__);
				}

				$mail_header = fread($fd, filesize($tpl_header));
				fclose($fd);

				// Mighty Gorgon - Add Site Url - BEGIN
				$site_url = (($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . trim($config['server_name']) . (($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '') . preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($config['script_path'])) . '/';
				if (substr($site_url, (strlen($site_url) - 1), 1) <> '/')
				{
					$site_url = $site_url . '/';
				}
				$mail_header = str_replace('{ROOT}', $site_url, $mail_header);
				$mail_header = str_replace('{SITENAME}', $config['sitename'], $mail_header);
				// Mighty Gorgon - Add Site Url - END

				$tpl_footer = IP_ROOT_PATH . 'language/lang_' . $template_lang . '/email/html/html_mail_footer.tpl';

				if (!@file_exists(@phpbb_realpath($tpl_footer)))
				{
					$tpl_footer = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/email/html/html_mail_footer.tpl';
				}

				if (!($fd = @fopen($tpl_footer, 'r')))
				{
					message_die(GENERAL_ERROR, 'Failed opening template file :: ' . $tpl_footer, '', __LINE__, __FILE__);
				}

				$mail_footer = fread($fd, filesize($tpl_footer));
				fclose($fd);
			}

			$this->msg = $mail_header . $this->tpl_msg[$template_lang . $template_file] . $mail_footer;

			return true;
		}
		else
		{
			if (empty($this->tpl_msg[$template_lang . $template_file]))
			{
				$tpl_file = IP_ROOT_PATH . 'language/lang_' . $template_lang . '/email/txt/' . $template_file . '.tpl';

				if (!@file_exists(@phpbb_realpath($tpl_file)))
				{
					$tpl_file = IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/email/txt/' . $template_file . '.tpl';

					if (!@file_exists(@phpbb_realpath($tpl_file)))
					{
						message_die(GENERAL_ERROR, 'Could not find email template file :: ' . $template_file, '', __LINE__, __FILE__);
					}
				}

				if (!($fd = @fopen($tpl_file, 'r')))
				{
					message_die(GENERAL_ERROR, 'Failed opening template file :: ' . $tpl_file, '', __LINE__, __FILE__);
				}

				$this->tpl_msg[$template_lang . $template_file] = fread($fd, filesize($tpl_file));
				fclose($fd);
			}

			$this->msg = $this->tpl_msg[$template_lang . $template_file];

			return true;
		}
	}

	// assign variables
	function assign_vars($vars)
	{
		$this->vars = (empty($this->vars)) ? $vars : $this->vars . $vars;
	}

	// Send the mail out to the recipients set previously in var $this->address
	function send()
	{
		global $config, $lang, $db;
		// Escape all quotes, else the eval will fail.
		$this->msg = str_replace ("'", "\'", $this->msg);
		$this->msg = preg_replace('#\{([a-z0-9\-_]*?)\}#is', "' . $\\1 . '", $this->msg);

		// Set vars
		reset ($this->vars);
		while (list($key, $val) = each($this->vars))
		{
			$$key = $val;
		}

		eval("\$this->msg = '$this->msg';");

		// Clear vars
		reset ($this->vars);
		while (list($key, $val) = each($this->vars))
		{
			unset($$key);
		}

		// We now try and pull a subject from the email body ... if it exists,
		// do this here because the subject may contain a variable
		$drop_header = '';
		$match = array();
		if (preg_match('#^(Subject:(.*?))$#m', $this->msg, $match))
		{
			$this->subject = (trim($match[2]) != '') ? trim($match[2]) : (($this->subject != '') ? $this->subject : 'No Subject');
			$drop_header .= '[\r\n]*?' . preg_quote($match[1], '#');
		}
		else
		{
			$this->subject = (($this->subject != '') ? $this->subject : 'No Subject');
		}

		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
		if (preg_match('#^(Charset:(.*?))$#m', $this->msg, $match))
		{
			$this->encoding = (trim($match[2]) != '') ? trim($match[2]) : trim($encoding_charset);
			$drop_header .= '[\r\n]*?' . preg_quote($match[1], '#');
		}
		else
		{
			$this->encoding = trim($encoding_charset);
		}

		if ($drop_header != '')
		{
			$this->msg = trim(preg_replace('#' . $drop_header . '#s', '', $this->msg));
		}

		$to = $this->addresses['to'];

		$cc = (sizeof($this->addresses['cc'])) ? implode(', ', $this->addresses['cc']) : '';
		$bcc = (sizeof($this->addresses['bcc'])) ? implode(', ', $this->addresses['bcc']) : '';

		// Build header

		if ($config['html_email'] == true)
		{
			$this->extra_headers = (($this->reply_to != '') ? "Reply-to: $this->reply_to\n" : '') . (($this->from != '') ? "From: $this->from\n" : "From: " . $config['board_email'] . "\n") . "Return-Path: " . $config['board_email'] . "\nMessage-ID: <" . md5(uniqid(time())) . "@" . $config['server_name'] . ">\nMIME-Version: 1.0\nContent-type: text/html; charset=" . $this->encoding . "\nContent-transfer-encoding: 8bit\nDate: " . gmdate('r') . "\nX-Priority: 3\nX-MSMail-Priority: Normal\nX-Mailer: PHP\nX-MimeOLE: Produced By Icy Phoenix\n" . $this->extra_headers . (($cc != '') ? "Cc: $cc\n" : '')  . (($bcc != '') ? "Bcc: $bcc\n" : '');
		}
		else
		{
			$this->extra_headers = (($this->reply_to != '') ? "Reply-to: $this->reply_to\n" : '') . (($this->from != '') ? "From: $this->from\n" : "From: " . $config['board_email'] . "\n") . "Return-Path: " . $config['board_email'] . "\nMessage-ID: <" . md5(uniqid(time())) . "@" . $config['server_name'] . ">\nMIME-Version: 1.0\nContent-type: text/plain; charset=" . $this->encoding . "\nContent-transfer-encoding: 8bit\nDate: " . gmdate('r') . "\nX-Priority: 3\nX-MSMail-Priority: Normal\nX-Mailer: PHP\nX-MimeOLE: Produced By Icy Phoenix\n" . $this->extra_headers . (($cc != '') ? "Cc: $cc\n" : '')  . (($bcc != '') ? "Bcc: $bcc\n" : '');
		}

		// Send message ... removed $this->encode() from subject for time being
		if ( $this->use_smtp )
		{
			if ( !defined('SMTP_INCLUDED') )
			{
				include(IP_ROOT_PATH . 'includes/smtp.' . PHP_EXT);
			}

			$result = smtpmail($to, $this->subject, $this->msg, $this->extra_headers);
		}
		else
		{
			$empty_to_header = ($to == '') ? true : false;
			$to = ($to == '') ? (($config['sendmail_fix']) ? ' ' : 'Undisclosed-recipients:;') : $to;

			$result = @mail($to, $this->subject, preg_replace("#(?<!\r)\n#s", "\n", $this->msg), $this->extra_headers);

			if (!$result && !$config['sendmail_fix'] && $empty_to_header)
			{
				$to = ' ';
				set_config('sendmail_fix', 1);
				$result = @mail($to, $this->subject, preg_replace("#(?<!\r)\n#s", "\n", $this->msg), $this->extra_headers);
			}
		}

		// Did it work?
		if (!$result)
		{
			if ($config['disable_email_error'] == 0)
			{
				message_die(GENERAL_ERROR, 'Failed sending email :: ' . (($this->use_smtp) ? 'SMTP' : 'PHP') . ' :: ' . $result, '', __LINE__, __FILE__);
			}
		}

		return true;
	}

	// Encodes the given string for proper display for this encoding ... nabbed
	// from php.net and modified. There is an alternative encoding method which
	// may produce lesd output but it's questionable as to its worth in this
	// scenario IMO
	function encode($str)
	{
		if ($this->encoding == '')
		{
			return $str;
		}

		// define start delimimter, end delimiter and spacer
		$end = "?=";
		$start = "=?$this->encoding?B?";
		$spacer = "$end\r\n $start";

		// determine length of encoded text within chunks and ensure length is even
		$length = 75 - strlen($start) - strlen($end);
		$length = floor($length / 2) * 2;

		// encode the string and split it into chunks with spacers after each chunk
		$str = chunk_split(base64_encode($str), $length, $spacer);

		// remove trailing spacer and add start and end delimiters
		$str = preg_replace('#' . preg_quote($spacer, '#') . '$#', '', $str);

		return $start . $str . $end;
	}

	// Attach files via MIME.
	function attachFile($filename, $mimetype = "application/octet-stream", $szFromAddress, $szFilenameToDisplay)
	{
		global $lang;
		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';
		$mime_boundary = "--==================_846811060==_";

		$this->msg = '--' . $mime_boundary . "\nContent-Type: text/html;\n\tcharset=\"" . $encoding_charset . "\"\n\n" . $this->msg;

		if ($mime_filename)
		{
			$filename = $mime_filename;
			$encoded = $this->encode_file($filename);
		}

		$fd = fopen($filename, "r");
		$contents = fread($fd, filesize($filename));

		$this->mimeOut = "--" . $mime_boundary . "\n";
		$this->mimeOut .= "Content-Type: " . $mimetype . ";\n\tname=\"$szFilenameToDisplay\"\n";
		$this->mimeOut .= "Content-Transfer-Encoding: quoted-printable\n";
		$this->mimeOut .= "Content-Disposition: attachment;\n\tfilename=\"$szFilenameToDisplay\"\n\n";

		if ( $mimetype == "message/rfc822" )
		{
			$this->mimeOut .= "From: ".$szFromAddress."\n";
			$this->mimeOut .= "To: ".$this->emailAddress."\n";
			$this->mimeOut .= "Date: " . gmdate("D, d M Y H:i:s") . " UT\n";
			$this->mimeOut .= "Reply-To:".$szFromAddress."\n";
			$this->mimeOut .= "Subject: ".$this->mailSubject."\n";
			$this->mimeOut .= "X-Mailer: PHP/" . phpversion()."\n";
			$this->mimeOut .= "MIME-Version: 1.0\n";
		}

		$this->mimeOut .= $contents."\n";
		$this->mimeOut .= "--" . $mime_boundary . "--" . "\n";

		return $out;
		// added -- to notify email client attachment is done
	}

	function getMimeHeaders($filename, $mime_filename="")
	{
		$mime_boundary = "--==================_846811060==_";

		if ($mime_filename)
		{
			$filename = $mime_filename;
		}

		$out = "MIME-Version: 1.0\n";
		$out .= "Content-Type: multipart/mixed;\n\tboundary=\"$mime_boundary\"\n\n";
		$out .= "This message is in MIME format. Since your mail reader does not understand\n";
		$out .= "this format, some or all of this message may not be legible.";

		return $out;
	}

	//
	// Split string by RFC 2045 semantics (76 chars per line, end with \r\n).
	//
	function myChunkSplit($str)
	{
		$stmp = $str;
		$len = strlen($stmp);
		$out = "";

		while ($len > 0)
		{
			if ($len >= 76)
			{
				$out .= substr($stmp, 0, 76) . "\r\n";
				$stmp = substr($stmp, 76);
				$len = $len - 76;
			}
			else
			{
				$out .= $stmp . "\r\n";
				$stmp = "";
				$len = 0;
			}
		}
		return $out;
	}

	// Split the specified file up into a string and return it
	function encode_file($sourcefile)
	{
		if (is_readable(@phpbb_realpath($sourcefile)))
		{
			$fd = fopen($sourcefile, "r");
			$contents = fread($fd, filesize($sourcefile));
			$encoded = $this->myChunkSplit(base64_encode($contents));
			fclose($fd);
		}

		return $encoded;
	}

} // class emailer

?>