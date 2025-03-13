<?php
/**
 * @package    com_hecbiosim_impact
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;


$url = "https://github.com/HEJ845/Auto_Extract_RF/blob/main/publication_charts.html"; 
$content = file_get_contents($url);

if ($content !== false) {
    echo $content; 
} else {
    echo "Failed to load content.";
}

?>

// Put HTML here.

