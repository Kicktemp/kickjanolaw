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

class JFormFieldCustomRenew extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'CustomRenew';

	protected $catchLanguageTypes = array("de", "gb", "fr");

	protected $catchTypes = array(
		"terms" => "PLG_SYSTEM_KICKJANOLAW_TERMS",
		"legaldetails" => "PLG_SYSTEM_KICKJANOLAW_LEGALDETAILS",
		"revocation" => "PLG_SYSTEM_KICKJANOLAW_REVAOCATION",
		"datasecurity" => "PLG_SYSTEM_KICKJANOLAW_DATASECURITY",
		"model-withdrawal-form" => "PLG_SYSTEM_KICKJANOLAW_MODE_WITHDRAWAL_FORM"
	);


	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getInput()
	{
		JHtml::_('script', 'plg_system_kickjanolaw/kickjanolaw-custom-renew.js', array('version' => 'auto', 'relative' => true));

		$html   = array();
		$html[] = '<div class="row">';
		$html[] = '<div class="span3">';
		$html[] = '<select id="type" class="form-select">';

		foreach ($this->catchTypes as $key => $type)
		{
			$html[] = '<option value="' . $key . '">' . Text::_($type) . '</option>';
		}

		$html[] = '</select>';
		$html[] = '</div>';
		$html[] = '<div class="span3">';
		$html[] = '<select id="language" class="form-select">';

		foreach ($this->catchLanguageTypes as $lang)
		{
			$html[] = '<option value="' . $lang . '">' . Text::_($lang) . '</option>';
		}

		$html[] = '</select>';
		$html[] = '</div>';
		$html[] = '<div class="span3">';
		$html[] = '<input data-url="' . Uri::base(false) . "index.php?option=com_ajax&plugin=CustomRenew&format=raw" . '" type="button" class="btn btn-success" value="' . Text::_('PLG_SYSTEM_KICKJANOLAW_FILERENEW') . '" id="cleancachefolder">';
		$html[] = '</div>';
		$html[] = '</div>';
		$html[] = '<div id="RenewError" class="alert alert-danger hidden">' . Text::_('PLG_SYSTEM_KICKJANOLAW_FILERENEW_ERROR') . '</div>';
		$html[] = '<div id="RenewSuccess" class="alert alert-success hidden">' . Text::_('PLG_SYSTEM_KICKJANOLAW_FILERENEW_SUCCESS') . '</div>';

		return implode("\n", $html);
	}

}
