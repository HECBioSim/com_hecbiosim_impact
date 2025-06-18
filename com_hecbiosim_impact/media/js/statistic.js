window.onload = function(){
    // Fetch stats data from GitHub
    fetch('https://hecbiosim.github.io/com_hecbiosim_impact/stats.json')
        .then(response => response.json())
        .then(data => {

            var ppy_layout = {
                title: { text: 'Publications Per Year' },
                xaxis: { tickmode: 'linear', dtick: 1 },
                font: {size: 18}
            };

            var ppg_layout = {
                title: { text: 'Publications Per Grant Code' },
                xaxis: { tickmode: 'linear', dtick: 1 },
                yaxis: { title: 'Publications' },
                font: {size: 18}
            };

            var ppm_layout = {
                title: { text: 'Total Publications Per Month' },
                xaxis: { tickmode: 'linear', dtick: 1 },
                font: {size: 18}
            };

            var tppg_layout = {
                title: { text: 'Total Publications Per Grant' },
                showlegend: false,
                font: {size: 18}

            };

            var tj_layout = {
                title: { text: 'Papers in Top Journals' },
                font: {size: 18}
            };

            var ppy_data = [{
                x: data.totalPublicationsPerYear.x,
                y: data.totalPublicationsPerYear.y,
                type: 'bar'
            }];

            var ppg_data = Object.entries(data.papersPerGrant).map(([grantCode, values]) => ({
                x: values.x,
                y: values.y,
                type: 'bar',
                name: grantCode
            }));

            var ppm_data = [{
                x: data.totalPublicationsPerMonth.x,
                y: data.totalPublicationsPerMonth.y,
                type: 'bar'
            }];

            var tppg_data = [{
                labels: Object.keys(data.papersPerGrantCount),
                values: Object.values(data.papersPerGrantCount),
                textinfo: "label+percent",
                textposition: "outside",
                automargin: true,
                type: 'pie'
            }];

            var tj_data = [{
                x: data.topJournals.x,
                y: data.topJournals.y,
                type: 'bar'
            }];

            // Setup chart configs for responsive behaviour
            var config = {responsive: true}

            // Display Stats as Text
            document.getElementById("dash-alltime-pubs").innerHTML = `${data.totalPublications}`;
            document.getElementById("dash-top-pubs").innerHTML = `${data.totalTopPapers}`;      
            document.getElementById("dash-authors").innerHTML = `${data.uniqueAuthors}`;

            // Bar chart - publications per year
            Plotly.newPlot('chart-pubs-yr', ppy_data, ppy_layout, config);

            // Bar chart - publications per year per grant code
            Plotly.newPlot('chart-pubs-grant-yr', ppg_data, ppg_layout, config);

            // Bar chart - publications per month (all-time)
            Plotly.newPlot('chart-pubs-month', ppm_data, ppm_layout, config);

            // Bar chart - all-time publications per grant
            Plotly.newPlot('chart-pubs-grant', tppg_data, tppg_layout, config);

            // Bar chart - all-time papers in top journals
            Plotly.newPlot('chart-top-journal', tj_data, tj_layout, config);
        })
        .catch(error => console.error('Error loading JSON:', error));
}
