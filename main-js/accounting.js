// Accounting module functionality
function showAccountingModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="accounting-dashboard">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>المحاسبة المالية</h2>
            <div class="accounting-modules">
                <div class="accounting-module" onclick="navigate('journal-entry')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <path d="M4 8h16M4 12h16M4 16h10"/>
                        <path d="M17 16l2 2 3-3"/>
                    </svg>
                    <h3>تسجيل قيد يومية</h3>
                </div>
                <div class="accounting-module" onclick="navigate('view-journal')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <path d="M14 3v5h5M8 13h8M8 17h8M8 9h8"/>
                    </svg>
                    <h3>عرض قيود اليومية</h3>
                </div>
                <div class="accounting-module" onclick="navigate('account-statement')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 8v13H3V3h13"/>
                        <path d="M7 13h8M7 17h8M7 9h8"/>
                        <polyline points="18 2 22 6 18 10"/>
                    </svg>
                    <h3>كشف حساب</h3>
                </div>
                <div class="accounting-module" onclick="navigate('accounting-books')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                        <path d="M8 7h8M8 11h8M8 15h8"/>
                    </svg>
                    <h3>الدفاتر المحاسبية</h3>
                </div>
                <div class="accounting-module" onclick="navigate('accounts-tree')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 3v18M3 12h18M8 7h3M8 17h3M17 8v3M17 13v3"/>
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                    <h3>شجرة الحسابات</h3>
                </div>
                <div class="accounting-module" onclick="navigate('accounting-guide')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <h3>دليل المحاسبة</h3>
                </div>
            </div>
        </div>
    `;
}

window.backToHome = function() {
    location.reload();
};

// Update showAccountingModule to include the journal entries view
function showJournalViewModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="accounting-dashboard">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>عرض قيود اليومية</h2>
            
            <div class="journal-search">
                <div class="search-filters">
                    <input type="text" id="journalNumberSearch" placeholder="بحث برقم القيد">
                    <input type="text" id="accountNumberSearch" placeholder="بحث برقم الحساب">
                    <input type="date" id="dateFromSearch">
                    <input type="date" id="dateToSearch">
                    <button class="search-btn" onclick="searchJournals()">
                        <svg class="icon" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        بحث
                    </button>
                </div>
            </div>

            <div class="journal-entries-list">
                <table>
                    <thead>
                        <tr>
                            <th>رقم القيد</th>
                            <th>التاريخ</th>
                            <th>البيان</th>
                            <th>إجمالي المدين</th>
                            <th>إجمالي الدائن</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="journalEntriesTable">
                        <!-- سيتم تعبئة هذا الجزء ديناميكياً -->
                    </tbody>
                </table>
            </div>
            
            <div class="journal-pagination">
                <button class="pagination-btn" onclick="changePage('prev')">السابق</button>
                <span id="currentPage">صفحة 1 من 1</span>
                <button class="pagination-btn" onclick="changePage('next')">التالي</button>
            </div>
        </div>
    `;

    // Load initial data
    loadJournalEntries();
}

// Load and display journal entries
function loadJournalEntries(filters = {}) {
    // Retrieve journal entries from localStorage
    const storedEntries = JSON.parse(localStorage.getItem('journalEntries') || '[]');

    // If no entries exist, create some sample data
    if (storedEntries.length === 0) {
        const sampleEntries = [
            {
                number: 'JE-2024-001',
                date: '2024-01-15',
                description: 'تسجيل فاتورة مبيعات',
                totalDebit: 1000,
                totalCredit: 1000,
                entries: [
                    { accountNumber: '1101', debit: 1000, credit: 0, description: 'الصندوق' },
                    { accountNumber: '4001', debit: 0, credit: 1000, description: 'المبيعات' }
                ]
            },
            {
                number: 'JE-2024-002',
                date: '2024-01-16',
                description: 'تسجيل فاتورة مشتريات',
                totalDebit: 2000,
                totalCredit: 2000,
                entries: [
                    { accountNumber: '3001', debit: 2000, credit: 0, description: 'المشتريات' },
                    { accountNumber: '1102', debit: 0, credit: 2000, description: 'البنك' }
                ]
            }
        ];

        // Save sample entries to localStorage
        localStorage.setItem('journalEntries', JSON.stringify(sampleEntries));
    }

    // Apply filters if provided
    let filteredEntries = storedEntries;
    if (filters.journalNumber) {
        filteredEntries = filteredEntries.filter(entry => 
            entry.number.includes(filters.journalNumber)
        );
    }
    if (filters.accountNumber) {
        filteredEntries = filteredEntries.filter(entry => 
            entry.entries.some(row => row.accountNumber.includes(filters.accountNumber))
        );
    }
    if (filters.dateFrom) {
        filteredEntries = filteredEntries.filter(entry => 
            new Date(entry.date) >= new Date(filters.dateFrom)
        );
    }
    if (filters.dateTo) {
        filteredEntries = filteredEntries.filter(entry => 
            new Date(entry.date) <= new Date(filters.dateTo)
        );
    }

    const tbody = document.getElementById('journalEntriesTable');
    tbody.innerHTML = filteredEntries.map(entry => `
        <tr>
            <td>${entry.number}</td>
            <td>${new Date(entry.date).toLocaleDateString('ar-SA')}</td>
            <td>${entry.description}</td>
            <td>${entry.totalDebit.toFixed(2)}</td>
            <td>${entry.totalCredit.toFixed(2)}</td>
            <td class="journal-actions">
                <button class="action-btn preview-btn" onclick="previewJournal('${encodeURIComponent(JSON.stringify(entry))}')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
                <button class="action-btn print-btn" onclick="printJournal('${encodeURIComponent(JSON.stringify(entry))}')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M6 9V2h12v7"/>
                        <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                        <path d="M6 14h12v8H6z"/>
                    </svg>
                </button>
                <button class="action-btn edit-btn" onclick="editJournal('${encodeURIComponent(JSON.stringify(entry))}')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
                <button class="action-btn delete-btn" onclick="deleteJournal('${entry.number}')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                        <line x1="10" y1="11" x2="10" y2="17"/>
                        <line x1="14" y1="11" x2="14" y2="17"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');

    // Update pagination 
    updatePagination(filteredEntries);
}

// Update pagination 
function updatePagination(entries) {
    const totalPages = Math.ceil(entries.length / 10); // 10 entries per page
    const currentPageEl = document.getElementById('currentPage');
    currentPageEl.textContent = `صفحة 1 من ${totalPages}`;
}

function searchJournals() {
    const filters = {
        journalNumber: document.getElementById('journalNumberSearch').value,
        accountNumber: document.getElementById('accountNumberSearch').value,
        dateFrom: document.getElementById('dateFromSearch').value,
        dateTo: document.getElementById('dateToSearch').value
    };
    
    loadJournalEntries(filters);
}

function changePage(direction) {
    // Implement pagination logic
    console.log('Changing page:', direction);
}

window.deleteJournal = function(journalNumber) {
    if (confirm('هل أنت متأكد من حذف هذا القيد؟')) {
        // Remove from localStorage
        const entries = JSON.parse(localStorage.getItem('journalEntries') || '[]');
        const updatedEntries = entries.filter(entry => entry.number !== journalNumber);
        localStorage.setItem('journalEntries', JSON.stringify(updatedEntries));
        
        // Reload entries
        loadJournalEntries();
        
        // Show success message
        const message = document.createElement('div');
        message.className = 'success-message';
        message.textContent = 'تم حذف القيد بنجاح';
        document.body.appendChild(message);
        
        // Remove message after 3 seconds
        setTimeout(() => message.remove(), 3000);
    }
};

window.editJournal = function(encodedData) {
    const journalData = JSON.parse(decodeURIComponent(encodedData));
    showJournalEntryForm(journalData); // Pass the data to pre-fill the form
};

// Add new function for journal entry form
function showJournalEntryForm(journalData = {}) {
    const journalWindow = document.createElement('div');
    journalWindow.className = 'modal journal-entry-modal';
    journalWindow.innerHTML = `
        <div class="modal-content">
            <div class="journal-entry-form">
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
                    <button class="cancel-btn" onclick="closeModal()">إلغاء</button>
                </div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    
    document.body.appendChild(journalWindow);
    setupJournalEntryForm(journalWindow, journalData);
    setupModalClose(journalWindow);
}

function setupJournalEntryForm(form, journalData = {}) {
    if (journalData.number) {
        form.querySelector('#journalNumber').value = journalData.number;
    }
    if (journalData.date) {
        form.querySelector('#journalDate').value = journalData.date;
    }
    if (journalData.description) {
        form.querySelector('#journalDescription').value = journalData.description;
    }
    if (journalData.entries) {
        const tbody = form.querySelector('#entriesTable');
        tbody.innerHTML = '';
        journalData.entries.forEach(entry => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="text" class="account-number" value="${entry.accountNumber}" list="accountNumbersList">
                    <datalist id="accountNumbersList">
                        <option value="1101" label="الصندوق">
                        <option value="1102" label="البنك">
                        <option value="4001" label="المبيعات">
                        <option value="3001" label="المشتريات">
                    </datalist>
                </td>
                <td><input type="number" class="debit-amount" value="${entry.debit}" step="0.01"></td>
                <td><input type="number" class="credit-amount" value="${entry.credit}" step="0.01"></td>
                <td>
                    <select class="cost-center">
                        <option value="">اختر المركز</option>
                        <option value="1">فرع 1</option>
                        <option value="2">فرع 2</option>
                    </select>
                </td>
                <td><input type="text" class="entry-description" value="${entry.description}" placeholder="وصف تفصيلي للقيد"></td>
                <td>
                    <button class="delete-row" onclick="deleteRow(this)">×</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    
    // Add event listeners for calculations
    form.querySelectorAll('.debit-amount, .credit-amount').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });
    
    // Add event listener for description field enter key
    form.querySelectorAll('.entry-description').forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                addNewRow(); // Add new row when enter is pressed
            }
        });
    });

    function calculateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;
        
        form.querySelectorAll('.debit-amount').forEach(input => {
            totalDebit += parseFloat(input.value || 0);
        });
        
        form.querySelectorAll('.credit-amount').forEach(input => {
            totalCredit += parseFloat(input.value || 0);
        });
        
        form.querySelector('#totalDebit').textContent = totalDebit.toFixed(2);
        form.querySelector('#totalCredit').textContent = totalCredit.toFixed(2);
        form.querySelector('#difference').textContent = Math.abs(totalDebit - totalCredit).toFixed(2);
    }
}

// Update addNewRow function to add enter key listener to new row
window.addNewRow = function() {
    const tbody = document.querySelector('#entriesTable');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
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
    `;
    tbody.appendChild(newRow);
    
    // Add enter key listener to the new description field
    const descField = newRow.querySelector('.entry-description');
    descField.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            addNewRow();
        }
    });
    
    setupRowEventListeners(newRow);
};

function setupRowEventListeners(row) {
    const accountInput = row.querySelector('.account-number');
    const amountInputs = row.querySelectorAll('.debit-amount, .credit-amount');
    amountInputs.forEach(input => {
        input.addEventListener('input', () => {
            const form = document.querySelector('.journal-entry-modal');
            setupJournalEntryForm(form);
        });
    });
}

window.deleteRow = function(button) {
    const row = button.closest('tr');
    if (document.querySelectorAll('#entriesTable tr').length > 1) {
        row.remove();
        const form = document.querySelector('.journal-entry-modal');
        setupJournalEntryForm(form);
    }
};

window.saveJournalEntry = function() {
    const journalData = {
        number: document.querySelector('#journalNumber').value,
        date: document.querySelector('#journalDate').value,
        description: document.querySelector('#journalDescription').value,
        entries: [],
        totalDebit: 0,
        totalCredit: 0
    };

    // Collect entries data
    const rows = document.querySelectorAll('#entriesTable tr');
    rows.forEach(row => {
        const entry = {
            accountNumber: row.querySelector('.account-number').value,
            debit: parseFloat(row.querySelector('.debit-amount').value) || 0,
            credit: parseFloat(row.querySelector('.credit-amount').value) || 0,
            costCenter: row.querySelector('.cost-center').value,
            description: row.querySelector('.entry-description').value
        };
        journalData.entries.push(entry);
        journalData.totalDebit += entry.debit;
        journalData.totalCredit += entry.credit;
    });

    // Retrieve existing entries and add new entry
    const entries = JSON.parse(localStorage.getItem('journalEntries') || '[]');
    entries.push(journalData);
    localStorage.setItem('journalEntries', JSON.stringify(entries));

    // Show success message
    const message = document.createElement('div');
    message.className = 'success-message';
    message.textContent = 'تم حفظ القيد بنجاح';
    document.body.appendChild(message);
    
    // Remove message after 3 seconds
    setTimeout(() => message.remove(), 3000);

    // Close the modal and reload entries
    closeModal();
    showJournalViewModule();
};

window.closeModal = function() {
    const modal = document.querySelector('.journal-entry-modal');
    if (modal) modal.remove();
};

function setupModalClose(modal) {
    const closeButton = modal.querySelector('.close-modal');
    closeButton.addEventListener('click', () => {
        modal.remove();
    });
}

function showJournalActions(journalData) {
    // Journal actions logic
}