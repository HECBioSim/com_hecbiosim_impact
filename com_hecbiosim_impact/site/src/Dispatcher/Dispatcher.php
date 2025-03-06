<?php
/**
 * @package    com_hecbiosim_impact
 * @author     James Gebbie-Rayet <james.gebbie@stfc.ac.uk>
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

namespace Hecbiosim\Component\Hecbiosim_impact\Site\Dispatcher;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcher;
use Joomla\CMS\Language\Text;

/**
 * ComponentDispatcher class for Com_Hecbiosim_impact
 */
class Dispatcher extends ComponentDispatcher
{
	/**
	 * Dispatch a controller task. Redirecting the user if appropriate.
	 *
	 * @return  void
	 */
	public function dispatch()
	{
		parent::dispatch();
	}
}
