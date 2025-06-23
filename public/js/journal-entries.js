
let allPropertiest = [];

async function groupTransactionsByDay() {
    try {
        const response = await fetch('/api/journal-entries');
        const data = await response.json();
        return data.map(entry => {
            return {
                id: entry.id,
                date: entry.date,
                propertyId: entry.property_id,
                description: entry.description,
                amount: entry.lines.reduce((total, line) => total + (parseFloat(line.debit) || 0), 0),
                isRentAccrual: entry.type === 'استحقاق إيجار',
                tenantName: entry.property?.tenant_name ?? '',
                propertyName: entry.property?.property_name ?? ''
            };
        });
    } catch (error) {
        console.error("فشل في جلب القيود:", error);
        return [];
    }
}
function populatePropertyFilter(allPropertiest) {
       // أول تحميل
    //    fetchProperties2();
        const propertyFilter = document.getElementById('property-filter');
        if (!propertyFilter) return;

        // إضافة خيار "جميع العقارات"
        propertyFilter.innerHTML = '<option value="all">جميع العقارات</option>';
        console.log(allPropertiest)
        // إضافة كل العقارات الموجودة
        allPropertiest.forEach(property => {
            const option = document.createElement('option');
            option.value = property.id;
            option.textContent = property.property_name;
            propertyFilter.appendChild(option);
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
    // تهيئة وحدة قيود اليومية
    initJournalEntriesModule();
});
async function displayJournalEntries(searchTerm = '', propertyFilter = 'all', entryTypeFilter = 'all') {
    const container = document.getElementById('journal-entries-container');
    if (!container) return;

    container.innerHTML = '';
    const groupedEntries = await groupTransactionsByDay();
    let filteredEntries = groupedEntries;
    // console.log(groupedEntries)


    // فلترة حسب العقار
    if (propertyFilter !== 'all') {
        const propertyId = parseInt(propertyFilter);
        filteredEntries = filteredEntries.filter(entry => entry.propertyId === propertyId);
    }

    // فلترة حسب نوع القيد
    if (entryTypeFilter !== 'all') {
        filteredEntries = filteredEntries.filter(entry => {
            if (entryTypeFilter === 'rent' && entry.isRentAccrual) return true;
            if (entryTypeFilter === 'payment' && !entry.isRentAccrual) return true;
            return false;
        });
    }

    // فلترة حسب مصطلح البحث
    if (searchTerm) {
        filteredEntries = filteredEntries.filter(entry => {
            const property = allProperties.find(p => p.id === entry.propertyId);
            if (!property) return false;

            return property.property_name.toLowerCase().includes(searchTerm) ||
                entry.description.toLowerCase().includes(searchTerm) ||
                property.tenant_name.toLowerCase().includes(searchTerm);
        });
    }

    // عرض كل القيود بعد التصفية
    if (filteredEntries.length === 0) {
        container.innerHTML = '<div class="no-data">لا توجد قيود مطابقة للبحث</div>';
        return;
    }
    // ترتيب القيود بتاريخ تنازلي (الأحدث أولاً)
    filteredEntries.sort((a, b) => new Date(b.date) - new Date(a.date));

    // عرض كل القيود
    filteredEntries.forEach(entry => {
        const property = allProperties.find(p => p.id === entry.propertyId);
        if (!property) return;

        const card = document.createElement('div');
        card.className = `journal-entry-card entry-type-${entry.isRentAccrual ? 'rent' : 'payment'}`;

        let tableRows = '';

        if (entry.isRentAccrual) {
            // قيد استحقاق الإيجار
            tableRows = `
                <tr>
                    <td>مدين / ذمم مستأجرين</td>
                    <td class="text-left">${formatCurrency(entry.amount)}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>دائن / إيرادات إيجارات</td>
                    <td></td>
                    <td class="text-left">${formatCurrency(entry.amount)}</td>
                </tr>
            `;
        } else {
            // قيد دفعة إيجار
            tableRows = `
                <tr>
                    <td>مدين / النقد</td>
                    <td class="text-left">${formatCurrency(entry.amount)}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>دائن / ذمم مستأجرين</td>
                    <td></td>
                    <td class="text-left">${formatCurrency(entry.amount)}</td>
                </tr>
            `;
        }

        card.innerHTML = `
            <div class="journal-entry-header">
                <div class="journal-entry-property">${property.property_name}</div>
                <div class="journal-entry-date">${formatDate(entry.date)}</div>
            </div>
            <div class="journal-entry-description">${entry.description}</div>
            <table class="journal-entries-table">
                <thead>
                    <tr>
                        <th>البيان</th>
                        <th>مدين</th>
                        <th>دائن</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
            <div class="journal-entry-reference">
                المستأجر: ${property.tenant_name}
            </div>
        `;

        container.appendChild(card);
    });
}


function initJournalEntriesModule() {
    // إضافة مستمع على حدث البحث
    const searchInput = document.getElementById('journal-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterJournalEntries();
        });
    }
    fetch(`/properties/show`)
        .then(response => response.json())
        .then(data => {
            allPropertiest = data.properties;
            populatePropertyFilter(allPropertiest);
        })
        .catch(error => {
            console.error('Error fetching properties:', error);
            showToast('حدث خطأ أثناء جلب العقارات', '#d00000');
        });
    // تهيئة قائمة العقارات في فلتر البحث
    // populatePropertyFilter();
}
function filterJournalEntries() {
    const searchTerm = document.getElementById('journal-search').value.trim().toLowerCase();
    const propertyFilter = document.getElementById('property-filter').value;
    const entryTypeFilter = document.getElementById('entry-type-filter').value;

    displayJournalEntries(searchTerm, propertyFilter, entryTypeFilter);
}
