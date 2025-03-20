<?php
/**
 * @package    com_hecbiosim_impact
 * @copyright  2025 HECBioSim Team
 * @license    MIT
 */

// No direct access
<html>
    <!-- Load the latest version of Plotly -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <div id="myDiv"></div>
    <div id="myDiv2"></div>
    <div id="myDiv3"></div>

    <script>
        fetch('stats.json')
            .then(response => response.json())
            .then(data => {
             
                Plotly.newPlot('myDiv', [{
                    x: data.barChart.x,
                    y: data.barChart.y,
                    type: 'bar'
                }]);
                

                Plotly.newPlot('myDiv2', [{
                    x: data.lineChart.x,
                    y: data.lineChart.y,
                    type: 'line'
                }]);
                
      
                Plotly.newPlot('myDiv3', [{
                    x: data.scatterPlot.x,
                    y: data.scatterPlot.y,
                    mode: 'markers',
                    type: 'scatter'
                }]);
            })
            .catch(error => console.error('Error loading JSON:', error));
    </script>
</html>









?>

// Put HTML here.

