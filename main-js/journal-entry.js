// Journal entry specific functionality
function showJournalEntryForm() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="journal-entry-form">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>تسجيل قيد يومية</h2>
            
            <div class="journal-header">
                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" id="journalDate" value="${new Date().toISOString().split('T')[0]}">
                </div>
                <div class="form-group">
                    <label>رقم القيد</label>
                    <input type="text" id="journalNumber" readonly value="JE-2024-001">
                </div>
                <div class="form-group">
                    <label>الدفتر المحاسبي</label>
                    <select id="accountingBook" class="form-select">
                        <option value="">اختر الدفتر</option>
                        <option value="general">دفتر اليومية العام</option>
                        <option value="sales">دفتر المبيعات</option>
                        <option value="purchases">دفتر المشتريات</option>
                        <option value="cash">دفتر النقدية</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الفرع</label>
                    <select id="branch" class="form-select">
                        <option value="">اختر الفرع</option>
                        <option value="main">الفرع الرئيسي</option>
                        <option value="branch1">الفرع الأول</option>
                        <option value="branch2">الفرع الثاني</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>الموظف</label>
                    <select id="employee" class="form-select">
                        <option value="">اختر الموظف</option>
                        <option value="emp1">أحمد محمد</option>
                        <option value="emp2">محمد علي</option>
                        <option value="emp3">فاطمة أحمد</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>البيان</label>
                    <input type="text" id="journalDescription" placeholder="وصف القيد المحاسبي">
                </div>
            </div>

            <div class="journal-entries">
                <table>
                    <thead>
                        <tr>
                            <th>رقم الحساب</th>
                            <th>مدين</th>
                            <th>دائن</th>
                            <th>المركز</th>
                            <th>وصف القيد</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="entriesTable">
                        <tr>
                            <td>
                                <input type="text" class="account-number" placeholder="رقم الحساب" list="accountNumbersList">
                                <datalist id="accountNumbersList">
                                    <option value="1101" label="الصندوق">
                                    <option value="1102" label="البنك">
                                    <option value="4001" label="المبيعات">
                                    <option value="3001" label="المشتريات">
                                </datalist>
                            </td>
                            <td><input type="number" class="debit-amount" step="0.01"></td>
                            <td><input type="number" class="credit-amount" step="0.01"></td>
                            <td>
                                <select class="cost-center">
                                    <option value="">اختر المركز</option>
                                    <option value="1">فرع 1</option>
                                    <option value="2">فرع 2</option>
                                </select>
                            </td>
                            <td><input type="text" class="entry-description" placeholder="وصف تفصيلي للقيد"></td>
                            <td>
                                <button class="delete-row" onclick="deleteRow(this)">×</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="add-row-btn" onclick="addNewRow()">
                    <svg viewBox="0 0 24 24" class="icon">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    إضافة سطر
                </button>
            </div>

            <div class="journal-totals">
                <div class="total-group">
                    <label>إجمالي المدين</label>
                    <span id="totalDebit">0.00</span>
                </div>
                <div class="total-group">
                    <label>إجمالي الدائن</label>
                    <span id="totalCredit">0.00</span>
                </div>
                <div class="total-group">
                    <label>الفرق</label>
                    <span id="difference">0.00</span>
                </div>
            </div>

            <div class="form-actions">
                <button class="save-btn" onclick="saveJournalEntry()">حفظ القيد</button>
                <button class="cancel-btn" onclick="backToHome()">إلغاء</button>
            </div>
        </div>
    `;

    setupJournalEntryForm(document.querySelector('.journal-entry-form'));
}

// Update window functions to handle encoded data
window.printJournal = function(encodedData) {
    const journalData = JSON.parse(decodeURIComponent(encodedData));
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html dir="rtl">
            <head>
                <title>طباعة قيد يومية</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                    th { background-color: #f5f5f5; }
                    .header { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>قيد يومية</h2>
                    <p>رقم القيد: ${journalData.number}</p>
                    <p>التاريخ: ${journalData.date}</p>
                </div>
                ${generateJournalTable(journalData)}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
};

window.previewJournal = function(encodedData) {
    const journalData = JSON.parse(decodeURIComponent(encodedData));
    const previewModal = document.createElement('div');
    previewModal.className = 'modal preview-modal';
    previewModal.innerHTML = `
        <div class="modal-content">
            <div class="preview-container">
                <h2>معاينة قيد يومية</h2>
                <div class="preview-header">
                    <p>رقم القيد: ${journalData.number}</p>
                    <p>التاريخ: ${journalData.date}</p>
                </div>
                ${generateJournalTable(journalData)}
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    document.body.appendChild(previewModal);
    setupModalClose(previewModal);
};

window.copyJournal = function(encodedData) {
    const journalData = JSON.parse(decodeURIComponent(encodedData));
    // Create a new journal entry with the same data
    const newNumber = generateNewJournalNumber(journalData.number);
    const newData = {
        ...journalData,
        number: newNumber,
        date: new Date().toISOString().split('T')[0]
    };
    
    // Show success message
    const message = document.createElement('div');
    message.className = 'success-message';
    message.textContent = 'تم نسخ القيد بنجاح';
    document.body.appendChild(message);
    
    // Remove message after 3 seconds
    setTimeout(() => message.remove(), 3000);
    
    // Show the new journal entry form with copied data
    showJournalEntryForm();
};

function generateJournalTable(journalData) {
    return `
        <table>
            <thead>
                <tr>
                    <th>رقم الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>المركز</th>
                    <th>وصف القيد</th>
                </tr>
            </thead>
            <tbody>
                ${journalData.entries.map(entry => `
                    <tr>
                        <td>${entry.accountNumber}</td>
                        <td>${entry.debit || ''}</td>
                        <td>${entry.credit || ''}</td>
                        <td>${entry.costCenter}</td>
                        <td>${entry.description}</td>
                    </tr>
                `).join('')}
            </tbody>
            <tfoot>
                <tr>
                    <th>الإجمالي</th>
                    <th>${journalData.totalDebit}</th>
                    <th>${journalData.totalCredit}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    `;
}

function generateNewJournalNumber(oldNumber) {
    // Assuming number format is JE-YYYY-XXX
    const parts = oldNumber.split('-');
    const currentYear = new Date().getFullYear().toString();
    const sequence = parseInt(parts[2]) + 1;
    return `JE-${currentYear}-${sequence.toString().padStart(3, '0')}`;
}