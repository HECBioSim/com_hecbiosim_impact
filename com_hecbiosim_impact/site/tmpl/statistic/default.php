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
<p>Here we present the statistics of outputs that are attributed to the HECBioSim consortium. All data is mined from consortium submissions to EPSRC via ResearchFish.</p>
<p></p>
<!-- Load Plotly 3.0.1 -->
<script src="https://cdn.plot.ly/plotly-3.0.1.min.js" charset="utf-8"></script>
<div id="pubsPerYear"></div>
<div id="pubsPerGrant"></div>
<div id="pubsPerMonth"></div>
<div id="totalPubsPerGrant"></div>
<div id="topJournals"></div>

<script>
         // Fetch stats data from GitHub
    fetch('https://hecbiosim.github.io/com_hecbiosim_impact/stats.json')
        .then(response => response.json())
        .then(data => {
            // Bar Chart: Publications per Year
            Plotly.newPlot('pubsPerYear', 
                [{
                    x: data.totalPublicationsPerYear.x, 
                    y: data.totalPublicationsPerYear.y, 
                    type: 'bar'
                }], 
                {
                    title: { text: 'Publications Per Year' }, 
                    xaxis: { tickmode: 'linear', dtick: 1 }
                }
            );

            // Bar Chart: Publications Per Year Per Grant Code
            let traces = Object.entries(data.papersPerGrant).map(([grantCode, values]) => ({
                x: values.x, 
                y: values.y, 
                type: 'bar', 
                name: grantCode
            }));

            Plotly.newPlot('pubsPerGrant', traces, { 
                title: { text: 'Publications Per Grant Code' }, 
                xaxis: { tickmode: 'linear', dtick: 1 }, 
                yaxis: { title: 'Publications' }
            });

            // Bar Chart: Publications per Month (All-Time)
            Plotly.newPlot('pubsPerMonth', 
                [{
                    x: data.totalPublicationsPerMonth.x, 
                    y: data.totalPublicationsPerMonth.y, 
                    type: 'bar'
                }], 
                {
                    title: { text: 'Total Publications Per Month' }, 
                    xaxis: { tickmode: 'linear', dtick: 1 }
                }
            );

            // Bar Chart: Total Publications Per Grant
            Plotly.newPlot('totalPubsPerGrant', 
                [{
                    x: Object.keys(data.papersPerGrantCount), 
                    y: Object.values(data.papersPerGrantCount), 
                    type: 'bar'
                }], 
                {
                    title: { text: 'Total Publications Per Grant' }, 
                    xaxis: { title: 'Grant Code' }, 
                    yaxis: { title: 'Publications' }
                }
            );

            // Bar Chart: Papers in Top Journals
            Plotly.newPlot('topJournals', 
                [{
                    x: data.topJournals.x, 
                    y: data.topJournals.y, 
                    type: 'bar'
                }], 
                {
                    title: { text: 'Papers in Top Journals' }
                }
            );

            // Display Stats as Text
            document.getElementById("totalPubsDiv").innerHTML = `<h3>Total Papers: ${data.totalPublications}</h3>`;
            document.getElementById("totalTopPapersDiv").innerHTML = `<h3>Total Papers in Top Ten Journals: ${data.totalTopPapers}</h3>`;      
            document.getElementById("uniqueAuthorsDiv").innerHTML = `<h3>Unique Authors: ${data.uniqueAuthors}</h3>`;
        })
        .catch(error => console.error('Error loading JSON:', error));
</script>
