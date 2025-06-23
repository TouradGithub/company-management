/**
 * Utility functions for exporting and printing reports
 */
class ExportPrintUtils {
    /**
     * Export table data to Excel
     * @param {string} tableId - The ID of the table to export
     * @param {string} filename - The filename for the exported file
     */
    static exportTableToExcel(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) {
            alert('جدول غير موجود!');
            return;
        }

        // In a real implementation, this would use a library like SheetJS
        // or call a server-side API to generate the Excel file
        console.log(`Exporting table ${tableId} to Excel as ${filename}`);
        alert(`تم تصدير البيانات إلى ملف Excel بنجاح!\nالملف: ${filename}`);
    }

    /**
     * Export table data to PDF
     * @param {string} tableId - The ID of the table to export
     * @param {string} filename - The filename for the exported file
     * @param {string} title - The title for the PDF document
     */
    static exportTableToPDF(tableId, filename, title) {
        const table = document.getElementById(tableId);
        if (!table) {
            alert('جدول غير موجود!');
            return;
        }

        // In a real implementation, this would use a library like jsPDF
        // or call a server-side API to generate the PDF file
        console.log(`Exporting table ${tableId} to PDF as ${filename}`);
        alert(`تم تصدير البيانات إلى ملف PDF بنجاح!\nالملف: ${filename}`);
    }

    /**
     * Print a specific element
     * @param {string} elementId - The ID of the element to print
     * @param {string} title - The title for the printed document
     */
    static printElement(elementId, title) {
        const element = document.getElementById(elementId);
        if (!element) {
            alert('عنصر غير موجود!');
            return;
        }

        // In a real implementation, this would create a print-friendly version
        console.log(`Printing element ${elementId} with title ${title}`);
        alert(`جاري إرسال ${title} إلى الطابعة...`);
    }

    /**
     * Add export/print buttons to a report container
     * @param {string} containerId - The ID of the container to add buttons to
     * @param {string} tableId - The ID of the table to export/print
     * @param {string} reportName - The name of the report (used in filenames)
     */
    static addExportPrintButtons(containerId, tableId, reportName) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Check if buttons already exist
        if (container.querySelector('.export-print-buttons')) return;

        const buttonsDiv = document.createElement('div');
        buttonsDiv.className = 'export-print-buttons';

        buttonsDiv.innerHTML = `

        `;

        container.appendChild(buttonsDiv);

        // Add event listeners
        const excelBtn = buttonsDiv.querySelector('.excel-btn');
        const pdfBtn = buttonsDiv.querySelector('.pdf-btn');
        const printBtn = buttonsDiv.querySelector('.print-btn');

        excelBtn.addEventListener('click', () => {
            this.exportTableToExcel(tableId, `${reportName}_${new Date().toISOString().slice(0, 10)}.xlsx`);
        });

        pdfBtn.addEventListener('click', () => {
            this.exportTableToPDF(tableId, `${reportName}_${new Date().toISOString().slice(0, 10)}.pdf`, reportName);
        });

        printBtn.addEventListener('click', () => {
            this.printElement(containerId, reportName);
        });
    }
}
