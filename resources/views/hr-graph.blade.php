<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Rate Graph with Plotly</title>
    <script src="https://cdn.jsdelivr.net/npm/plotly.js-dist@2.18.0/plotly.min.js"></script>  <!-- Plotly.js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  <!-- jQuery for AJAX -->
</head>
<body>
    <h1>Heart Rate Over Time</h1>
    <div id="heartRateChart"></div> <!-- Container for the Plotly chart -->

    <script>
        // Initial graph data setup
        var trace1 = {
            x: [],  // Time data will go here
            y: [],  // Heart rate data will go here
            mode: 'lines',
            name: 'Heart Rate',
            line: {color: 'rgb(75, 192, 192)', width: 2}
        };

        var layout = {
            title: 'Heart Rate Over Time',
            xaxis: {
                title: 'Time',
                type: 'date',
                tickformat: '%Y-%m-%d %H:%M:%S'  // Format for time axis
            },
            yaxis: {
                title: 'Heart Rate',
                rangemode: 'tozero'
            }
        };

        // Plot the initial chart
        Plotly.newPlot('heartRateChart', [trace1], layout);

        // Function to update the graph with new data
        function updateGraph() {
            $.get('/heart-rate-data', function(data) {
                var newX = [];
                var newY = [];

                data.forEach(item => {
                    newX.push(item.timestamp);  // Timestamp from the API
                    newY.push(item.heart_rate);  // Heart rate from the API
                });

                // Update the graph with new data
                Plotly.update('heartRateChart', {
                    x: [newX],
                    y: [newY]
                });
            });
        }

        // Call updateGraph() every 5 seconds
        setInterval(updateGraph, 5000);
    </script>
</body>
</html>
