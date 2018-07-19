<?php
/**
 * Joomla! System plugin - KickJanolaw
 *
 * @author     KickTemp <info@kicktemp.com>
 * @copyright  Copyright 2016 KickTemp
 * @license    GNU Public License
 * @link       http://www.kicktemp.com
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * A plugin to add additional image fields to an Joomla article
 *
 * @since  1.0
 */
class PlgSystemKickjanolaw extends JPlugin
{
	/**
	 * The language object
	 *
	 * @var null
	 * @since  1.0
	 */
	protected $lang = null;

	protected $langArray = null;

	protected $catchLanguageTypes = array("de", "gb", "fr");

	protected $user_id = null;

	protected $shop_id = null;

	protected $janolaw_url = 'https://www.janolaw.de/agb-service/shops/';

	protected $catchTypes = array("terms", "legaldetails", "revocation", "datasecurity", "model-withdrawal-form");

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * constructor, used to inject the language class for testing purposes
	 *
	 * @param   object  &$subject  The object to observe
	 * @param   array   $config    An optional associative array of configuration settings.
	 *                             Recognized key values include 'name', 'group', 'params', 'language'
	 *                             (this list is not meant to be comprehensive).
	 *
	 * @since  1.0
	 */
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);

		$this->langArray = array(
			"de" => "de",
			"en" => "gb",
			"gb" => "gb",
			"fr" => "fr"
		);

		$this->user_id = $this->params->get('user-id');
		$this->shop_id = $this->params->get('shop-id');
	}

	/**
	 * Plugin that loads module positions within content
	 *
	 * @param   string  $context   The context of the content being passed to the plugin.
	 * @param   object  &$article  The article object.  Note $article->text is also available
	 *
	 * @return  mixed   true if there is an error. Void otherwise.
	 *
	 * @since   1.6
	 */
	public function onContentPrepare($context, &$article)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, 'janolaw') === false)
		{
			return true;
		}

		$this->lang = JFactory::getLanguage();

		// Expression to search for (janolaw)
		$regex = '/{janolaw\s(.*?)}/i';
		$lang  = substr($this->lang->getTag(), 0, 2);
		$alang = substr($article->language, 0, 2);

		// Find all instances of plugin and put in $matches for janolaw
		// $matches[0] is full pattern match, $matches[1] is the type
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		// No matches, skip this
		if ($matches)
		{
			foreach ($matches as $match)
			{
				$matcheslist = explode(' ', $match[1]);

				if ((array_key_exists(1, $matcheslist) && !array_key_exists($matcheslist[1], $this->langArray)) || !array_key_exists(1, $matcheslist))
				{
					if (array_key_exists($alang, $this->langArray))
					{
						$matcheslist[1] = $this->langArray[$alang];
					}
					elseif (array_key_exists($lang, $this->langArray))
					{
						$matcheslist[1] = $this->langArray[$lang];
					}
					else
					{
						$matcheslist[1] = 'de';
					}
				}

				$type     = trim($matcheslist[0]);
				$language = trim($matcheslist[1]);

				$output = $this->_load($type, $language, $this->params->get('cachetime', 7200));

				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
			}
		}

		return true;
	}

	/**
	 * PLdasda
	 *
	 * @param   string  $type        Bla
	 * @param   string  $language    Add
	 * @param   int     $cache_time  Add
	 * @param   bool    $ajax        Is the request a ajax request
	 *
	 * @return mixed
	 */
	public function _load($type, $language = 'de', $cache_time = 7200, $ajax = false)
	{
		$tmpDir     = JFactory::getConfig()->get('tmp_path', JPATH_ROOT . '/tmp');
		$tmpDir     = rtrim($tmpDir, '/\\');
		$cache_path = $tmpDir;

		$request_url = $this->janolaw_url . $this->user_id . '/' . $this->shop_id . '/' . $language . '/' . $type . '_include.html';

		$file = $cache_path . '/' . $this->user_id . $this->shop_id . '_' . $language . '_janolaw_' . $type . '.html';

		jimport('joomla.filesystem.file');

		if (JFile::exists($file))
		{
			if (filectime($file) + $cache_time <= time())
			{
				// Get fresh version from server
				if ($content = $this->getContentfromUrl($request_url))
				{
					unlink($file);
					$fp = fopen($file, 'w');
					fwrite($fp, $content);
					fclose($fp);
				}
			}
		}
		else
		{
			$content = $this->getContentfromUrl($request_url);
			$fp      = fopen($file, 'w');
			fwrite($fp, $content);
			fclose($fp);
		}

		if ($ajax)
		{
			return $file;
		}

		// Extract text
		if ($content = file_get_contents($file))
		{
			return $content;
		}
		else
		{
			$this->loadLanguage();

			return JText::_('PLG_KICKJANOLAW_CONTENT_ERROR');
		}
	}

	/**
	 * Plugin
	 *
	 * @return Exception
	 */
	public function onAjaxCustomRenew()
	{
		$app = JFactory::getApplication();

		$type = $app->input->get('posttype');
		$lang = $app->input->get('postlang');

		$file = $this->_load($type, $lang, 0, true);

		if (file_get_contents($file))
		{
			$return = array('renew' => 'success');
		}
		else
		{
			$return = array('renew' => 'error');
		}

		echo json_encode($return);

		$app->close();
	}

	/**
	 * Plugin
	 *
	 * @return Exception
	 */
	public function onAjaxVersionCheck()
	{
		$app = JFactory::getApplication();

		$user_id = $app->input->get('postuserid');
		$shop_id = $app->input->get('postshopid');

		$request_url = $this->janolaw_url . $user_id . '/' . $shop_id . '/de/legaldetails.pdf';

		$content = $this->getContentfromUrl($request_url);

		if ($content)
		{
			$request_url = $this->janolaw_url . $user_id . '/' . $shop_id . '/gb/legaldetails.pdf';

			$content = $this->getContentfromUrl($request_url);

			if ($content)
			{
				echo '<br><div class="alert alert-success">' . JText::_('PLG_KICKJANOLAW_MULTILANGUAGE') . '</div>';
			}
			else
			{
				echo '<br><div class="alert alert-info">' . JText::_('PLG_KICKJANOLAW_NO_MULTILANGUAGE');
			}
		}
		else
		{
			echo '<br><div class="alert alert-danger">' . JText::_('PLG_KICKJANOLAW_VERSION_NO_3') . '</div>';
		}

		$app->close();
	}

	/**
	 * PLdasda
	 *
	 * @param   string  $url  The url
	 *
	 * @return string
	 */
	protected function getContentfromUrl($url)
	{
		if (function_exists('curl_exec'))
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$data     = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpCode == 404)
			{
				return false;
			}

			return $data;
		}
		else
		{
			return file_get_contents($url);
		}
	}

	/**
	 * PLdasda
	 *
	 * @param   string  $file  The url
	 *
	 * @return string
	 */
	protected function getContentfromLocalFile($file)
	{
		if (JFile::exists($file))
		{
			ob_start();

			include $file;

			$data = ob_get_contents();
			ob_end_clean();

			return $data;
		}
		else
		{
			return false;
		}
	}
}
