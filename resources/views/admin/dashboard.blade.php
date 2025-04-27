@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Queries for the Past 7 Days</h5>
        <canvas id="queriesChart" width="400" height="200"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('queriesChart').getContext('2d');
    var queriesChart = new Chart(ctx, {
        type: 'line', // you can also try 'line'
        data: {
            labels: @json($days),
            datasets: [{
                label: 'Number of Inquiries',
                data: @json($counts),
                backgroundColor: 'rgba(54, 162, 235, 0.7)', 
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
});
</script>

 @endsection
