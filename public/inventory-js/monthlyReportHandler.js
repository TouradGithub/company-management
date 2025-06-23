class MonthlyReportHandler {
    constructor() {
        this.reportData = {
            sales: 458750.25,
            purchases: 187520.75,
            purchaseReturns: 12580.50,
            salesReturns: 8750.40,
            currentInventoryValue: 245680.30,
            disposalValue: 8920.15,
            transferredInventoryValue: 32450.75,
            cashSales: 201850.35,
            electronicPaymentSales: 256899.90,
            grossProfit: 275600.60,
            totalExpenses: 142780.25,
            monthlyRent: 35000.00,
            monthlyWages: 85000.00,
            netProfit: 97820.35
        };

            // استخدم هذا الكود لعمل fetch للبيانات من السيرفر
    // fetch('/dashboard/stats')
    // .then(response => response.json())
    // .then(data => {
    //     this.reportData = data;
    //     // عرض البيانات في الصفحة
    //     // console.log('إجمالي المبيعات:', data.totalSales);
    //     // console.log('مبيعات نقدية:', data.cashSales);
    //     // console.log('مبيعات دفع إلكتروني:', data.electronicSales);
    //     // console.log('إجمالي المشتريات:', data.totalPurchases);
    //     // console.log('مردودات المشتريات:', data.purchaseReturns);
    //     // console.log('مردودات المبيعات:', data.salesReturns);
    //     // console.log('قيمة المخزون الحالي:', data.currentInventoryValue);

    //     // هنا يمكنك إضافة الكود لعرض البيانات في الـ HTML
    // })
    // .catch(error => console.error('حدث خطأ:', error));
        this.chart = null;
        this.barChart = null;
    }

    initialize() {
        // Set current month as default
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

        const monthSelect = document.getElementById('reportMonth');
        const yearSelect = document.getElementById('reportYear');

        if (monthSelect && yearSelect) {
            monthSelect.value = today.getMonth() + 1;
            yearSelect.value = today.getFullYear();
        }

        // Add event listeners
        const generateBtn = document.getElementById('generateReportBtn');
        if (generateBtn) {
            generateBtn.addEventListener('click', () => this.generateReport());
        }

        // Initialize the report
        this.generateReport();
        this.initCharts();

        // Add export buttons
        // ExportPrintUtils.addExportPrintButtons(
        //     'monthly-summary-report',
        //     'summarySalesTable',
        //     'التقرير_الشهري_العام'
        // );
    }

    generateReport() {
        // Update financial cards
        this.updateFinancialCards();

        // Update performance cards
        this.updatePerformanceCards();

        // Refresh charts
        this.initCharts();
   }

    updateFinancialCards() {
        // Update sales card
        document.getElementById('totalSalesValue').textContent = this.reportData.sales.toLocaleString('ar-EG') + ' ريال';

        // Update purchases card
        document.getElementById('totalPurchasesValue').textContent = this.reportData.purchases.toLocaleString('ar-EG') + ' ريال';

        // Update inventory card
        document.getElementById('currentInventoryValue').textContent = this.reportData.currentInventoryValue.toLocaleString('ar-EG') + ' ريال';

        // Update profit card
        document.getElementById('netProfitValue').textContent = this.reportData.netProfit.toLocaleString('ar-EG') + ' ريال';

        // Update sales details
        document.getElementById('cashSalesValue').textContent = this.reportData.cashSales.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('electronicSalesValue').textContent = this.reportData.electronicPaymentSales.toLocaleString('ar-EG') + ' ريال';

        // Update expenses details
        document.getElementById('totalExpensesValue').textContent = this.reportData.totalExpenses.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('monthlyRentValue').textContent = this.reportData.monthlyRent.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('monthlyWagesValue').textContent = this.reportData.monthlyWages.toLocaleString('ar-EG') + ' ريال';
    }

    updatePerformanceCards() {
        // Update returns indicators
        document.getElementById('purchaseReturnsValue').textContent = this.reportData.purchaseReturns.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('salesReturnsValue').textContent = this.reportData.salesReturns.toLocaleString('ar-EG') + ' ريال';

        // Calculate return percentages
        const purchaseReturnPercent = (this.reportData.purchaseReturns / this.reportData.purchases) * 100;
        const salesReturnPercent = (this.reportData.salesReturns / this.reportData.sales) * 100;

        document.getElementById('purchaseReturnsPercent').textContent = purchaseReturnPercent.toFixed(2) + '%';
        document.getElementById('salesReturnsPercent').textContent = salesReturnPercent.toFixed(2) + '%';

        // Update inventory movement indicators
        document.getElementById('disposalValue').textContent = this.reportData.disposalValue.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('transferredValue').textContent = this.reportData.transferredInventoryValue.toLocaleString('ar-EG') + ' ريال';

        // Update profit margin
        const profitMargin = (this.reportData.netProfit / this.reportData.sales) * 100;
        document.getElementById('profitMarginValue').textContent = profitMargin.toFixed(2) + '%';

        // Update gross profit
        document.getElementById('grossProfitValue').textContent = this.reportData.grossProfit.toLocaleString('ar-EG') + ' ريال';
    }

    initCharts() {
        this.initPieChart();
        this.initBarChart();
    }

    initPieChart() {
        const ctx = document.getElementById('summaryPieChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (this.chart) {
            this.chart.destroy();
        }

        // Data for pie chart - Revenue breakdown
        const data = {
            labels: ['إجمالي المبيعات', 'إجمالي المصروفات', 'صافي الربح'],
            datasets: [{
                data: [
                    this.reportData.sales,
                    this.reportData.totalExpenses,
                    this.reportData.netProfit
                ],
                backgroundColor: [
                    'rgba(52, 152, 219, 0.8)',  // Blue
                    'rgba(231, 76, 60, 0.8)',   // Red
                    'rgba(46, 204, 113, 0.8)'   // Green
                ],
                borderColor: [
                    'rgba(52, 152, 219, 1)',
                    'rgba(231, 76, 60, 1)',
                    'rgba(46, 204, 113, 1)'
                ],
                borderWidth: 1
            }]
        };

        this.chart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'تحليل الأداء المالي',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });
    }

    initBarChart() {
        const ctx = document.getElementById('summaryBarChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (this.barChart) {
            this.barChart.destroy();
        }

        // Data for bar chart - Monthly comparison
        const data = {
            labels: ['المبيعات النقدية', 'المبيعات الإلكترونية', 'المشتريات', 'الرواتب', 'الإيجار'],
            datasets: [{
                label: 'القيمة (ريال)',
                data: [
                    this.reportData.cashSales,
                    this.reportData.electronicPaymentSales,
                    this.reportData.purchases,
                    this.reportData.monthlyWages,
                    this.reportData.monthlyRent
                ],
                backgroundColor: [
                    'rgba(52, 152, 219, 0.7)',  // Blue
                    'rgba(155, 89, 182, 0.7)',  // Purple
                    'rgba(231, 76, 60, 0.7)',   // Red
                    'rgba(241, 196, 15, 0.7)',  // Yellow
                    'rgba(46, 204, 113, 0.7)'   // Green
                ],
                borderColor: [
                    'rgba(52, 152, 219, 1)',
                    'rgba(155, 89, 182, 1)',
                    'rgba(231, 76, 60, 1)',
                    'rgba(241, 196, 15, 1)',
                    'rgba(46, 204, 113, 1)'
                ],
                borderWidth: 1
            }]
        };

        this.barChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'مقارنة عناصر الدخل والمصروفات',
                        font: {
                            size: 16
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'القيمة (ريال)'
                        }
                    }
                }
            }
        });
    }

    // exportReport() {
    //     ExportPrintUtils.exportTableToExcel(
    //         'summarySalesTable',
    //         `التقرير_الشهري_العام_${new Date().toISOString().slice(0, 7)}.xlsx`
    //     );
    // }

    // printReport() {
    //     ExportPrintUtils.printElement(
    //         'monthly-summary-report',
    //         'التقرير الشهري العام'
    //     );
    // }
}
