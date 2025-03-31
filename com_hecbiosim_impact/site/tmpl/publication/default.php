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

// Load filter data from JSON
$filters = json_decode(file_get_contents(__DIR__ . "/pubs_config.json"), true);

$params  = $this->item->params;
?>

<link rel="stylesheet" href="media/com_hecbiosim_impact/css/publication.css">
<script src="media/com_hecbiosim_impact/js/publication.js" type="text/javascript"></script>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>

<p>A detailed list of publications that have attributed HECBioSim HPC resources or support as a significant contributor. These publications are harvested from submissions made to the EPSRC via ResearchFish.</p>

<div class="filters">
    <input type="text" id="searchBar" placeholder="Search publications...">

    <select id="typeFilter">
        <option value="All">All Types</option>
        <?php foreach ($filters["types"] as $type): ?>
            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
        <?php endforeach; ?>
    </select>

    <select id="dateFilter">
        <option value="All">All Years</option>
        <?php foreach ($filters["years"] as $year): ?>
            <option value="<?= $year ?>"><?= $year ?></option>
        <?php endforeach; ?>
    </select>

    <select id="projectRefFilter">
        <option value="All">All Project References</option>
        <?php foreach ($filters["projects"] as $key => $value): ?>
            <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div id="publications"></div>
