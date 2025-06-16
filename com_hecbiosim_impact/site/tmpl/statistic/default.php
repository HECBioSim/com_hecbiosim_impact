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

$params  = $this->item->params;
?>

<link rel="stylesheet" href="media/com_hecbiosim_impact/css/statistics.css">
<script src="media/com_hecbiosim_impact/js/statistics.js" type="text/javascript"></script>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>
<p>Here we present the statistics of outputs that are attributed to the HECBioSim consortium. All data is mined from consortium submissions to EPSRC via ResearchFish.</p>
<p></p>
<!-- Load Plotly 3.0.1 -->
<script src="https://cdn.plot.ly/plotly-3.0.1.min.js" charset="utf-8"></script>

<div id="tp"></div>
<div id="ttp"></div>
<div id="ua"></div>
<div id="ppy"></div>
<div id="ppg"></div>
<div id="ppm"></div>
<div id="tppg"></div>
<div id="tj"></div>
