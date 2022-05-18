<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @link       [AUTHOR_URL]
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * KickJanolaw script file.
 *
 * @package   [PACKAGE_NAME]
 * @since     1.0.0
 */
class plgSystemKickjanolawInstallerScript
{
	public function __construct()
	{
		// Define the minimum versions to be supported.
		$this->minimumJoomla = '4.0';
		$this->minimumPhp    = '7.2.5';

		$this->dir = __DIR__;
	}

	public function install() {
		Factory::getDbo()->setQuery("UPDATE #__extensions SET enabled = 1 WHERE type = 'plugin' AND folder = 'system' AND element = 'kickjanolaw'")->execute();
	}
}
