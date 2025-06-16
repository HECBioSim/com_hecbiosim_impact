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
                xaxis: { title: 'Grant Code' }, 
                yaxis: { title: 'Publications' },
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
                x: Object.keys(data.papersPerGrantCount),
                y: Object.values(data.papersPerGrantCount),
                type: 'bar'
            }];

            var tj_data = [{
                x: data.topJournals.x,
                y: data.topJournals.y,
                type: 'bar'
            }];

            // Setup chart configs for responsive behaviour
            var config = {responsive: true}

            // Display Stats as Text
            document.getElementById("tp").innerHTML = `<h3>Total Papers: ${data.totalPublications}</h3>`;
            document.getElementById("ttp").innerHTML = `<h3>Total Papers in Top Ten Journals: ${data.totalTopPapers}</h3>`;      
            document.getElementById("ua").innerHTML = `<h3>Unique Authors: ${data.uniqueAuthors}</h3>`;

            // Bar chart - publications per year
            Plotly.newPlot('ppy', ppy_data, ppy_layout, config);

            // Bar chart - publications per year per grant code
            Plotly.newPlot('ppg', ppg_data, ppg_layout, config);

            // Bar chart - publications per month (all-time)
            Plotly.newPlot('ppm', ppm_data, ppm_layout, config);

            // Bar chart - all-time publications per grant
            Plotly.newPlot('tppg', tppg_data, tppg_layout, config);

            // Bar chart - all-time papers in top journals
            Plotly.newPlot('tj', tj_data, tj_layout, config);
        })
        .catch(error => console.error('Error loading JSON:', error));
}
