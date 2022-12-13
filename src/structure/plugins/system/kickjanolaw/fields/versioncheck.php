<?php
/**
 * @package    [PROJECT_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    [LICENSE]
 * @link       [AUTHOR_URL]
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

class JFormFieldVersioncheck extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'versioncheck';

	protected $catchLanguageTypes = array("de", "gb", "fr");

	protected $catchTypes = array("terms", "legaldetails", "revocation", "datasecurity", "model-withdrawal-form");

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getInput()
	{
		JHtml::_('script', 'plg_system_kickjanolaw/kickjanolaw-version-check.js', array('version' => 'auto', 'relative' => true));

		$html   = array();
		$html[] = Text::_('PLG_SYSTEM_KICKJANOLAW_CHECK_INFO') . "<br>";
		$html[] = '<input data-url="' . Uri::base(false) . "index.php?option=com_ajax&plugin=VersionCheck&format=raw" . '" type="button" class="btn btn-success" value="' . Text::_('PLG_SYSTEM_KICKJANOLAW_CHECK') . '" id="versioncheckajax">';
		$html[] = '<div id="versionhtml"></div>';

		return implode("\n", $html);
	}

}
