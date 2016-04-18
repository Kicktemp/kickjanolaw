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

jimport('joomla.form.formfield');

/**
 * Update janolaw Form Field class for the Joomla Platform.
 *
 * @since  1.0
 */
class JFormFieldCustomRenew extends JFormField
{
	protected $type = 'CustomRenew';

	protected $catchLanguageTypes = array("de", "gb", "fr");

	protected $catchTypes = array(
		"terms" => "PLG_KICKJANOLAW_TERMS",
		"legaldetails" => "PLG_KICKJANOLAW_LEGALDETAILS",
		"revocation" => "PLG_KICKJANOLAW_REVAOCATION",
		"datasecurity" => "PLG_KICKJANOLAW_DATASECURITY",
		"model-withdrawal-form" => "PLG_KICKJANOLAW_MODE_WITHDRAWAL_FORM"
	);

	/**
	 * Method to instantiate the form field object.
	 *
	 * @param   JForm  $form  The form to attach to the form field object.
	 *
	 * @since   11.1
	 */
	public function __construct($form = null)
	{
		parent::__construct();
		$doc = JFactory::getDocument();
		$js  = array();

		$js[] = 'jQuery(function () {';
		$js[] = "jQuery('#cleancachefolder').click(function(){";
		$js[] = "var language = jQuery('#language').val();";
		$js[] = "var type = jQuery('#type').val();";
		$js[] = "jQuery.ajax({";
		$js[] = "type: 'POST',";
		$js[] = "url: '" . JUri::root(false) . "index.php?option=com_ajax&plugin=CustomRenew&format=raw',";
		$js[] = "data: { postlang: language, posttype: type";
		$js[] = "},";
		$js[] = "dataType: 'json',";
		$js[] = "success: function (data) {";
		$js[] = "if (data.renew == 'success') {";
		$js[] = "jQuery('#RenewSuccess').removeClass('hidden');";
		$js[] = "jQuery('#RenewError').addClass('hidden');";
		$js[] = "} else {";
		$js[] = "jQuery('#RenewError').removeClass('hidden');";
		$js[] = "jQuery('#RenewSuccess').addClass('hidden');";
		$js[] = "}";
		$js[] = "}";
		$js[] = "});";
		$js[] = "});";
		$js[] = '});';

		$js = implode("\n", $js);
		$doc->addScriptDeclaration($js);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	public function getInput()
	{
		$html   = array();
		$html[] = '<select id="type">';

		foreach ($this->catchTypes as $key => $type)
		{
			$html[] = '<option value="' . $key . '">' . JText::_($type) . '</option>';
		}

		$html[] = '</select>';
		$html[] = '<select id="language">';

		foreach ($this->catchLanguageTypes as $lang)
		{
			$html[] = '<option value="' . $lang . '">' . JText::_($lang) . '</option>';
		}

		$html[] = '</select>';
		$html[] = '<div id="RenewError" class="alert alert-danger hidden">' . JText::_('PLG_KICKJANOLAW_FILERENEW_ERROR') . '</div>';
		$html[] = '<div id="RenewSuccess" class="alert alert-success hidden">' . JText::_('PLG_KICKJANOLAW_FILERENEW_SUCCESS') . '</div>';
		$html[] = '<input type="button" class="btn btn-success" value="' . JText::_('PLG_KICKJANOLAW_FILERENEW') . '" id="cleancachefolder">';

		return implode("\n", $html);
	}
}
