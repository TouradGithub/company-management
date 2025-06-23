// New module for handling various reports
class ReportViewer {
    constructor() {
        this.reportsData = {};
        this.currentReport = null;
    }

    // Load profit and cost breakdown report data
    loadProfitCostReport() {
        this.reportsData.profitCost = [
            { productNumber: "R4603", recipeName: "5 SRV S/W ITALIAN BREAD ROAST BEEF", salesSR: 360.83, recipeYield: 7.45, costSR: 109.85, costPercent: 30.44, costPerServing: 69.56, grossProfit: 250.98, recipeGrossProfit: 7.98 },
            { productNumber: "R4591", recipeName: "5 SRV S/W ITALIAN BREAD CHICKEN", salesSR: 358.25, recipeYield: 7.40, costSR: 117.48, costPercent: 32.79, costPerServing: 67.21, grossProfit: 240.77, recipeGrossProfit: 7.65 },
            { productNumber: "R4788", recipeName: "5 SRV S/W WHEAT BREAD ROAST BEEF", salesSR: 280.00, recipeYield: 5.78, costSR: 85.39, costPercent: 30.50, costPerServing: 69.50, grossProfit: 194.61, recipeGrossProfit: 6.19 },
            { productNumber: "R4702", recipeName: "5 SRV S/W ITALIAN BREAD CHICKEN", salesSR: 254.49, recipeYield: 5.25, costSR: 78.49, costPercent: 30.84, costPerServing: 69.16, grossProfit: 176.00, recipeGrossProfit: 5.60 },
            { productNumber: "R7197", recipeName: "5 SRV S/W PLATTER COMBO", salesSR: 134.78, recipeYield: 2.78, costSR: 5.38, costPercent: 3.99, costPerServing: 96.01, grossProfit: 129.40, recipeGrossProfit: 4.11 },
            { productNumber: "R5292", recipeName: "5 SRV 12\" ITL BSTRDCHICN CMBCOLD", salesSR: 146.95, recipeYield: 3.03, costSR: 33.80, costPercent: 23.00, costPerServing: 77.00, grossProfit: 113.15, recipeGrossProfit: 3.60 },
            { productNumber: "R7196", recipeName: "5 SRV SANDWICH PLATTER", salesSR: 103.48, recipeYield: 2.14, costSR: 5.38, costPercent: 5.20, costPerServing: 94.80, grossProfit: 98.10, recipeGrossProfit: 3.12 },
            { productNumber: "R4790", recipeName: "5 SRV S/W WHEAT BREAD CHICKEN", salesSR: 140.00, recipeYield: 2.89, costSR: 45.61, costPercent: 32.58, costPerServing: 67.42, grossProfit: 94.39, recipeGrossProfit: 3.00 },
            { productNumber: "R4661", recipeName: "5 SRV S/W ITALIAN BREAD CHICKEN", salesSR: 104.48, recipeYield: 2.16, costSR: 22.60, costPercent: 21.63, costPerServing: 78.37, grossProfit: 81.88, recipeGrossProfit: 2.60 },
            { productNumber: "R5279", recipeName: "5 SRV 12\" WH. RSTD CHICKEN CMBCOLD", salesSR: 105.94, recipeYield: 2.21, costSR: 25.62, costPercent: 23.96, costPerServing: 76.04, grossProfit: 81.32, recipeGrossProfit: 2.59 },
            { productNumber: "R4914", recipeName: "5 SRV S/W ITALIAN BREAD CHICKEN", salesSR: 91.29, recipeYield: 1.88, costSR: 21.80, costPercent: 23.88, costPerServing: 76.12, grossProfit: 69.49, recipeGrossProfit: 2.21 },
            { productNumber: "R4908", recipeName: "5 SRV S/W ITALIAN BREAD ROAST BEEF", salesSR: 80.87, recipeYield: 1.67, costSR: 16.91, costPercent: 20.91, costPerServing: 79.09, grossProfit: 63.96, recipeGrossProfit: 2.03 },
            { productNumber: "R4912", recipeName: "5 SRV S/W ITALIAN BREAD PERP TUNA", salesSR: 88.71, recipeYield: 1.83, costSR: 26.07, costPercent: 29.39, costPerServing: 70.61, grossProfit: 62.64, recipeGrossProfit: 1.99 },
            { productNumber: "R4782", recipeName: "5 SRV S/W WHEAT BREAD PERP TUNA", salesSR: 80.88, recipeYield: 1.67, costSR: 25.35, costPercent: 22.58, costPerServing: 67.42, grossProfit: 54.53, recipeGrossProfit: 1.73 },
            { productNumber: "R5273", recipeName: "5 SRV 12\" WH ITALIAN S.M.T CMBCOLD", salesSR: 73.05, recipeYield: 1.51, costSR: 20.14, costPercent: 27.57, costPerServing: 72.43, grossProfit: 52.91, recipeGrossProfit: 1.68 }
        ];
        return this.reportsData.profitCost;
    }

    // Render the profit and cost breakdown report
    renderProfitCostReport(container) {
        if (!this.reportsData.profitCost) {
            this.loadProfitCostReport();
        }

        const data = this.reportsData.profitCost;

        // Create report container
        const reportDiv = document.createElement('div');
        reportDiv.className = 'profit-cost-report';

        // Create filters and controls
        const controlsDiv = document.createElement('div');
        controlsDiv.className = 'report-controls';

        // Add date range filters
        controlsDiv.innerHTML = `
            <div class="date-range-control">
                <div class="date-field">
                    <label>تاريخ البدء:</label>
                    <input type="date" id="profit-report-start-date">
                </div>
                <div class="date-field">
                    <label>تاريخ النهاية:</label>
                    <input type="date" id="profit-report-end-date">
                </div>
                <div class="group-field">
                    <label>المجموعة:</label>
                    <select id="profit-report-group">
                        <option value="N/A">N/A</option>
                    </select>
                </div>
                <div class="group-field">
                    <label>البائع:</label>
                    <select id="profit-report-server">
                        <option value="N/A">N/A</option>
                    </select>
                </div>
                <button id="profit-report-retrieve" class="report-btn">استعلام</button>
                <button id="profit-report-summary" class="report-btn">ملخص البائع</button>
            </div>
        `;

        // Create table for report data
        const tableDiv = document.createElement('div');
        tableDiv.className = 'table-responsive';

        const table = document.createElement('table');
        table.className = 'profit-cost-table';

        // Create table header
        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>رقم المنتج</th>
                <th>اسم الوصفة</th>
                <th>المبيعات (ريال)</th>
                <th>نسبة الإنتاج</th>
                <th>التكلفة (ريال)</th>
                <th>نسبة التكلفة %</th>
                <th>تكلفة الخدمة %</th>
                <th>إجمالي الربح</th>
                <th>إجمالي ربح الوصفة</th>
            </tr>
        `;
        table.appendChild(thead);

        // Create table body
        const tbody = document.createElement('tbody');
        data.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.productNumber}</td>
                <td>${item.recipeName}</td>
                <td>${item.salesSR.toFixed(2)}</td>
                <td>${item.recipeYield.toFixed(2)}</td>
                <td>${item.costSR.toFixed(2)}</td>
                <td>${item.costPercent.toFixed(2)}</td>
                <td>${item.costPerServing.toFixed(2)}</td>
                <td>${item.grossProfit.toFixed(2)}</td>
                <td>${item.recipeGrossProfit.toFixed(2)}</td>
            `;
            tbody.appendChild(row);
        });
        table.appendChild(tbody);

        // Create pagination controls
        const paginationDiv = document.createElement('div');
        paginationDiv.className = 'pagination';
        paginationDiv.innerHTML = `
            <button class="page-btn first">«</button>
            <button class="page-btn prev">‹</button>
            <span class="page-info">صفحة <span class="current-page">1</span> من <span class="total-pages">3</span></span>
            <button class="page-btn next">›</button>
            <button class="page-btn last">»</button>
            <span class="page-status">عرض 1 - 75 من 153</span>
        `;

        tableDiv.appendChild(table);

        // Assemble the report
        reportDiv.appendChild(controlsDiv);
        reportDiv.appendChild(tableDiv);
        reportDiv.appendChild(paginationDiv);

        // Add to container
        container.innerHTML = '';
        container.appendChild(reportDiv);

        // Set initial dates
        const startDateInput = document.getElementById('profit-report-start-date');
        const endDateInput = document.getElementById('profit-report-end-date');

        if (startDateInput && endDateInput) {
            const today = new Date();
            startDateInput.valueAsDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDateInput.valueAsDate = today;
        }

        // Add event listeners
        const retrieveBtn = document.getElementById('profit-report-retrieve');
        if (retrieveBtn) {
            retrieveBtn.addEventListener('click', () => {
                // In a real app, this would refresh the data based on filters
                console.log('Retrieving profit cost report data...');
            });
        }

        // Add export buttons
        // ExportPrintUtils.addExportPrintButtons(
        //     'profit-cost-report',
        //     'profit-cost-table',
        //     'تقرير_الربح_والتكلفة'
        // );

        // Dispatch event to notify the report is rendered
        document.dispatchEvent(new Event('profit-cost-report-rendered'));
    }
}
