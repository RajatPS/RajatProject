<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sales Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen">
    
    <!-- Laravel Session Messages -->
    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 max-w-md">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-lg shadow-2xl border border-green-400/30 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 max-w-md">
            <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-4 rounded-lg shadow-2xl border border-red-400/30 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-slate-800/50 backdrop-blur-sm border-b border-purple-500/20 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
                    Sales Dashboard
                </h1>
                <div class="flex gap-4 items-center flex-wrap">
                    <button
                        id="compareModeBtn"
                        onclick="toggleCompareMode()"
                        class="px-4 py-2 rounded-lg font-medium transition-all bg-slate-700 text-gray-300 hover:bg-slate-600"
                    >
                        Compare Years
                    </button>
                    <div id="singleYearSelect">
                        <select
                        id="selectedYear"
                        onchange="updateCharts()"
                        class="px-4 py-2 bg-slate-700 text-white rounded-lg border border-purple-500/30 focus:outline-none focus:border-purple-500">
                        {{-- MODIFY: Start from current year, go back 50 years --}}
                        @for ($i = 0; $i <= 10; $i++)
                            <option value="{{ $currentYear - $i}}">{{ $currentYear - $i}}</option>
                        @endfor
                    </select>
                    </div>

                    {{-- after comparison --}}
                    <div id="compareYearSelects" class="hidden flex gap-3 items-center">
                        <select
                            id="compareYear1"
                            onchange="updateCharts()"
                            class="px-4 py-2 bg-slate-700 text-white rounded-lg border border-purple-500/30 focus:outline-none focus:border-purple-500">
                        @for ($i = 0; $i <= 10; $i++)
                            <option value="{{ $currentYear - $i}}">{{ $currentYear - $i}}</option>
                        @endfor
                        </select>
                        <span class="text-purple-400 font-medium">vs</span>
                        <select
                            id="compareYear2"
                            onchange="updateCharts()"
                            class="px-4 py-2 bg-slate-700 text-white rounded-lg border border-purple-500/30 focus:outline-none focus:border-purple-500">
                        @for ($i = 0; $i <= 10; $i++)
                            <option value="{{ $currentYear - $i}}">{{ $currentYear - $i}}</option>
                        @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-200 text-sm font-medium">Total Sales</p>
                        <p class="text-white text-3xl font-bold mt-2" id="totalSales"></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-pink-600 to-pink-700 rounded-xl p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-200 text-sm font-medium">Total Orders</p>
                        <p class="text-white text-3xl font-bold mt-2" id="totalOrders"></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-shopping-cart text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200 text-sm font-medium">Total Customers</p>
                        <p class="text-white text-3xl font-bold mt-2" id="totalCustomers"></p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-violet-600 to-violet-700 rounded-xl p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-violet-200 text-sm font-medium">Avg Order Value</p>
                        <p class="text-white text-3xl font-bold mt-2" id="avgOrderValue">$0</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-purple-500/20">
                <h3 class="text-xl font-bold text-white mb-4" id="salesChartTitle">Monthly Sales - </h3>
                <div class="relative h-[300px] w-full">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-purple-500/20">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <h3 class="text-xl font-bold text-white">Sales Comparison</h3>
                    <div class="flex gap-3 items-center">
                        <select id="comparisonYear1" onchange="updateComparisonChart()" class="px-3 py-1.5 bg-slate-700 text-white text-sm rounded-lg border border-purple-500/30 focus:outline-none focus:border-purple-500">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                        <span class="text-purple-400 font-medium">vs</span>
                        <select id="comparisonYear2" onchange="updateComparisonChart()" class="px-3 py-1.5 bg-slate-700 text-white text-sm rounded-lg border border-purple-500/30 focus:outline-none focus:border-purple-500">
                            <option value="2024">2024</option>
                            <option value="2023" selected>2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>
                </div>
                <div class="relative h-[300px] w-full">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-purple-500/20">
                <h3 class="text-xl font-bold text-white mb-4">Sales by Category</h3>
                
                <div class="relative h-[300px] w-full">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-purple-500/20">
                <h3 class="text-xl font-bold text-white mb-4">Category Breakdown</h3>
                <div class="space-y-4" id="categoryBreakdown">
                    </div>
            </div>
        </div>

        <div class="mt-8 bg-gradient-to-r from-purple-900/50 to-pink-900/50 backdrop-blur-sm rounded-xl p-8 border border-purple-500/30 overflow-hidden">
            <h3 class="text-2xl font-bold text-white mb-6 text-center" id="summaryTitle">
                Year 2024 Summary
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-purple-300 text-sm font-medium mb-2">Total Revenue</p>
                    <p class="text-white text-3xl font-bold" id="summaryRevenue">$0</p>
                </div>
                <div class="text-center">
                    <p class="text-purple-300 text-sm font-medium mb-2">Total Orders</p>
                    <p class="text-white text-3xl font-bold" id="summaryOrders">0</p>
                </div>
                <div class="text-center">
                    <p class="text-purple-300 text-sm font-medium mb-2">Total Customers</p>
                    <p class="text-white text-3xl font-bold" id="summaryCustomers">0</p>
                </div>
                <div class="text-center">
                    <p class="text-purple-300 text-sm font-medium mb-2">Avg Order Value</p>
                    <p class="text-white text-3xl font-bold" id="summaryAvgOrder">$0</p>
                </div>
            </div>
        </div>
    </div>



                    {{-- use ajaxxx --}}
    <script>
        // Sample data for different years (In Laravel, you would pass this from controller)
        const salesData = {
            '2025': [
                { month: 'Jan', sales: 45000, orders: 320, customers: 285 },
                { month: 'Feb', sales: 52000, orders: 380, customers: 340 },
                { month: 'Mar', sales: 48000, orders: 350, customers: 310 },
                { month: 'Apr', sales: 61000, orders: 420, customers: 390 },
                { month: 'May', sales: 55000, orders: 390, customers: 350 },
                { month: 'Jun', sales: 67000, orders: 450, customers: 410 },
                { month: 'Jul', sales: 72000, orders: 490, customers: 440 },
                { month: 'Aug', sales: 68000, orders: 470, customers: 425 },
                { month: 'Sep', sales: 71000, orders: 485, customers: 450 },
                { month: 'Oct', sales: 76000, orders: 510, customers: 475 },
                { month: 'Nov', sales: 82000, orders: 550, customers: 510 },    
                { month: 'Dec', sales: 95000, orders: 620, customers: 580 }
            ],
            '2024': [
                { month: 'Jan', sales: 38000, orders: 280, customers: 250 },
                { month: 'Feb', sales: 42000, orders: 310, customers: 280 },
                { month: 'Mar', sales: 45000, orders: 330, customers: 295 },
                { month: 'Apr', sales: 51000, orders: 370, customers: 340 },
                { month: 'May', sales: 48000, orders: 350, customers: 320 },
                { month: 'Jun', sales: 58000, orders: 410, customers: 375 },
                { month: 'Jul', sales: 63000, orders: 440, customers: 400 },
                { month: 'Aug', sales: 59000, orders: 420, customers: 385 },
                { month: 'Sep', sales: 64000, orders: 450, customers: 410 },
                { month: 'Oct', sales: 68000, orders: 475, customers: 435 },
                { month: 'Nov', sales: 73000, orders: 500, customers: 465 },
                { month: 'Dec', sales: 84000, orders: 570, customers: 530 }
            ],
            '2023': [
                { month: 'Jan', sales: 32000, orders: 245, customers: 220 },
                { month: 'Feb', sales: 35000, orders: 270, customers: 245 },
                { month: 'Mar', sales: 38000, orders: 290, customers: 260 },
                { month: 'Apr', sales: 43000, orders: 320, customers: 295 },
                { month: 'May', sales: 41000, orders: 310, customers: 285 },
                { month: 'Jun', sales: 49000, orders: 360, customers: 330 },
                { month: 'Jul', sales: 54000, orders: 395, customers: 360 },
                { month: 'Aug', sales: 51000, orders: 375, customers: 345 },
                { month: 'Sep', sales: 56000, orders: 405, customers: 370 },
                { month: 'Oct', sales: 60000, orders: 430, customers: 395 },
                { month: 'Nov', sales: 65000, orders: 460, customers: 425 },
                { month: 'Dec', sales: 75000, orders: 520, customers: 485 }
            ]
        };

        const categoryData = [
            { name: 'Electronics', value: 35, amount: 245000, color: '#6366f1' },
            { name: 'Clothing', value: 25, amount: 175000, color: '#8b5cf6' },
            { name: 'Home & Garden', value: 20, amount: 140000, color: '#ec4899' },
            { name: 'Sports', value: 12, amount: 84000, color: '#f59e0b' },
            { name: 'Books', value: 8, amount: 56000, color: '#10b981' }
        ];

        let compareMode = false;
        let salesChart, comparisonChart, categoryChart;
        // let ordersCustomersChart; // Commented out

        function toggleCompareMode() {
            compareMode = !compareMode;
            const btn = document.getElementById('compareModeBtn');
            const singleSelect = document.getElementById('singleYearSelect');
            const compareSelects = document.getElementById('compareYearSelects');

            if (compareMode) {
                btn.classList.remove('bg-slate-700', 'text-gray-300', 'hover:bg-slate-600');
                btn.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-lg', 'shadow-purple-500/50');
                btn.textContent = 'Exit Compare';
                singleSelect.classList.add('hidden');
                compareSelects.classList.remove('hidden');
                compareSelects.classList.add('flex');
            } else {
                btn.classList.add('bg-slate-700', 'text-gray-300', 'hover:bg-slate-600');
                btn.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-lg', 'shadow-purple-500/50');
                btn.textContent = 'Compare Years';
                singleSelect.classList.remove('hidden');
                compareSelects.classList.add('hidden');
                compareSelects.classList.remove('flex');
            }

            updateCharts();
        }

        function calculateTotals(data) {
            const totalSales = document.getElementById('totalSales').textContent;
            const totalOrders = document.getElementById('totalOrders').textContent;
            const totalCustomers = document.getElementById('totalCustomers').textContent;
            const avgOrderValue = totalSales / totalOrders;

            return { totalSales, totalOrders, totalCustomers, avgOrderValue };
        }

        function updateStatsCards(data) {
            const totals = calculateTotals(data);
            
            document.getElementById('totalSales').textContent = totals.totalSales.toLocaleString();
            document.getElementById('totalOrders').textContent = totals.totalOrders.toLocaleString();
            document.getElementById('totalCustomers').textContent = totals.totalCustomers.toLocaleString();
            document.getElementById('avgOrderValue').textContent = `₹${totals.avgOrderValue.toFixed(0)}`;

            // Update summary
            document.getElementById('summaryRevenue').textContent = totals.totalSales.toLocaleString();
            document.getElementById('summaryOrders').textContent = totals.totalOrders.toLocaleString();
            document.getElementById('summaryCustomers').textContent = totals.totalCustomers.toLocaleString();
            document.getElementById('summaryAvgOrder').textContent = `₹${totals.avgOrderValue.toFixed()}`;
        }

        function updateCharts() {
            if (compareMode) {
                const year1 = document.getElementById('compareYear1').value;
                console.log(year1);
                const year2 = document.getElementById('compareYear2').value;
                updateCompareChart(year1, year2);
                document.getElementById('summaryTitle').textContent = `Year ${year1} vs ${year2} Summary`;
            } else {
                const year = document.getElementById('selectedYear').value;
                updateSingleYearChart(year);
                document.getElementById('summaryTitle').textContent = `Year ${year} Summary`;
            }
        }

        function updateSingleYearChart(year) {
            if (!salesData[year]) return;
            const data = salesData[year];
            updateStatsCards(data);

            const months = data.map(d => d.month);
            const sales = data.map(d => d.sales);

            document.getElementById('salesChartTitle').textContent = `Monthly Sales - ${year}`;

            if (salesChart) salesChart.destroy();

            salesChart = new Chart(document.getElementById('salesChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Sales ($)',
                        data: sales,
                        backgroundColor: '#8b5cf6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#9ca3af' } }
                    },
                    scales: {
                        y: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        },
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        }
                    }
                }
            });
        }

        function updateCompareChart(year1, year2) {
            const data1 = salesData[year1];
            const data2 = salesData[year2];
            
            // Use first year data for stats
            updateStatsCards(data1);

            const months = data1.map(d => d.month);
            const sales1 = data1.map(d => d.sales);
            const sales2 = data2.map(d => d.sales);

            document.getElementById('salesChartTitle').textContent = `Sales Comparison: ${year1} vs ${year2}`;

            if (salesChart) salesChart.destroy();

            salesChart = new Chart(document.getElementById('salesChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: year1,
                            data: sales1,
                            backgroundColor: '#8b5cf6',
                            borderRadius: 8
                        },
                        {
                            label: year2,
                            data: sales2,
                            backgroundColor: '#ec4899',
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#9ca3af' } }
                    },
                    scales: {
                        y: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        },
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        }
                    }
                }
            });
        }

        function updateComparisonChart() {
            const year1 = document.getElementById('comparisonYear1').value;
            const year2 = document.getElementById('comparisonYear2').value;
            
            const data1 = salesData[year1];
            const data2 = salesData[year2];

            const months = data1.map(d => d.month);
            const sales1 = data1.map(d => d.sales);
            const sales2 = data2.map(d => d.sales);

            if (comparisonChart) comparisonChart.destroy();

            comparisonChart = new Chart(document.getElementById('comparisonChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: `Sales ${year1}`,
                            data: sales1,
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#6366f1',
                            tension: 0.4
                        },
                        {
                            label: `Sales ${year2}`,
                            data: sales2,
                            borderColor: '#ec4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#ec4899',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#9ca3af' } } 
                    },
                    scales: {
                        y: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        },
                        x: {
                            ticks: { color: '#9ca3af' },
                            grid: { color: '#374151' }
                        }
                    }
                }
            });
        }

        function initCategoryChart() {
            const labels = categoryData.map(d => d.name);
            const values = categoryData.map(d => d.value);
            const colors = categoryData.map(d => d.color);

            categoryChart = new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#9ca3af', padding: 15 }
                        }
                    }
                }
            });

            // Update category breakdown
            const breakdownHTML = categoryData.map((cat, index) => `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded" style="background-color: ${cat.color}"></div>
                        <span class="text-gray-300 font-medium">${cat.name}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-bold">$${(cat.amount / 1000).toFixed(1)}k</p>
                        <p class="text-gray-400 text-sm">${cat.value}%</p>
                    </div>
                </div>
            `).join('');

            document.getElementById('categoryBreakdown').innerHTML = breakdownHTML;
        }

        // Initialize charts on page load
        window.addEventListener('load', function() {
            updateSingleYearChart('2024');
            updateComparisonChart();
            initCategoryChart();

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.fixed.top-4');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>