  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
  <canvas id="progressChart"></canvas>
  <script>
  // Fetch data from the PHP script
  s.db.get({file:G.ADMIN_ROOT+'main/gpm/metric_xhr.php'},function(data){
console.log(data)
      // Create chart using Chart.js
      const ctx = document.getElementById('progressChart').getContext('2d');
      const myChart = new Chart(ctx, {
        type: 'line',
        data: {
          // Labels for the x-axis (weeks)
          labels: [...new Set(Object.values(data).flatMap(system => system.map(weekData => weekData.week)))], 
          datasets: Object.entries(data).map(([systemName, systemData]) => ({
            label: systemName,
            data: systemData.map(weekData => weekData.progress),
            // ... other Chart.js options
          }))
        },
        // ... other Chart.js options
      });

})
  </script>