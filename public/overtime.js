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


    // $('#employees').select2({
    //   placeholder: 'اختر الموظفين',
    //   dir: 'rtl',
    //   language: 'ar'
    // });


    // Sample employees data
    var employees = [];

    // Load saved entries from localStorage
    let savedEntries = JSON.parse(localStorage.getItem('overtimeEntries') || '[]');

    // Handle branch selection change
    $('#branches').on('change', function() {
      const selectedBranches = $(this).val();
      console.log("OK------------");
      // Filter employees based on selected branches
      $.ajax({
          url: '/get-employees-by-branch',
          method: 'GET',
          data: { branches: [selectedBranches] },
          success: function(response) {
            const employeesSelect = $('#employees').get(0);

            // Clear the current options
            employeesSelect.innerHTML = '';
             employees = response;

            // Add new options for filtered employees
            response.forEach(emp => {
                const option = document.createElement('option');
                option.value = emp.id;
                option.textContent = emp.name;
                console.log(emp.basic_salary);
                option.setAttribute('data-salary', emp.basic_salary);

                employeesSelect.appendChild(option);
                console.log(emp.name, emp.salary);
            });

            // Trigger Select2 update to refresh the dropdown
            $('#employees').trigger('change');
          },
          error: function(xhr, status, error) {
            console.error("There was an error fetching the employees: ", error);
          }
        });

      // Trigger Select2 update
      $('#employees').trigger('change');
    });


    document.getElementById('fixedAmount').addEventListener('input', calculateTotal);

    // Handle changes in the hours and multiplier inputs
    document.getElementById('hours').addEventListener('input', calculateTotal);
    document.querySelectorAll('input[name="hourMultiplier"]').forEach(radio => {
      radio.addEventListener('change', calculateTotal);
    });

    // Handle changes in the daily rate and days inputs
    document.getElementById('days').addEventListener('input', calculateTotal);
    document.getElementById('dailyRate').addEventListener('input', calculateTotal);

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
            console.log(document.getElementById('fixedAmount').value);
            break;
          case 'hours':
            const hours = parseFloat(document.getElementById('hours').value) || 0;
            const multiplier = parseFloat(document.querySelector('input[name="hourMultiplier"]:checked').value);
            const hourlyRate = (selectedEmployee.basic_salary / 30) / 8;
            amount = hours * hourlyRate * multiplier;
            console.log(hours);
            console.log(multiplier);
            break;
          case 'daily':
            const days = parseFloat(document.getElementById('days').value) || 0;
            const dailyRate = parseFloat(document.getElementById('dailyRate').value) || 0;
            amount = days * dailyRate;
            console.log(dailyRate);
            console.log(days);
            break;
        }

        totalAmount.textContent = amount.toLocaleString('ar-SA');
        totalAmountHidden.value = amount;
      }





  //   form.addEventListener('submit', (e) => {
  //     e.preventDefault();
  //     const selectedEmployeeIds = $('#employees').val();




  });


//   document.addEventListener('DOMContentLoaded', () => {
//     // Other code...

//     // Handle changes in the fixed amount input
//     document.getElementById('fixedAmount').addEventListener('input', calculateTotal);

//     // Handle changes in the hours and multiplier inputs
//     document.getElementById('hours').addEventListener('input', calculateTotal);
//     document.querySelectorAll('input[name="hourMultiplier"]').forEach(radio => {
//       radio.addEventListener('change', calculateTotal);
//     });

//     // Handle changes in the daily rate and days inputs
//     document.getElementById('days').addEventListener('input', calculateTotal);
//     document.getElementById('dailyRate').addEventListener('input', calculateTotal);

//     // Calculate total amount
//     function calculateTotal() {
//       const selectedEmployeeIds = $('#employees').val();
//       if (!selectedEmployeeIds || selectedEmployeeIds.length === 0) {
//         totalAmount.textContent = '0';
//         totalAmountHidden.value = 0;
//         return;
//       }

//       const selectedEmployee = employees.find(emp => emp.id === parseInt(selectedEmployeeIds[0]));
//       if (!selectedEmployee) return;

//       let amount = 0;
//       const overtimeType = document.querySelector('input[name="overtimeType"]:checked')?.value;

//       switch (overtimeType) {
//         case 'fixed':
//           amount = parseFloat(document.getElementById('fixedAmount').value) || 0;
//           break;

//         case 'hours':
//           const hours = parseFloat(document.getElementById('hours').value) || 0;
//           const multiplier = parseFloat(document.querySelector('input[name="hourMultiplier"]:checked')?.value) || 1;
//           const hourlyRate = (selectedEmployee.basic_salary / 30) / 8;
//           amount = hours * hourlyRate * multiplier;
//           break;

//         case 'daily':
//           const days = parseFloat(document.getElementById('days').value) || 0;
//           const dailyRate = parseFloat(document.getElementById('dailyRate').value) || 0;
//           amount = days * dailyRate;
//           break;
//       }

//       totalAmount.textContent = amount.toLocaleString('ar-SA', { minimumFractionDigits: 2 });
//       totalAmountHidden.value = amount;
//     }

//     // Trigger initial calculation on page load (if any values are pre-filled)
//     calculateTotal();
//   });

