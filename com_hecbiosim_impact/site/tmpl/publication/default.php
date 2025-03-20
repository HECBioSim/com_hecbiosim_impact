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
<style>

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }

      select, input {
           padding: 12px;
           font-size: 16px;
           border: 1px solid #ccc;
           border-radius: 5px;
           width: 240px;
    }

    #searchBar {
        flex-grow: 1;
        width: 100%;
    }

    .publication h3 {
        margin: 0;
        font-size: 18px;
        font-weight: normal;
    }

    .publication a {
        text-decoration: none;
        color: #0073e6;
        font-weight: bold;
    }

    .publication a:hover {
        text-decoration: underline;
    }

    .pub-details {
        flex-grow: 1;
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

</style>

<script>
    function displayPublications(data) {
        const container = document.getElementById("publications");
        container.innerHTML = "";
        data.forEach(pub => {
            const pubElement = document.createElement("div");
            pubElement.classList.add("publication");
            pubElement.innerHTML = `
                <h3>
                    <a href="${pub.doi}" target="_blank">${pub.title}</a>
                </h3>
                <div class="pub-details">
                    <span> ${pub.authors.join(", ")} (${pub.year}) <em>${pub.journal}</em> </span>
                </div>
            `;
            container.appendChild(pubElement);
        });
    }

    function filterPublications() {
        const query = document.getElementById("searchBar").value.trim().toLowerCase();
        const typeFilter = document.getElementById("typeFilter").value;
        const dateFilter = document.getElementById("dateFilter").value;
        const projectRefFilter = document.getElementById("projectRefFilter").value;

        const filtered = publicationsData.filter(pub =>
            (pub.title.toLowerCase().includes(query) ||
            pub.authors.some(author => author.toLowerCase().includes(query)) ||
            pub.year.toString().includes(query) ||
            pub.journal.toLowerCase().includes(query)) &&
            (typeFilter === "All" || pub.type === typeFilter) &&
            (dateFilter === "All" || pub.year.toString() === dateFilter) &&
            (projectRefFilter === "All" || pub.projectRef === projectRefFilter)
        );

        displayPublications(filtered);
    }

    document.getElementById("searchBar").addEventListener("input", filterPublications);
    document.getElementById("typeFilter").addEventListener("change", filterPublications);
    document.getElementById("dateFilter").addEventListener("change", filterPublications);
    document.getElementById("projectRefFilter").addEventListener("change", filterPublications);

    let publicationsData;

    const getjson = async () => {
      const response = await fetch("https://hecbiosim.github.io/com_hecbiosim_impact/pubs.json");
      const data = await response.json();
      publicationsData = data;
      return data;
    };

    (async () => {
        await getjson();
        displayPublications(publicationsData);
    })();

</script>

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

