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
 * Version-Check janolaw Form Field class for the Joomla Platform.
 *
 * @since  1.0
 */
class JFormFieldVersionCheck extends JFormField
{
	protected $type = 'VersionCheck';

	protected $catchLanguageTypes = array("de", "gb", "fr");

	protected $catchTypes = array("terms", "legaldetails", "revocation", "datasecurity", "model-withdrawal-form");

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

		$js[] = '(function ($) {';
		$js[] = '$(document).ready(function() {';
		$js[] = "$('#versioncheckajax').click(function(){
					var userid = jQuery('#jform_params_user_id').val();
					var shopid = jQuery('#jform_params_shop_id').val();
					$.ajax({
						type: 'POST',
						url: '" . JUri::base(false) . "index.php?option=com_ajax&plugin=VersionCheck&format=raw',
						data: { postuserid: userid, postshopid: shopid}
					})
					.done(function( data ) {
					    $('#version').html(data);
					});
				});
		";
		$js[] = '});';
		$js[] = '}(jQuery));';

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
		$html[] = JText::_('PLG_KICKJANOLAW_CHECK_INFO') . "<br>";
		$html[] = '<input type="button" class="btn btn-success" value="' . JText::_('PLG_KICKJANOLAW_CHECK') . '" id="versioncheckajax">';
		$html[] = '<div id="version"></div>';

		return implode("\n", $html);
	}
}
