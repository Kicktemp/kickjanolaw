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

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Kicktemp\Plugin\System\Janolaw\Extension\KickJanolaw;

return new class implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->set(PluginInterface::class, function (Container $container) {
            $plugin = new KickJanolaw(
                $container->get(DispatcherInterface::class),
                (array) PluginHelper::getPlugin('system', 'kickjanolaw'),
            );
            $plugin->setApplication(Factory::getApplication());

            return $plugin;
        });
    }
};
