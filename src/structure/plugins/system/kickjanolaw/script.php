<?php
/**
 * @package    [PACKAGE_NAME]
 *
 * @author     [AUTHOR] <[AUTHOR_EMAIL]>
 * @copyright  [COPYRIGHT]
 * @license    [LICENSE]
 * @link       [AUTHOR_URL]
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerScript;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * KickJanolaw script file.
 *
 * @package   [PACKAGE_NAME]
 * @since     1.0.0
 */
class plgSystemKickjanolawInstallerScript extends InstallerScript
{

    protected $minimumPhp = '8.1';
    protected $minimumJoomla = '5.0';

    /**
     * @param $type
     * @param $parent
     * @return true|void
     */
    public function preflight($type, $parent)
    {
        if (!in_array($type, ['install', 'update'])) {
            return true;
        }
    }

    /**
     * @param $install_type
     * @param $parent
     * @return true|void
     */
    public function postflight($install_type, $parent)
    {
        if (!in_array($install_type, ['install', 'update']))
        {
            return true;
        }

        $this->removeFiles();
    }

    /**
     * @return void
     */
    public function install() {
		Factory::getDbo()->setQuery("UPDATE #__extensions SET enabled = 1 WHERE type = 'plugin' AND folder = 'system' AND element = 'kickjanolaw'")->execute();
	}
}
