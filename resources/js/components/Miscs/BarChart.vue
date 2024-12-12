<template>
    <div>
      <canvas ref="chartRef"></canvas>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watchEffect } from 'vue';
  import { Chart, registerables } from 'chart.js';
  
  // Register the necessary components from chart.js
  Chart.register(...registerables);
  
  const chartData = ref(null);
  const chartRef = ref(null);
  
  // Fetch JSON data when component is mounted
  onMounted(async () => {
    const response = await fetch('/data.json');
    const data = await response.json();
    chartData.value = {
      labels: data.labels,
      datasets: data.datasets
    };
    if (chartData.value) {
      const ctx = chartRef.value.getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: chartData.value,
        options: { responsive: true, maintainAspectRatio: false }
      });
    }
  });
  </script>
  