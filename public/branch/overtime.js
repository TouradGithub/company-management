document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('overtimeForm');
    const employeesSelect = document.getElementById('employees');
    const employeeDetails = document.querySelector('.employee-details');
    const fixedAmountSection = document.getElementById('fixedAmountSection');
    const hoursSection = document.getElementById('hoursSection');
    const dailyRateSection = document.getElementById('dailyRateSection');
    const totalAmount = document.getElementById('totalAmount');
    const totalAmountHidden = document.getElementById('totalAmountHidden');
    const entriesContainer = document.getElementById('entriesContainer');
    const resetBtn = document.getElementById('resetBtn');

    // var employees = @json($employees);
    $('#employees').select2({
      placeholder: 'اختر الموظفين',
      dir: 'rtl',
      language: 'ar'
    });

    // Set today's date as default
    document.getElementById('date').valueAsDate = new Date();

    const employeesDataElement = document.getElementById('employeesData');
    var employees = JSON.parse(employeesDataElement.getAttribute('data-employees'));
    // Sample employees data



    // Load saved entries from localStorage
    let savedEntries = JSON.parse(localStorage.getItem('overtimeEntries') || '[]');

    // Handle employee selection change
    $('#employees').on('change', function() {
      const selectedIds = $(this).val();
      console.log("OK changed");
      if (selectedIds && selectedIds.length > 0) {
        const selectedEmployee = employees.find(emp => emp.id === parseInt(selectedIds[0]));
        if (selectedEmployee) {
          document.getElementById('iqamaNumber').textContent = selectedEmployee.iqamaNumber;
          document.getElementById('basicSalary').textContent = selectedEmployee.basic_salary+ ' ريال';
          document.getElementById('hourlyRate').textContent =((selectedEmployee.basic_salary / 30) / 8).toFixed(2) + ' ريال';
          employeeDetails.classList.remove('hidden');
        }
      } else {
        employeeDetails.classList.add('hidden');
      }
    });

    // Toggle between overtime types
    document.querySelectorAll('input[name="overtimeType"]').forEach(radio => {
      radio.addEventListener('change', (e) => {
        // Hide all sections first
        fixedAmountSection.classList.add('hidden');
        hoursSection.classList.add('hidden');
        dailyRateSection.classList.add('hidden');

        // Show the selected section
        switch(e.target.value) {
          case 'fixed':
            fixedAmountSection.classList.remove('hidden');
            break;
          case 'hours':
            hoursSection.classList.remove('hidden');
            break;
          case 'daily':
            dailyRateSection.classList.remove('hidden');
            break;
        }
        calculateTotal();
      });
    });

    // Calculate total amount
    function calculateTotal() {
      const selectedEmployeeIds = $('#employees').val();
      let amount = 0;
      const selectedEmployee = employees.find(emp => emp.id === parseInt(selectedEmployeeIds[0]));
      if (!selectedEmployee) return;

      const overtimeType = document.querySelector('input[name="overtimeType"]:checked').value;

      switch(overtimeType) {
        case 'fixed':
          amount = parseFloat(document.getElementById('fixedAmount').value) || 0;
          break;
        case 'hours':
          const hours = parseFloat(document.getElementById('hours').value) || 0;
          const multiplier = parseFloat(document.querySelector('input[name="hourMultiplier"]:checked').value);
          const hourlyRate = (selectedEmployee.basic_salary / 30) / 8;
          amount = hours * hourlyRate * multiplier;
          break;
        case 'daily':
          const days = parseFloat(document.getElementById('days').value) || 0;
          const dailyRate = parseFloat(document.getElementById('dailyRate').value) || 0;
          amount = days * dailyRate;
          break;
      }

      totalAmount.textContent = amount.toLocaleString('ar-SA');
      totalAmountHidden.value = amount;
    }

    // Add event listeners for input changes
    document.getElementById('fixedAmount').addEventListener('input', calculateTotal);
    document.getElementById('hours').addEventListener('input', calculateTotal);
    document.getElementById('days').addEventListener('input', calculateTotal);
    document.getElementById('dailyRate').addEventListener('input', calculateTotal);

    // Add event listener for multiplier change
    document.querySelectorAll('input[name="hourMultiplier"]').forEach(radio => {
      radio.addEventListener('change', calculateTotal);
    });

    // function renderEntries() {
    //   entriesContainer.innerHTML = savedEntries.map(entry => {
    //     // Safely format the date and amount with fallbacks
    //     const formattedDate = entry.date ? new Date(entry.date).toLocaleDateString('ar-SA') : '-';
    //     const formattedAmount = entry.amount ? entry.amount.toLocaleString('ar-SA') : '0';

    //     return `
    //       <div class="entry-card">
    //         <div class="entry-info">
    //           <div class="entry-date">${formattedDate}</div>
    //           <div>${entry.employeeName || '-'}</div>
    //           <div class="entry-total">${formattedAmount} ريال</div>
    //         </div>
    //         <div class="entry-actions">
    //           <button class="btn-primary btn-edit" onclick="editEntry('${entry.id}')">تعديل</button>
    //           <button class="btn-primary btn-delete" onclick="deleteEntry('${entry.id}')">حذف</button>
    //         </div>
    //       </div>
    //     `;
    //   }).join('');
    // }

    window.editEntry = function(entryId) {
      const entry = savedEntries.find(e => e.id === entryId);
      if (!entry) return;

      try {
        document.getElementById('date').value = entry.date || '';
        $('#branches').val(entry.branches || []).trigger('change');
        $('#employees').val([entry.employeeId]).trigger('change');

        const overtimeTypeRadio = document.querySelector(`input[name="overtimeType"][value="${entry.overtimeType}"]`);
        if (overtimeTypeRadio) {
          overtimeTypeRadio.checked = true;
          overtimeTypeRadio.dispatchEvent(new Event('change'));
        }

        // Hide all sections first
        fixedAmountSection.classList.add('hidden');
        hoursSection.classList.add('hidden');
        dailyRateSection.classList.add('hidden');

        // Show and populate the relevant section
        switch(entry.overtimeType) {
          case 'fixed':
            fixedAmountSection.classList.remove('hidden');
            document.getElementById('fixedAmount').value = entry.fixedAmount || '';
            break;
          case 'hours':
            hoursSection.classList.remove('hidden');
            document.getElementById('hours').value = entry.hours || '';
            const multiplierRadio = document.querySelector(`input[name="hourMultiplier"][value="${entry.hourMultiplier}"]`);
            if (multiplierRadio) multiplierRadio.checked = true;
            break;
          case 'daily':
            dailyRateSection.classList.remove('hidden');
            document.getElementById('days').value = entry.days || '';
            document.getElementById('dailyRate').value = entry.dailyRate || '';
            break;
        }

        calculateTotal();
        deleteEntry(entryId);
      } catch (error) {
        console.error('Error editing entry:', error);
      }
    };

    window.deleteEntry = function(entryId) {
      if (!entryId) return;
      savedEntries = savedEntries.filter(e => e.id !== entryId);
      localStorage.setItem('overtimeEntries', JSON.stringify(savedEntries));
      renderEntries();
    };

  //   form.addEventListener('submit', (e) => {
  //     e.preventDefault();
  //     const selectedEmployeeIds = $('#employees').val();

  //     if (!selectedEmployeeIds || selectedEmployeeIds.length === 0) return;

  //     try {
  //       selectedEmployeeIds.forEach(empId => {
  //         const selectedEmployee = employees.find(emp => emp.id === parseInt(empId));
  //         if (!selectedEmployee) return;

  //         const entry = {
  //           id: Date.now() + Math.random().toString(36).substr(2, 9),
  //           date: document.getElementById('date').value,
  //           branches: $('#branches').val() || [],
  //           employeeId: selectedEmployee.id,
  //           employeeName: selectedEmployee.name,
  //           overtimeType: document.querySelector('input[name="overtimeType"]:checked')?.value || 'fixed',
  //           amount: parseFloat(totalAmount.textContent.replace(/[^\d.-]/g, '')) || 0,
  //           fixedAmount: document.getElementById('fixedAmount').value || '',
  //           hours: document.getElementById('hours').value || '',
  //           hourMultiplier: document.querySelector('input[name="hourMultiplier"]:checked')?.value || '1',
  //           days: document.getElementById('days').value || '',
  //           dailyRate: document.getElementById('dailyRate').value || ''
  //         };

  //         savedEntries.push(entry);
  //       });

  //       localStorage.setItem('overtimeEntries', JSON.stringify(savedEntries));
  //       renderEntries();
  //       form.reset();
  //       $('#branches').val(null).trigger('change');
  //       $('#employees').val(null).trigger('change');
  //       document.getElementById('date').valueAsDate = new Date();
  //       employeeDetails.classList.add('hidden');
  //       calculateTotal();
  //     } catch (error) {
  //       console.error('Error saving entry:', error);
  //     }
  //   });

    // resetBtn.addEventListener('click', () => {
    //   form.reset();
    //   document.getElementById('date').valueAsDate = new Date();
    //   employeeDetails.classList.add('hidden');
    //   calculateTotal();
    // });

    // Initial render
    renderEntries();
  });
