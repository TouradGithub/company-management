class DailySalesReportHandler {
    constructor() {
        this.salesData = [];
        //     { date: "2023-10-01", dayName: "الأحد", salesBeforeTax: 12580.50, tax: 1887.08, totalSales: 14467.58, cashPayment: 5240.20, electronicPayment: 6120.35, messenger: 1240.55, hungerstation: 987.60, toyou: 580.24, kita: 298.64 },
        //     { date: "2023-10-02", dayName: "الإثنين", salesBeforeTax: 10420.75, tax: 1563.11, totalSales: 11983.86, cashPayment: 4180.15, electronicPayment: 5320.45, messenger: 980.35, hungerstation: 876.50, toyou: 420.33, kita: 206.08 },
        //     { date: "2023-10-03", dayName: "الثلاثاء", salesBeforeTax: 9870.25, tax: 1480.54, totalSales: 11350.79, cashPayment: 3985.20, electronicPayment: 4920.35, messenger: 865.40, hungerstation: 780.25, toyou: 520.69, kita: 278.90 },
        //     { date: "2023-10-04", dayName: "الأربعاء", salesBeforeTax: 11340.60, tax: 1701.09, totalSales: 13041.69, cashPayment: 4520.35, electronicPayment: 5680.15, messenger: 1045.20, hungerstation: 896.45, toyou: 620.78, kita: 278.76 },
        //     { date: "2023-10-05", dayName: "الخميس", salesBeforeTax: 15720.80, tax: 2358.12, totalSales: 18078.92, cashPayment: 6350.40, electronicPayment: 7240.25, messenger: 1420.60, hungerstation: 1580.35, toyou: 980.42, kita: 506.90 },
        //     { date: "2023-10-06", dayName: "الجمعة", salesBeforeTax: 18450.35, tax: 2767.55, totalSales: 21217.90, cashPayment: 7240.50, electronicPayment: 8520.65, messenger: 1745.30, hungerstation: 1890.25, toyou: 1240.65, kita: 580.55 },
        //     { date: "2023-10-07", dayName: "السبت", salesBeforeTax: 16840.25, tax: 2526.04, totalSales: 19366.29, cashPayment: 6760.15, electronicPayment: 7950.40, messenger: 1540.25, hungerstation: 1680.55, toyou: 980.24, kita: 454.70 },
        //     { date: "2023-10-08", dayName: "الأحد", salesBeforeTax: 13250.75, tax: 1987.61, totalSales: 15238.36, cashPayment: 5340.45, electronicPayment: 6240.20, messenger: 1260.35, hungerstation: 1120.45, toyou: 670.20, kita: 606.71 },
        //     { date: "2023-10-09", dayName: "الإثنين", salesBeforeTax: 10980.45, tax: 1647.07, totalSales: 12627.52, cashPayment: 4380.25, electronicPayment: 5430.10, messenger: 980.50, hungerstation: 950.30, toyou: 540.45, kita: 345.92 },
        //     { date: "2023-10-10", dayName: "الثلاثاء", salesBeforeTax: 9950.30, tax: 1492.55, totalSales: 11442.85, cashPayment: 3980.15, electronicPayment: 4870.35, messenger: 890.25, hungerstation: 820.40, toyou: 510.75, kita: 370.95 },
        //     { date: "2023-10-11", dayName: "الأربعاء", salesBeforeTax: 11780.55, tax: 1767.08, totalSales: 13547.63, cashPayment: 4680.25, electronicPayment: 5920.20, messenger: 1090.45, hungerstation: 970.35, toyou: 580.24, kita: 306.14 },
        //     { date: "2023-10-12", dayName: "الخميس", salesBeforeTax: 16250.40, tax: 2437.56, totalSales: 18687.96, cashPayment: 6520.30, electronicPayment: 7860.50, messenger: 1540.20, hungerstation: 1480.65, toyou: 920.40, kita: 365.91 },
        //     { date: "2023-10-13", dayName: "الجمعة", salesBeforeTax: 19240.25, tax: 2886.04, totalSales: 22126.29, cashPayment: 7740.15, electronicPayment: 9020.55, messenger: 1820.30, hungerstation: 1950.45, toyou: 1240.65, kita: 354.19 },
        //     { date: "2023-10-14", dayName: "السبت", salesBeforeTax: 17450.35, tax: 2617.55, totalSales: 20067.90, cashPayment: 7020.50, electronicPayment: 8240.15, messenger: 1680.45, hungerstation: 1750.25, toyou: 1030.65, kita: 345.90 }
        // ];
        fetch('/daily-sales')
            .then(response => response.json())
            .then(data => {
                this.salesData = data;
                this.displaySalesReport();
                // console.log("تم تحميل بيانات المبيعات:", this.salesData);
            })
            .catch(error => console.error("فشل تحميل البيانات:", error));

        this.chart = null;



    }

    initializeDates() {
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

        const fromDateSales = document.getElementById('fromDateSales');
        const toDateSales = document.getElementById('toDateSales');

        if (fromDateSales && toDateSales) {
            fromDateSales.valueAsDate = firstDay;
            toDateSales.valueAsDate = today;
        }
    }

    displaySalesReport() {
        const salesReportBody = document.getElementById('salesReportTableBody');
        if (!salesReportBody) return;

        salesReportBody.innerHTML = '';

        let totalBeforeTax = 0;
        let totalTax = 0;
        let totalSales = 0;
        let totalCash = 0;
        let totalElectronic = 0;
        let totalMessenger = 0;
        let totalHungerstation = 0;
        let totalToyou = 0;
        let totalKita = 0;

        // Get date range
        const fromDate = document.getElementById('fromDateSales').value;
        const toDate = document.getElementById('toDateSales').value;

        const filteredData = this.filterDataByDateRange(fromDate, toDate);

        filteredData.forEach(item => {
            const row = document.createElement('tr');

            const dateObj = new Date(item.date);
            const formattedDate = dateObj.toLocaleDateString('ar-SA');

            row.innerHTML = `
            <td>${formattedDate}</td>
            <td>${item.salesBeforeTax.toLocaleString('ar-EG')}</td>
            <td>${item.tax.toLocaleString('ar-EG')}</td>
            <td>${item.totalSales.toLocaleString('ar-EG')}</td>
            <td>${item.hungerstation.toLocaleString('ar-EG')}</td>
            <td>${item.toyou.toLocaleString('ar-EG')}</td>
            <td>${item.kita.toLocaleString('ar-EG')}</td>
        `;


            salesReportBody.appendChild(row);

            // Update totals
            totalBeforeTax += item.salesBeforeTax;
            totalTax += item.tax;
            totalSales += item.totalSales;
            totalCash += item.cashPayment;
            totalElectronic += item.electronicPayment;
            totalMessenger += item.messenger;
            totalHungerstation += item.hungerstation;
            totalToyou += item.toyou;
            totalKita += item.kita;
        });

        // Update payment type summary cards with totals
        document.getElementById('cashTotal').textContent = totalCash.toLocaleString('ar-EG') + ' ريال';
        document.getElementById('electronicTotal').textContent = totalElectronic.toLocaleString('ar-EG') + ' ريال';

        const totalDelivery = totalMessenger + totalHungerstation + totalToyou + totalKita;
        document.getElementById('deliveryTotal').textContent = totalDelivery.toLocaleString('ar-EG') + ' ريال';

        // Add totals row
        const totalsRow = document.createElement('tr');
        totalsRow.className = 'totals-row';
        totalsRow.innerHTML = `
            <td>الإجمالي</td>
            <td>${totalBeforeTax.toLocaleString('ar-EG')} ريال</td>
            <td>${totalTax.toLocaleString('ar-EG')} ريال</td>
            <td>${totalSales.toLocaleString('ar-EG')} ريال</td>
            <td>${totalHungerstation.toLocaleString('ar-EG')} ريال</td>
            <td>${totalToyou.toLocaleString('ar-EG')} ريال</td>
            <td>${totalKita.toLocaleString('ar-EG')} ريال</td>
        `;


        salesReportBody.appendChild(totalsRow);

        // Initialize or update chart
        this.initChart(filteredData);
    }

    filterDataByDateRange(fromDate, toDate) {
        if (!fromDate || !toDate) return this.salesData;

        return this.salesData.filter(item => {
            return item.date >= fromDate && item.date <= toDate;
        });
    }

    initChart(data) {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (this.chart) {
            this.chart.destroy();
        }

        // Prepare data for chart
        const dates = data.map(item => item.date);
        const salesValues = data.map(item => item.totalSales);
        const cashValues = data.map(item => item.cashPayment);
        const electronicValues = data.map(item => item.electronicPayment);
        const deliveryValues = data.map(item => item.messenger + item.hungerstation + item.toyou + item.kita);

        this.chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'إجمالي المبيعات',
                        data: salesValues,
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1,
                        type: 'line',
                        order: 0
                    },
                    {
                        label: 'مبيعات نقدية',
                        data: cashValues,
                        backgroundColor: 'rgba(46, 204, 113, 0.7)',
                        borderColor: 'rgba(46, 204, 113, 1)',
                        borderWidth: 1,
                        order: 1
                    },
                    {
                        label: 'دفع إلكتروني',
                        data: electronicValues,
                        backgroundColor: 'rgba(155, 89, 182, 0.7)',
                        borderColor: 'rgba(155, 89, 182, 1)',
                        borderWidth: 1,
                        order: 2
                    },
                    {
                        label: 'توصيل',
                        data: deliveryValues,
                        backgroundColor: 'rgba(243, 156, 18, 0.7)',
                        borderColor: 'rgba(243, 156, 18, 1)',
                        borderWidth: 1,
                        order: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'تحليل المبيعات اليومية',
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
                            text: 'التاريخ'
                        }
                    }
                }
            }
        });
    }

    // exportReport() {
    //     this.ExportPrintUtils.exportTableToExcel(
    //         'salesReportTable',
    //         `تقرير_المبيعات_اليومي_${new Date().toISOString().slice(0, 10)}.xlsx`
    //     );
    // }
    printReport() {
        const elementId = 'salesReportTable';
        const title = 'تقرير المبيعات اليومي';

        const tableElement = document.getElementById(elementId);
        if (!tableElement) {
            alert('جدول التقرير غير موجود');
            return;
        }

        const printWindow = window.open('', '', 'height=800,width=1000');

        printWindow.document.write(`
            <html>
                <head>
                    <title>${title}</title>
                    <style>
                        body { font-family: sans-serif; direction: rtl; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                        th { background-color: #f2f2f2; }
                        .totals-row td { font-weight: bold; background-color: #e0f7fa; }
                    </style>
                </head>
                <body>
                    <h2 style="text-align: center;">${title}</h2>
                    ${tableElement.outerHTML}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.focus();

        // أضف تأخير بسيط لتجنب مشكلة عدم تحميل الجدول قبل الطباعة
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }


    initialize() {
        this.initializeDates();

        // Add event listeners
        const retrieveSalesBtn = document.getElementById('retrieveSalesBtn');
        const exportSalesBtn = document.getElementById('exportSalesBtn');
        const printSalesBtn = document.getElementById('printSalesBtn');

        if (retrieveSalesBtn) {
            retrieveSalesBtn.addEventListener('click', () => this.displaySalesReport());
        }

        // if (exportSalesBtn) {
        //     exportSalesBtn.addEventListener('click', () => this.exportReport());
        // }

        if (printSalesBtn) {
            printSalesBtn.addEventListener('click', () => this.printReport());
        }

        // Add export buttons
        // ExportPrintUtils.addExportPrintButtons(
        //     'daily-sales-report',
        //     'salesReportTable',
        //     'تقرير_المبيعات_اليومي'
        // );

        // Initial display
        // this.displaySalesReport();
    }
}
