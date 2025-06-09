@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Monitoring</h2>

    <canvas id="jobStatusChart" width="400" height="300"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('jobStatusChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['SUCCESSFUL', 'FAULT', 'PENDING'],
            datasets: [{
                label: 'Jumlah Job',
                data: [
                    {{ $jobCounts['Successful'] ?? 0 }},
                    {{ $jobCounts['Faulted'] ?? 0 }},
                    {{ $jobCounts['Pending'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(144, 238, 144, 0.9)', // green
                    'rgba(255, 0, 0, 0.9)',     // red
                    'rgba(30, 144, 255, 0.9)'   // blue
                ],
                borderColor: [
                    'rgba(0, 128, 0, 1)',
                    'rgba(139, 0, 0, 1)',
                    'rgba(0, 0, 139, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'x',
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Process Status'
                    }
                }
            }
        }
    });
</script>
@endpush
