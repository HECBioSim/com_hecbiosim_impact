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

<link rel="stylesheet" href="media/com_hecbiosim_impact/css/publication.css">
<script src="media/com_hecbiosim_impact/js/publication.js" type="text/javascript"></script>

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>
<div class="filters">
    <input type="text" id="searchBar" placeholder="Search publications...">
    <select id="typeFilter">
        <option value="All">All Types</option>
        <option value="Journal Article">Journal Article</option>
        <option value="Preprint">Preprint</option>
        <option value="Conference Proceeding_Abstract">Conference Paper</option>
        <option value="Book Chapter">Book Chapter</option>
    </select>
    <select id="dateFilter">
        <option value="All">All Years</option>
        <option value="2025">2025</option>
        <option value="2024">2024</option>
        <option value="2023">2023</option>
        <option value="2022">2022</option>
        <option value="2021">2021</option>
        <option value="2020">2020</option>
        <option value="2019">2019</option>
        <option value="2018">2018</option>
        <option value="2017">2017</option>
        <option value="2016">2016</option>
        <option value="2015">2015</option>
        <option value="2014">2014</option>
        <option value="2013">2013</option>
    </select>
    <select id="projectRefFilter">
        <option value="All">All Project References</option>
        <option value="EP/L000253/1">EP/L000253/1 (2013-2019)</option>
        <option value="EP/R029407/1">EP/R029407/1 (2019-2023)</option>
        <option value="EP/X035603/1">EP/X035603/1 (2023-2027)</option>
    </select>
</div>

<div id="publications"></div>

