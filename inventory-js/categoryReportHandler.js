class CategoryReportHandler {
    constructor() {
        this.categoryData = [];
        this.chart = null;

        // نؤخر عرض البيانات حتى يتم جلبها
        fetch('/category-data')
            .then(res => res.json())
            .then(data => {
                this.categoryData = data;
                this.displayCategoryReport(); // هنا فقط نعرض التقرير بعد استلام البيانات
            });
    }


    initializeDates() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

        const fromDateCat = document.getElementById('fromDateCat');
        const toDateCat = document.getElementById('toDateCat');

        if (fromDateCat && toDateCat) {
            fromDateCat.valueAsDate = firstDay;
            toDateCat.valueAsDate = today;
        }
    }

    displayCategoryReport() {
        const categoryReportBody = document.getElementById('categoryReportBody');
        if (!categoryReportBody) return;

        categoryReportBody.innerHTML = '';

        let totalPurchase = 0;
        let totalProduction = 0;
        let totalVariance = 0;

        this.categoryData.forEach(item => {
            const row = document.createElement('tr');

            // Add class for variance (positive or negative)
            const varianceClass = item.variance >= 0 ? 'positive' : 'negative';

            row.innerHTML = `
                <td>${item.category}</td>
                <td>${item.purchasePrice.toLocaleString('ar-EG')} ريال</td>
                <td>${item.productionValue.toLocaleString('ar-EG')} ريال</td>
                <td class="${varianceClass}">${item.variance.toLocaleString('ar-EG')} ريال</td>
            `;

            categoryReportBody.appendChild(row);

            totalPurchase += item.purchasePrice;
            totalProduction += item.productionValue;
            totalVariance += item.variance;
        });

        // Update totals
        document.getElementById('totalPurchase').textContent = totalPurchase.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('totalProduction').textContent = totalProduction.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('totalVariance').textContent = totalVariance.toLocaleString('ar-EG') + ' ريال';

        // Initialize or update chart
        this.initChart();
    }

    initChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: this.categoryData.map(item => item.category),
                datasets: [
                    {
                        label: 'سعر الشراء',
                        data: this.categoryData.map(item => item.purchasePrice),
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'قيمة الإنتاج',
                        data: this.categoryData.map(item => item.productionValue),
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'مقارنة سعر الشراء وقيمة الإنتاج حسب الفئة',
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'القيمة (ريال)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'الفئة'
                        }
                    }
                }
            }
        });
    }

    initialize() {
        this.initializeDates();

        // Add event listener for retrieve button
        const retrieveCatBtn = document.getElementById('retrieveCatBtn');
        if (retrieveCatBtn) {
            retrieveCatBtn.addEventListener('click', () => this.displayCategoryReport());
        }

        // Add export buttons
        // ExportPrintUtils.addExportPrintButtons(
        //     'category-report',
        //     'categoryReportTable',
        //     'تقرير_الفئات'
        // );

        // Initial display
        // this.displayCategoryReport();
    }
}
