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

<link rel="stylesheet" href="media/com_hecbiosim_impact/css/statistic.css">
<script src="media/com_hecbiosim_impact/js/statistic.js" type="text/javascript"></script>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>
<p>Here we present the statistics of outputs that are attributed to the HECBioSim consortium. All data is mined from consortium submissions to EPSRC via ResearchFish.</p>
<p></br></p>
<!-- Load Plotly 3.0.1 -->
<script src="https://cdn.plot.ly/plotly-3.0.1.min.js" charset="utf-8"></script>

<div class="container">
  <div class="row">
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-newspaper fa-3x" aria-hidden="true"></span>
        <h1 id="dash-alltime-pubs" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">All-time Publications</p>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-ranking-star fa-3x" aria-hidden="true"></span>
        <h1 id="dash-top-pubs" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">Publications in Top 10 Journals</p>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-users fa-3x" aria-hidden="true"></span>
        <h1 id="dash-authors" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">All-time Community Contributors</p>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-sack-dollar fa-3x" aria-hidden="true"></span>
        <h1 id="dash-projects" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">All-time Projects Resourced</p>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-microchip fa-3x" aria-hidden="true"></span>
        <h1 id="dash-projects" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">All-time CPU time awarded</p>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="dash-box">
        <span class="fa-solid fa-microchip fa-3x" aria-hidden="true"></span>
        <h1 id="dash-projects" class="dash-value"></h1>
        <p></br></p>
        <p class="dash-label">All-time GPU time awarded</p>
      </div>
    </div>    
  </div>

  <div class="row">
    <div id="chart-pubs-yr" class="col-lg-6"></div>
    <div id="chart-pubs-grant-yr" class="col-lg-6"></div>
    <div id="chart-pubs-month" class="col-lg-6"></div>
    <div id="chart-pubs-grant" class="col-lg-6"></div>
    <div id="chart-top-journal" class="col-lg-6"></div>
  </div>
</div>

