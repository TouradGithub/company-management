/**
 * Utility functions for exporting and printing data
 */

// Export assets table to Excel
function exportToExcel(tableId, filename = 'assets-report') {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Get all rows
    const rows = Array.from(table.querySelectorAll('tr'));
    
    // Extract headers from the first row
    const headers = Array.from(rows[0].querySelectorAll('th')).map(th => th.textContent.trim());
    
    // Remove the last column (actions) as we don't want to export it
    headers.pop();
    
    // Process data rows
    const data = rows.slice(1).map(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        
        // If this is an "empty results" row that spans multiple columns, return null
        if (cells.length === 1 && cells[0].hasAttribute('colspan')) {
            return null;
        }
        
        // Remove the last column (actions)
        cells.pop();
        
        // Extract text content from each cell
        return cells.map(cell => {
            // If cell contains a badge, get its text content
            const badge = cell.querySelector('.asset-badge');
            return badge ? badge.textContent.trim() : cell.textContent.trim();
        });
    }).filter(row => row !== null); // Filter out empty result rows
    
    // Create CSV content
    let csvContent = "\uFEFF"; // UTF-8 BOM for proper Arabic display
    
    // Add headers
    csvContent += headers.join(',') + '\n';
    
    // Add data rows
    data.forEach(row => {
        csvContent += row.join(',') + '\n';
    });
    
    // Create a Blob and download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `${filename}.csv`);
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export assets table to PDF
function exportToPDF(tableId, filename = 'assets-report') {
    // Check if jsPDF is available
    if (typeof jspdf === 'undefined') {
        loadJsPDF().then(() => exportToPDF(tableId, filename));
        return;
    }
    
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Create new jsPDF instance
    const doc = new jspdf.jsPDF({
        orientation: 'landscape',
        unit: 'mm',
        format: 'a4'
    });
    
    // Set RTL mode for Arabic text
    doc.setR2L(true);
    
    // Add title
    doc.setFontSize(18);
    doc.text('تقرير الأصول الثابتة', doc.internal.pageSize.width / 2, 15, { align: 'center' });
    
    // Get table data
    const rows = Array.from(table.querySelectorAll('tr'));
    const headers = Array.from(rows[0].querySelectorAll('th')).map(th => th.textContent.trim());
    
    // Remove the last column (actions) as we don't want to export it
    headers.pop();
    
    // Process data rows
    const data = rows.slice(1).map(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        
        // If this is an "empty results" row, return null
        if (cells.length === 1 && cells[0].hasAttribute('colspan')) {
            return null;
        }
        
        // Remove the last column (actions)
        cells.pop();
        
        // Extract text content from each cell
        return cells.map(cell => {
            // If cell contains a badge, get its text content
            const badge = cell.querySelector('.asset-badge');
            return badge ? badge.textContent.trim() : cell.textContent.trim();
        });
    }).filter(row => row !== null); // Filter out empty result rows
    
    // Create table in PDF
    doc.autoTable({
        head: [headers],
        body: data,
        startY: 25,
        theme: 'grid',
        styles: {
            font: 'Arial',
            halign: 'right',
            fontSize: 10
        },
        headStyles: {
            fillColor: [52, 152, 219],
            textColor: 255,
            fontStyle: 'bold'
        }
    });
    
    // Save PDF
    doc.save(`${filename}.pdf`);
}

// Print the table
function printTable(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Generate print-friendly HTML
    printWindow.document.write(`
        <html dir="rtl">
        <head>
            <title>تقرير الأصول الثابتة</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 30px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: right;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 15px;
                    }
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <h1>تقرير الأصول الثابتة</h1>
    `);
    
    // Clone the table and remove the actions column
    const tableClone = table.cloneNode(true);
    
    // Get all rows
    const rows = Array.from(tableClone.querySelectorAll('tr'));
    
    // Remove last cell (actions column) from each row
    rows.forEach(row => {
        const cells = row.querySelectorAll('th, td');
        if (cells.length > 0) {
            cells[cells.length - 1].remove();
        }
    });
    
    printWindow.document.write(tableClone.outerHTML);
    
    // Close the HTML
    printWindow.document.write(`
            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <button onclick="window.print();">طباعة</button>
                <button onclick="window.close();">إغلاق</button>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    
    // Automatically open print dialog after content loads
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.focus();
            printWindow.print();
        }, 500);
    };
}

// Preview the assets report
function previewReport(tableId) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Create a new window for preview
    const previewWindow = window.open('', '_blank');
    
    // Generate preview HTML with more details
    previewWindow.document.write(`
        <html dir="rtl">
        <head>
            <title>معاينة تقرير الأصول الثابتة</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f5f5f5;
                }
                .report-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: white;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .report-header {
                    text-align: center;
                    padding: 20px 0;
                    border-bottom: 2px solid #3498db;
                    margin-bottom: 20px;
                }
                .report-title {
                    font-size: 24px;
                    margin-bottom: 10px;
                    color: #2c3e50;
                }
                .report-date {
                    color: #7f8c8d;
                    font-size: 14px;
                }
                .report-summary {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-radius: 5px;
                    margin-bottom: 20px;
                }
                .summary-item {
                    display: inline-block;
                    margin-right: 30px;
                    margin-bottom: 10px;
                }
                .summary-label {
                    font-weight: bold;
                    color: #7f8c8d;
                }
                .summary-value {
                    font-size: 18px;
                    color: #2c3e50;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 30px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: right;
                }
                th {
                    background-color: #3498db;
                    color: white;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                .report-footer {
                    text-align: center;
                    padding: 20px 0;
                    border-top: 1px solid #eee;
                    color: #7f8c8d;
                    font-size: 12px;
                }
                .action-buttons {
                    text-align: center;
                    margin: 20px 0;
                }
                .action-button {
                    background-color: #3498db;
                    color: white;
                    border: none;
                    padding: 10px 15px;
                    margin: 0 5px;
                    border-radius: 4px;
                    cursor: pointer;
                    font-weight: bold;
                }
                .action-button:hover {
                    background-color: #2980b9;
                }
            </style>
        </head>
        <body>
            <div class="report-container">
                <div class="report-header">
                    <div class="report-title">تقرير الأصول الثابتة</div>
                    <div class="report-date">تاريخ التقرير: ${new Date().toLocaleDateString('ar-EG')}</div>
                </div>
    `);
    
    // Calculate summary data
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    let totalAssets = 0;
    let totalOriginalCost = 0;
    let totalDepreciation = 0;
    let totalBookValue = 0;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        // Skip "no results" rows
        if (cells.length > 1) {
            totalAssets++;
            
            // Get original cost from the 6th column (index 5)
            const originalCost = parseFloat(cells[5].textContent.replace(/,/g, ''));
            if (!isNaN(originalCost)) {
                totalOriginalCost += originalCost;
            }
            
            // Get depreciation from the 7th column (index 6)
            const depreciation = parseFloat(cells[6].textContent.replace(/,/g, ''));
            if (!isNaN(depreciation)) {
                totalDepreciation += depreciation;
            }
            
            // Get book value from the 8th column (index 7)
            const bookValue = parseFloat(cells[7].textContent.replace(/,/g, ''));
            if (!isNaN(bookValue)) {
                totalBookValue += bookValue;
            }
        }
    });
    
    // Add summary
    previewWindow.document.write(`
                <div class="report-summary">
                    <div class="summary-item">
                        <div class="summary-label">عدد الأصول:</div>
                        <div class="summary-value">${totalAssets}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">إجمالي تكلفة الشراء:</div>
                        <div class="summary-value">${totalOriginalCost.toLocaleString()}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">إجمالي مجمع الإهلاك:</div>
                        <div class="summary-value">${totalDepreciation.toLocaleString()}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">إجمالي القيمة الدفترية:</div>
                        <div class="summary-value">${totalBookValue.toLocaleString()}</div>
                    </div>
                </div>
    `);
    
    // Clone the table and remove the actions column
    const tableClone = table.cloneNode(true);
    
    // Get all rows
    const tableRows = Array.from(tableClone.querySelectorAll('tr'));
    
    // Remove last cell (actions column) from each row
    tableRows.forEach(row => {
        const cells = row.querySelectorAll('th, td');
        if (cells.length > 0) {
            cells[cells.length - 1].remove();
        }
    });
    
    previewWindow.document.write(tableClone.outerHTML);
    
    // Add footer and buttons
    previewWindow.document.write(`
                <div class="action-buttons">
                    <button class="action-button" onclick="window.print();">طباعة التقرير</button>
                    <button class="action-button" onclick="window.close();">إغلاق</button>
                </div>
                
                <div class="report-footer">
                    تم إنشاء هذا التقرير بواسطة نظام إدارة الأصول الثابتة © ${new Date().getFullYear()}
                </div>
            </div>
        </body>
        </html>
    `);
    
    previewWindow.document.close();
}

// Load jsPDF and its dependencies if needed
function loadJsPDF() {
    return new Promise((resolve, reject) => {
        // Check if jsPDF is already loaded
        if (typeof jspdf !== 'undefined') {
            resolve();
            return;
        }
        
        // Create script for jsPDF
        const jsPdfScript = document.createElement('script');
        jsPdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        jsPdfScript.onload = () => {
            // Load autoTable plugin
            const autoTableScript = document.createElement('script');
            autoTableScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js';
            autoTableScript.onload = resolve;
            document.head.appendChild(autoTableScript);
        };
        jsPdfScript.onerror = reject;
        document.head.appendChild(jsPdfScript);
    });
}

// Export all functions
window.exportToExcel = exportToExcel;
window.exportToPDF = exportToPDF;
window.printTable = printTable;
window.previewReport = previewReport;