document.addEventListener('DOMContentLoaded', function() {
    // Initialize the dashboard handler
    const dashboardHandler = new DashboardHandler();
    dashboardHandler.initialize();

    // بيانات تجريبية للعرض
    // const items = [
    //     { name: "جهاز لابتوب ASUS", id: "10001", quantity: 5, unitPrice: 2500, unitCost: 2000, unitProfit: 500, totalCost: 10000, totalProfit: 2500, category: "category1" },
    //     { name: "شاشة سامسونج 24 بوصة", id: "10002", quantity: 8, unitPrice: 800, unitCost: 600, unitProfit: 200, totalCost: 4800, totalProfit: 1600, category: "category1" },
    //     { name: "لوحة مفاتيح لاسلكية", id: "10003", quantity: 12, unitPrice: 150, unitCost: 100, unitProfit: 50, totalCost: 1200, totalProfit: 600, category: "category2" },
    //     { name: "ماوس لاسلكي", id: "10004", quantity: 15, unitPrice: 100, unitCost: 70, unitProfit: 30, totalCost: 1050, totalProfit: 450, category: "category2" },
    //     { name: "سماعة بلوتوث", id: "10005", quantity: 10, unitPrice: 300, unitCost: 200, unitProfit: 100, totalCost: 2000, totalProfit: 1000, category: "category3" },
    //     { name: "كاميرا ويب HD", id: "10006", quantity: 7, unitPrice: 450, unitCost: 300, unitProfit: 150, totalCost: 2100, totalProfit: 1050, category: "category3" },
    //     { name: "راوتر واي فاي", id: "10007", quantity: 6, unitPrice: 500, unitCost: 350, unitProfit: 150, totalCost: 2100, totalProfit: 900, category: "category1" },
    // ];
    let items = [];
    fetch("/products/list")
    .then(response => response.json())
    .then(data => {
        items = Array.isArray(data) ? data : [];
        displayItems();
    });

    // function fetchProducts() {
    //     fetch("/products/list")
    //     .then(response => response.json())
    //     .then(items => {
    //         items.forEach(item => {
    //             console.log(item.name); // مثال
    //         });
    //         items = Array.isArray(items) ? items : [];

    //         displayItems(items);
    //     });
    // }



    const tableBody = document.getElementById('tableBody');
    const totalCostElement = document.getElementById('totalCost');
    const totalProfitElement = document.getElementById('totalProfit');
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const filterSelect = document.getElementById('filterSelect');

    // عرض البيانات في الجدول
    function displayItems(itemsToDisplay) {
        tableBody.innerHTML = '';

        let sumTotalCost = 0;
        let sumTotalProfit = 0;

        itemsToDisplay.forEach(item => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.id}</td>
                <td>${item.quantity}</td>
                <td>${item.unitPrice.toLocaleString('ar-EG')} ريال</td>
                <td>${item.unitCost.toLocaleString('ar-EG')} ريال</td>
                <td>${item.unitProfit.toLocaleString('ar-EG')} ريال</td>
                <td>${item.totalCost.toLocaleString('ar-EG')} ريال</td>
                <td>${item.totalProfit.toLocaleString('ar-EG')} ريال</td>
            `;

            tableBody.appendChild(row);
            sumTotalCost += item.totalCost;
            sumTotalProfit += item.totalProfit;
        });

        totalCostElement.textContent = sumTotalCost.toLocaleString('ar-EG') + ' ريال';
        totalProfitElement.textContent = sumTotalProfit.toLocaleString('ar-EG') + ' ريال';
    }

    // تصفية البيانات حسب البحث والفلتر
    function filterItems() {
        // fetchProducts();
        const searchTerm = searchInput.value.trim().toLowerCase();
        const filterValue = filterSelect.value;

        let filteredItems = items;
        // console.log(filteredItems,filterValue)

        // تطبيق فلتر التصنيف
        if (filterValue !== 'all') {
            filteredItems = filteredItems.filter(item => item.category === filterValue);
        }

        // تطبيق فلتر البحث
        if (searchTerm) {
            filteredItems = filteredItems.filter(item =>
                item.name.toLowerCase().includes(searchTerm) ||
                item.id.toLowerCase().includes(searchTerm)
            );
        }

        displayItems(filteredItems);
    }

    // تفعيل أحداث البحث والفلترة
    searchButton.addEventListener('click', filterItems);
    searchInput.addEventListener('keyup', function(event) {
        if (event.key === 'Enter') {
            filterItems();
        }
    });
    filterSelect.addEventListener('change', filterItems);

    // Tab functionality - keep this logic for behind-the-scenes tab switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Initialize the report viewer for the new report
    const reportViewer = new ReportViewer();

    // Initialize the category report handler
    const categoryReportHandler = new CategoryReportHandler();

    // Initialize the inventory handlers
    const inventoryEntryHandler = new InventoryEntryHandler();
    const bulkInventoryHandler = new BulkInventoryHandler();

    // Initialize the transfer handler
    const transferHandler = new TransferHandler();

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // This is now handled behind the scenes - user interacts with dashboard cards
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to clicked button and corresponding content
            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');

            // If switching to cost detail tab, display its data
            if (tabId === 'cost-detail') {
                displayCostDetail();
                // initCharts();
            } else if (tabId === 'inventory-summary') {
                displayInventorySummary();
            } else if (tabId === 'profit-cost-report') {
                // Render the profit and cost report
                const container = document.getElementById('profit-cost-report');
                reportViewer.renderProfitCostReport(container);
            } else if (tabId === 'category-report') {
                // No need to initialize each time, the handler manages its state
            } else if (tabId === 'inventory-entry') {
                bulkInventoryHandler.initialize();
            } else if (tabId === 'material-transfer') {
                // No need to initialize every time, handler manages state
            }
        });
    });

    // Set current date in date inputs
    const today = new Date();
    const fromDate = document.getElementById('fromDate');
    const toDate = document.getElementById('toDate');

    if (fromDate && toDate) {
        // Set default date range (current month)
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

        fromDate.valueAsDate = firstDay;
        toDate.valueAsDate = today;
    }

    // Cost detail report data
    // const costDetailData = [
    //     { productNumber: "1001", productName: "جهاز لابتوب ASUS", invUnit: "قطعة", invBeginning: 25000, qtyBeginning: 10, valuePurchase: 20000, qtyPurchase: 8, actualCost: 45000, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 30000, valueVariance: 15000, valueInvNet: 30000 },
    //     { productNumber: "1002", productName: "شاشة سامسونج 24 بوصة", invUnit: "قطعة", invBeginning: 6000, qtyBeginning: 10, valuePurchase: 4800, qtyPurchase: 8, actualCost: 10800, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 7200, valueVariance: 3600, valueInvNet: 7200 },
    //     { productNumber: "1003", productName: "لوحة مفاتيح لاسلكية", invUnit: "قطعة", invBeginning: 1200, qtyBeginning: 12, valuePurchase: 900, qtyPurchase: 9, actualCost: 2100, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 1200, valueVariance: 900, valueInvNet: 1200 },
    //     { productNumber: "1004", productName: "ماوس لاسلكي", invUnit: "قطعة", invBeginning: 1050, qtyBeginning: 15, valuePurchase: 700, qtyPurchase: 10, actualCost: 1750, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 1050, valueVariance: 700, valueInvNet: 1050 },
    //     { productNumber: "1005", productName: "سماعة بلوتوث", invUnit: "قطعة", invBeginning: 2000, qtyBeginning: 10, valuePurchase: 1600, qtyPurchase: 8, actualCost: 3600, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 2200, valueVariance: 1400, valueInvNet: 2200 },
    //     { productNumber: "1006", productName: "طابعة ليزر HP", invUnit: "قطعة", invBeginning: 8500, qtyBeginning: 5, valuePurchase: 7200, qtyPurchase: 4, actualCost: 15700, valueProduction: 0, valueTransfers: 500, consReversal: 300, valueEnding: 9000, valueVariance: 6700, valueInvNet: 9000 },
    //     { productNumber: "1007", productName: "قرص صلب خارجي", invUnit: "قطعة", invBeginning: 4200, qtyBeginning: 7, valuePurchase: 3600, qtyPurchase: 6, actualCost: 7800, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 5400, valueVariance: 2400, valueInvNet: 5400 },
    //     { productNumber: "1008", productName: "كاميرا رقمية كانون", invUnit: "قطعة", invBeginning: 12000, qtyBeginning: 4, valuePurchase: 9000, qtyPurchase: 3, actualCost: 21000, valueProduction: 0, valueTransfers: 1000, consReversal: 500, valueEnding: 15000, valueVariance: 6000, valueInvNet: 15000 },
    //     { productNumber: "1009", productName: "مكبر صوت JBL", invUnit: "قطعة", invBeginning: 3000, qtyBeginning: 6, valuePurchase: 2500, qtyPurchase: 5, actualCost: 5500, valueProduction: 0, valueTransfers: 300, consReversal: 200, valueEnding: 3500, valueVariance: 2000, valueInvNet: 3500 },
    //     { productNumber: "1010", productName: "شاحن محمول", invUnit: "قطعة", invBeginning: 1800, qtyBeginning: 12, valuePurchase: 1500, qtyPurchase: 10, actualCost: 3300, valueProduction: 0, valueTransfers: 0, consReversal: 0, valueEnding: 2100, valueVariance: 1200, valueInvNet: 2100 }
    // ];
    let costDetailData =[];
    fetch('/products/cost-details')
    .then(response => response.json())
    .then(data => {
        // console.log(data);
        costDetailData = Array.isArray(data) ? data : [];

        displayCostDetail(costDetailData) // يمكنك عرضها في جدول أو أي واجهة تريدها
    });

    function displayCostDetail() {
        const costDetailBody = document.getElementById('costDetailBody');
        if (!costDetailBody) return;

        costDetailBody.innerHTML = '';

        costDetailData.forEach(item => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${item.productNumber}</td>
                <td>${item.productName}</td>
                <td>${item.category}</td>
                <td>${item.invUnit}</td>
                <td>${item.qtyBeginning}</td>
                <td>${item.invBeginning.toLocaleString('ar-EG')} </td>
                <td>${item.valuePurchase.toLocaleString('ar-EG')} </td>
                <td>${item.qtyPurchase}</td>
                <td>${item.transfers}</td>
                <td>${item.valueTransfers.toLocaleString('ar-EG')} </td>
                <td>${item.qtySales}</td>
                <td>${item.valueSales.toLocaleString('ar-EG')} </td>
                <td>${item.damages}</td>
                <td>${item.valueDamages.toLocaleString('ar-EG')} </td>
                <td>${item.valueBook.toLocaleString('ar-EG')} </td>
                <td>${item.qtyBook}</td>
            `;

            costDetailBody.appendChild(row);
        });
    }


    // Initialize charts for cost detail report
    // function initCharts() {
    //     // Cost breakdown chart
    //     const costBreakdownCtx = document.getElementById('costBreakdownChart');
    //     if (costBreakdownCtx) {
    //         // Destroy existing chart instance if exists
    //         if (window.costBreakdownChart && typeof window.costBreakdownChart.destroy === 'function') {
    //             window.costBreakdownChart.destroy();
    //         }

    //         window.costBreakdownChart = new Chart(costBreakdownCtx, {
    //             type: 'pie',
    //             data: {
    //                 labels: costDetailData.map(item => item.productName),
    //                 datasets: [{
    //                     label: 'التكلفة الفعلية',
    //                     data: costDetailData.map(item => item.actualCost),
    //                     backgroundColor: [
    //                         '#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6'
    //                     ],
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 plugins: {
    //                     legend: {
    //                         position: 'right',
    //                     },
    //                     title: {
    //                         display: true,
    //                         text: 'توزيع التكلفة الفعلية حسب المنتج'
    //                     }
    //                 }
    //             }
    //         });
    //     }

    //     // Inventory value chart
    //     const inventoryValueCtx = document.getElementById('inventoryValueChart');
    //     if (inventoryValueCtx) {
    //         // Destroy existing chart instance if exists
    //         if (window.inventoryValueChart && typeof window.inventoryValueChart.destroy === 'function') {
    //             window.inventoryValueChart.destroy();
    //         }

    //         window.inventoryValueChart = new Chart(inventoryValueCtx, {
    //             type: 'bar',
    //             data: {
    //                 labels: costDetailData.map(item => item.productName),
    //                 datasets: [{
    //                     label: 'بداية المخزون',
    //                     data: costDetailData.map(item => item.invBeginning),
    //                     backgroundColor: '#3498db',
    //                 }, {
    //                     label: 'نهاية المخزون',
    //                     data: costDetailData.map(item => item.valueEnding),
    //                     backgroundColor: '#2ecc71',
    //                 }]
    //             },
    //             options: {
    //                 responsive: true,
    //                 plugins: {
    //                     title: {
    //                         display: true,
    //                         text: 'مقارنة قيم المخزون'
    //                     },
    //                 },
    //                 scales: {
    //                     y: {
    //                         beginAtZero: true
    //                     }
    //                 }
    //             }
    //         });
    //     }
    // }

    // Event listener for retrieve button
    // const retrieveBtn = document.getElementById('retrieveBtn');
    // if (retrieveBtn) {
    //     retrieveBtn.addEventListener('click', function() {
    //         displayCostDetail();
    //         initCharts();
    //     });
    // }

    // Physical Inventory Summary data
    const inventorySummaryData = [
        { productId: "P1248", productName: "DOP GLOVES VINYL MEDIUM", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 6000.00, physicalQty: 5100.00, qtyVariance: -900.00, srVariance: -7.72, beginningBal: 51.48, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 43.76, physicalBal: 0.00, transitBal: 0.00, cumVariance: -43.76 },
        { productId: "P1247", productName: "GS PAPER MEDIYM SIZE OO 500'S", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 600.00, physicalQty: 500.00, qtyVariance: -100.00, srVariance: -9.09, beginningBal: 9.09, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 0.00, physicalBal: 0.00, transitBal: 0.00, cumVariance: -9.09 },
        { productId: "P2042", productName: "GS PAPER SMALL CARRY BAG LARGE", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 500.00, physicalQty: 91.00, qtyVariance: -409.00, srVariance: -89.93, beginningBal: 89.93, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 13.36, physicalBal: 0.00, transitBal: 0.00, cumVariance: -89.93 },
        { productId: "P1356", productName: "DOP DEU PAPER SHEETS", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 240.00, physicalQty: 40.00, qtyVariance: -200.00, srVariance: -13.39, beginningBal: 100.47, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 0.00, physicalBal: 0.00, transitBal: 0.00, cumVariance: -13.39 },
        { productId: "P2063", productName: "GS PAPER LARGE CARRY BAG SMALL", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 540.00, physicalQty: 500.00, qtyVariance: -40.00, srVariance: -14.02, beginningBal: 14.02, receivedBal: 223.21, soldBal: 0.00, adjustedBal: 223.21, physicalBal: 0.00, transitBal: 0.00, cumVariance: -14.02 },
        { productId: "P1362", productName: "GS PLASTIC BOX BLACK", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 340.00, physicalQty: 320.00, qtyVariance: -20.00, srVariance: -5.40, beginningBal: 21.20, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 15.80, physicalBal: 0.00, transitBal: 0.00, cumVariance: -5.40 },
        { productId: "P2059", productName: "GS PAPER SOUP BOWL", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 75.00, physicalQty: 70.00, qtyVariance: -5.00, srVariance: -0.50, beginningBal: 16.50, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 7.00, physicalBal: 0.00, transitBal: 0.00, cumVariance: -0.50 },
        { productId: "P2767", productName: "SD CAN ICE TEA PEACH 240G", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 32.00, physicalQty: 15.00, qtyVariance: -17.00, srVariance: -40.80, beginningBal: 40.80, receivedBal: 0.00, soldBal: 0.00, adjustedBal: 7.20, physicalBal: 0.00, transitBal: 0.00, cumVariance: -40.80 },
        { productId: "P2769", productName: "DG DATES BITE 250GMH", uom: "قطعة", unit: "EA", adjustedQty: 0.00, bookQty: 159.33, physicalQty: 145.00, qtyVariance: -14.33, srVariance: -24.74, beginningBal: 62.23, receivedBal: 27.00, soldBal: 164.47, adjustedBal: 64.47, physicalBal: 0.00, transitBal: 0.00, cumVariance: -24.74 },
    ];

    // Initialize date fields for inventory summary
    const fromDateInv = document.getElementById('fromDateInv');
    const toDateInv = document.getElementById('toDateInv');

    if (fromDateInv && toDateInv) {
        // Set default date range (current month)
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

        fromDateInv.valueAsDate = firstDay;
        toDateInv.valueAsDate = today;
    }

    // Display inventory summary report
    function displayInventorySummary() {
        const inventorySummaryBody = document.getElementById('inventorySummaryBody');
        if (!inventorySummaryBody) return;

        inventorySummaryBody.innerHTML = '';

        inventorySummaryData.forEach(item => {
            const row = document.createElement('tr');

            // Helper function to format numbers
            const formatNumber = (number) => {
                if (typeof number !== 'number') return '-';
                return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };

            // Add negative class for negative values
            const getClass = (value) => {
                if (typeof value !== 'number') return '';
                return value < 0 ? 'negative' : (value > 0 ? 'positive' : '');
            };

            row.innerHTML = `
                <td>${item.productId}</td>
                <td>${item.productName}</td>
                <td>${item.uom}</td>
                <td>${item.unit}</td>
                <td>${formatNumber(item.adjustedQty)}</td>
                <td>${formatNumber(item.bookQty)}</td>
                <td>${formatNumber(item.physicalQty)}</td>
                <td class="${getClass(item.qtyVariance)}">${formatNumber(item.qtyVariance)}</td>
                <td class="${getClass(item.srVariance)}">${formatNumber(item.srVariance)}</td>
                <td>${formatNumber(item.beginningBal)}</td>
                <td>${formatNumber(item.receivedBal)}</td>
                <td>${formatNumber(item.soldBal)}</td>
                <td>${formatNumber(item.adjustedBal)}</td>
                <td>${formatNumber(item.physicalBal)}</td>
                <td>${formatNumber(item.transitBal)}</td>
                <td class="${getClass(-item.cumVariance)}">${formatNumber(-item.cumVariance)}</td>
            `;

            inventorySummaryBody.appendChild(row);
        });
    }

    // Event listener for retrieve inventory button
    const retrieveInvBtn = document.getElementById('retrieveInvBtn');
    if (retrieveInvBtn) {
        retrieveInvBtn.addEventListener('click', function() {
            displayInventorySummary();
        });
    }

    // Initialize all components
    categoryReportHandler.initialize();
    transferHandler.initialize();

    // Initialize the waste disposal handler
    const wasteDisposalHandler = new WasteDisposalHandler();
    wasteDisposalHandler.initialize();

    // Initialize daily sales report handler
    const dailySalesReportHandler = new DailySalesReportHandler();
    dailySalesReportHandler.initialize();

    // Initialize monthly report handler
    const monthlyReportHandler = new MonthlyReportHandler();
    monthlyReportHandler.initialize();

    // Initialize report export handler
    const reportExportHandler = new ReportExportHandler();

    // Check which tab is active and initialize it
    if (document.querySelector('.tab-btn[data-tab="cost-detail"].active')) {
        displayCostDetail();
        // initCharts();
    } else if (document.querySelector('.tab-btn[data-tab="inventory-summary"].active')) {
        displayInventorySummary();
    } else if (document.querySelector('.tab-btn[data-tab="inventory-entry"].active')) {
        bulkInventoryHandler.initialize();
    } else if (document.querySelector('.tab-btn[data-tab="material-transfer"].active')) {
        transferHandler.loadProductsData();
        transferHandler.renderTransferTable();
    } else if (document.querySelector('.tab-btn[data-tab="waste-disposal"].active')) {
        wasteDisposalHandler.loadProductsData();
    } else if (document.querySelector('.tab-btn[data-tab="daily-sales-report"].active')) {
        dailySalesReportHandler.displaySalesReport();
    }

    // displayItems(items);
});
