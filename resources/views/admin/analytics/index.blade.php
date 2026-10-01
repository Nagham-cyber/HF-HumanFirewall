@extends('admin.layouts.app')

@section('title', 'Advanced Analytics')

@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<!-- Overview Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#4ADE80] mb-2">people</span>
        <p class="text-3xl font-black text-[#4ADE80]">{{ $stats['total_users'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total Users</p>
        <p class="text-[10px] mt-1 {{ $stats['new_users_week'] > 0 ? 'text-[#4ADE80]' : 'text-[#94A3B8]' }}">
            +{{ $stats['new_users_week'] }} this week
        </p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-[#60A5FA] mb-2">assignment</span>
        <p class="text-3xl font-black text-[#60A5FA]">{{ $stats['total_submissions'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Submissions</p>
        <p class="text-[10px] text-[#4ADE80] mt-1">{{ $stats['approved_submissions'] }} approved</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-orange-400 mb-2">pending</span>
        <p class="text-3xl font-black text-orange-400">{{ $stats['pending_review'] }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Pending Review</p>
    </div>
    <div class="card p-6 text-center">
        <span class="material-icons text-2xl text-yellow-400 mb-2">stars</span>
        <p class="text-3xl font-black text-yellow-400">{{ number_format($stats['total_xp_awarded']) }}</p>
        <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Total XP</p>
        <p class="text-[10px] text-[#94A3B8] mt-1">Avg: {{ $stats['avg_xp_per_user'] }} XP/user</p>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">trending_up</span>
            User Growth (Last 30 Days)
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#60A5FA]">show_chart</span>
            Submissions Trend (Last 30 Days)
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="submissionsTrendChart"></canvas>
        </div>
    </div>

</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-[#4ADE80]">pie_chart</span>
            Tasks by Section
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="tasksBySectionChart"></canvas>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-purple-400">donut_large</span>
            Scenarios by User Type
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="scenariosByTypeChart"></canvas>
        </div>
    </div>

</div>

<!-- Charts Row 3 -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-orange-400">bar_chart</span>
            Submissions by Status
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="submissionsByStatusChart"></canvas>
        </div>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
            <span class="material-icons text-yellow-400">equalizer</span>
            Task Difficulty Distribution
        </h3>
        <div style="height: 250px; position: relative;">
            <canvas id="taskDifficultyChart"></canvas>
        </div>
    </div>

</div>

<!-- Top Performers -->
<div class="card p-6 mb-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-[#4ADE80]">emoji_events</span>
        Top 10 Performers
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="border-b border-[#4ADE80]/10">
                    <th class="text-left py-2 text-[#94A3B8] uppercase">#</th>
                    <th class="text-left py-2 text-[#94A3B8] uppercase">Name</th>
                    <th class="text-center py-2 text-[#94A3B8] uppercase">XP</th>
                    <th class="text-center py-2 text-[#94A3B8] uppercase">Completed</th>
                    <th class="text-center py-2 text-[#94A3B8] uppercase">Mistakes</th>
                    <th class="text-center py-2 text-[#94A3B8] uppercase">Streak</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topPerformers as $index => $user)
                <tr class="border-b border-[#4ADE80]/5 hover:bg-[#4ADE80]/5">
                    <td class="py-2 font-bold
                        @if($index == 0) text-yellow-400
                        @elseif($index == 1) text-gray-300
                        @elseif($index == 2) text-amber-600
                        @else text-[#94A3B8] @endif">
                        #{{ $index + 1 }}
                    </td>
                    <td class="py-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold text-[10px]">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold">{{ $user->name }}</p>
                                <p class="text-[10px] text-[#94A3B8]">{{ $user->security_rank ?? 'Novice' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-center font-bold text-[#4ADE80]">{{ $user->xp_points }}</td>
                    <td class="text-center text-[#60A5FA]">{{ $user->completed_tasks }}</td>
                    <td class="text-center text-red-400">{{ $user->total_mistakes }}</td>
                    <td class="text-center text-orange-400">{{ $user->streak_days }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Weak Areas -->
<div class="card p-6 mb-6">
    <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
        <span class="material-icons text-red-400">warning</span>
        Weak Areas (Most Common Mistakes)
    </h3>
    <div class="space-y-3">
        @forelse($weakAreas as $area)
        <div>
            <div class="flex justify-between items-center mb-1">
                <span class="text-xs font-bold capitalize">{{ str_replace('_', ' ', $area->category) }}</span>
                <span class="text-xs font-black text-red-400">{{ $area->total }}</span>
            </div>
            <div class="w-full h-1.5 bg-red-500/10 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-red-400 to-orange-400 rounded-full"
                     style="width: {{ $weakAreas->max('total') > 0 ? ($area->total / $weakAreas->max('total')) * 100 : 0 }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-xs text-[#94A3B8] text-center py-4">No mistakes recorded yet.</p>
        @endforelse
    </div>
</div>

<!-- Weekly Comparison -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="card p-6">
        <h3 class="text-xs text-[#94A3B8] uppercase font-bold mb-3">New Users</h3>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-black text-[#4ADE80]">{{ $thisWeek['users'] }}</p>
            @php
                $diff = $thisWeek['users'] - $lastWeek['users'];
                $percent = $lastWeek['users'] > 0 ? round(($diff / $lastWeek['users']) * 100) : 0;
            @endphp
            <span class="text-xs font-bold {{ $diff >= 0 ? 'text-[#4ADE80]' : 'text-red-400' }}">
                {{ $diff >= 0 ? '↑' : '↓' }} {{ abs($percent) }}%
            </span>
        </div>
        <p class="text-[10px] text-[#94A3B8] mt-1">vs last week ({{ $lastWeek['users'] }})</p>
    </div>

    <div class="card p-6">
        <h3 class="text-xs text-[#94A3B8] uppercase font-bold mb-3">Submissions</h3>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-black text-[#60A5FA]">{{ $thisWeek['submissions'] }}</p>
            @php
                $diff = $thisWeek['submissions'] - $lastWeek['submissions'];
                $percent = $lastWeek['submissions'] > 0 ? round(($diff / $lastWeek['submissions']) * 100) : 0;
            @endphp
            <span class="text-xs font-bold {{ $diff >= 0 ? 'text-[#4ADE80]' : 'text-red-400' }}">
                {{ $diff >= 0 ? '↑' : '↓' }} {{ abs($percent) }}%
            </span>
        </div>
        <p class="text-[10px] text-[#94A3B8] mt-1">vs last week ({{ $lastWeek['submissions'] }})</p>
    </div>

    <div class="card p-6">
        <h3 class="text-xs text-[#94A3B8] uppercase font-bold mb-3">Completed</h3>
        <div class="flex items-end justify-between">
            <p class="text-2xl font-black text-purple-400">{{ $thisWeek['completed'] }}</p>
            @php
                $diff = $thisWeek['completed'] - $lastWeek['completed'];
                $percent = $lastWeek['completed'] > 0 ? round(($diff / $lastWeek['completed']) * 100) : 0;
            @endphp
            <span class="text-xs font-bold {{ $diff >= 0 ? 'text-[#4ADE80]' : 'text-red-400' }}">
                {{ $diff >= 0 ? '↑' : '↓' }} {{ abs($percent) }}%
            </span>
        </div>
        <p class="text-[10px] text-[#94A3B8] mt-1">vs last week ({{ $lastWeek['completed'] }})</p>
    </div>
</div>

<script>
    // ==================== Chart.js Global Config ====================
    Chart.defaults.color = '#94A3B8';
    Chart.defaults.borderColor = 'rgba(74, 222, 128, 0.1)';
    Chart.defaults.font.family = 'Inter, sans-serif';

    // ==================== User Growth Chart ====================
    const userGrowthData = @json($userGrowth);
    new Chart(document.getElementById('userGrowthChart'), {
        type: 'line',
        data: {
            labels: userGrowthData.map(d => d.date),
            datasets: [{
                label: 'New Users',
                data: userGrowthData.map(d => d.count),
                borderColor: '#4ADE80',
                backgroundColor: 'rgba(74, 222, 128, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4ADE80',
                pointBorderColor: '#0A0A0F',
                pointBorderWidth: 2,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(74, 222, 128, 0.05)' } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
            }
        }
    });

    // ==================== Submissions Trend Chart ====================
    const submissionsData = @json($submissionsTrend);
    new Chart(document.getElementById('submissionsTrendChart'), {
        type: 'line',
        data: {
            labels: submissionsData.map(d => d.date),
            datasets: [{
                label: 'Submissions',
                data: submissionsData.map(d => d.count),
                borderColor: '#60A5FA',
                backgroundColor: 'rgba(96, 165, 250, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#60A5FA',
                pointBorderColor: '#0A0A0F',
                pointBorderWidth: 2,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(96, 165, 250, 0.05)' } },
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } }
            }
        }
    });

    // ==================== Tasks by Section Chart ====================
    const tasksBySection = @json($tasksBySection);
    new Chart(document.getElementById('tasksBySectionChart'), {
        type: 'doughnut',
        data: {
            labels: tasksBySection.map(t => t.section.replace(/_/g, ' ').toUpperCase()),
            datasets: [{
                data: tasksBySection.map(t => t.total),
                backgroundColor: [
                    'rgba(74, 222, 128, 0.7)',
                    'rgba(96, 165, 250, 0.7)',
                    'rgba(168, 85, 247, 0.7)',
                    'rgba(251, 146, 60, 0.7)',
                    'rgba(250, 204, 21, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                ],
                borderColor: '#0A0A0F',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#94A3B8', font: { size: 10 }, padding: 10 }
                }
            }
        }
    });

    // ==================== Scenarios by User Type Chart ====================
    const scenariosByType = @json($scenariosByType);
    new Chart(document.getElementById('scenariosByTypeChart'), {
        type: 'doughnut',
        data: {
            labels: scenariosByType.map(s => s.user_type.replace(/_/g, ' ').toUpperCase()),
            datasets: [{
                data: scenariosByType.map(s => s.total),
                backgroundColor: [
                    'rgba(74, 222, 128, 0.7)',
                    'rgba(96, 165, 250, 0.7)',
                    'rgba(168, 85, 247, 0.7)',
                ],
                borderColor: '#0A0A0F',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#94A3B8', font: { size: 10 }, padding: 10 }
                }
            }
        }
    });

    // ==================== Submissions by Status Chart ====================
    const submissionsByStatus = @json($submissionsByStatus);
    new Chart(document.getElementById('submissionsByStatusChart'), {
        type: 'bar',
        data: {
            labels: submissionsByStatus.map(s => s.status.replace(/_/g, ' ').toUpperCase()),
            datasets: [{
                data: submissionsByStatus.map(s => s.total),
                backgroundColor: [
                    'rgba(74, 222, 128, 0.7)',
                    'rgba(251, 146, 60, 0.7)',
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(74, 222, 128, 0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // ==================== Task Difficulty Chart ====================
    const taskDifficulty = @json($taskDifficultyStats);
    new Chart(document.getElementById('taskDifficultyChart'), {
        type: 'bar',
        data: {
            labels: taskDifficulty.map(d => 'Rating ' + d.difficulty_rating),
            datasets: [{
                data: taskDifficulty.map(d => d.total),
                backgroundColor: [
                    'rgba(74, 222, 128, 0.7)',
                    'rgba(74, 222, 128, 0.6)',
                    'rgba(251, 204, 21, 0.7)',
                    'rgba(251, 146, 60, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                ],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(74, 222, 128, 0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

@endsection