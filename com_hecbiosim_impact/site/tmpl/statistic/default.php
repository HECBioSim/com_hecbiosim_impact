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

<?php if ($this->params->get('show_page_heading')) : ?>
    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
<?php endif; ?>
<!-- Load Plotly 3.0.1 -->
<script src="https://cdn.plot.ly/plotly-3.0.1.min.js" charset="utf-8"></script>
<div id="papersPerYear"></div>
<div id="totalPapersDiv"></div>
<div id="uniqueAuthorsDiv"></div>
<div id="papersPerGrantDiv"></div>
<div id="publicationMonthDiv"></div>
<div id="topJournalsDiv"></div>
    <script>

        // Fetch stats data from GitHub
        fetch('https://hecbiosim.github.io/com_hecbiosim_impact/stats.json')
            .then(response => response.json())
            .then(data => {
            // Bar Chart: Papers per Year
            Plotly.newPlot('papersPerYear', [{ x: data.barChart.x, y: data.barChart.y, type: 'bar' }]);

            // Bar Chart: Publications per Month (all-time) [Note- PLACEHOLDER]
            Plotly.newPlot('publicationMonthDiv', [{ x: data.publicationMonth.x, y: data.publicationMonth.y, type: 'bar' }]);

            // Bar Chart: Papers in Top Journals
            Plotly.newPlot('topJournalsDiv', [{ x: data.topJournals.x, y: data.topJournals.y, type: 'bar' }]);

            // Display Total Papers & Unique Authors as Text
            document.getElementById("totalPapersDiv").innerHTML = `<h3>Total Papers: ${data.totalPapers}</h3>`;
            document.getElementById("uniqueAuthorsDiv").innerHTML = `<h3>Unique Authors: ${data.uniqueAuthors}</h3>`;
        })
        .catch(error => console.error('Error loading JSON:', error));
</script>
