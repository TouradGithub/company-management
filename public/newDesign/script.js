// document.addEventListener('DOMContentLoaded', () => {
//     const menuItems = document.querySelectorAll('.menu-item');
//     const employeesView = document.getElementById('employees-view');
//     const advancesView = document.getElementById('advances-view');
//     const overtimeView = document.getElementById('overtime-view');
//     const deductionsView = document.getElementById('deductions-view');
//     const payrollView = document.getElementById('payroll-view');
//     const leavesView = document.getElementById('leaves-view');
//     const journalEntryView = document.getElementById('journal-entry-view');
//     const viewJournalView = document.getElementById('view-journal-view');
//     const accountStatementView = document.getElementById('account-statement-view');
//     const addAdvanceView = document.getElementById('add-advance-view');

//     // View management
//     function showView(viewId) {
//       // Hide all views
//       [
//         employeesView, advancesView, overtimeView, deductionsView,
//         payrollView, leavesView, journalEntryView, viewJournalView,
//         accountStatementView, addAdvanceView
//       ].forEach(view => {
//         if (view) view.style.display = 'none';
//       });

//       // Show selected view
//       const viewToShow = document.getElementById(viewId);
//       if (viewToShow) viewToShow.style.display = 'block';
//     }

//     // Menu item click handlers
//     menuItems.forEach(item => {
//       item.addEventListener('click', function(e) {
//         e.preventDefault();
//         menuItems.forEach(i => i.classList.remove('active'));
//         this.classList.add('active');

//         const link = this.querySelector('a span').textContent;
//         switch(link) {
//           case 'الموظفين':
//             showView('employees-view');
//             break;
//           case 'السلف':
//             showView('advances-view');
//             break;
//           case 'الاضافي':
//             showView('overtime-view');
//             break;
//           case 'الخصومات':
//             showView('deductions-view');
//             break;
//           case 'كشوفات الرواتب':
//             showView('payroll-view');
//             break;
//           case 'الاجازات':
//             showView('leaves-view');
//             break;
//         }
//       });
//     });

//     // Mobile menu functionality
//     const sidebar = document.querySelector('.sidebar');
//     const menuTrigger = document.createElement('div');
//     menuTrigger.className = 'mobile-menu-trigger';
//     menuTrigger.innerHTML = '<i class="fas fa-bars"></i>';
//     document.body.appendChild(menuTrigger);

//     menuTrigger.addEventListener('click', () => {
//       sidebar.style.transform = sidebar.style.transform === 'translateX(0px)' ?
//         'translateX(100%)' : 'translateX(0)';
//     });

//     // Search functionality



//     // Submenu functionality
//     const submenuItems = document.querySelectorAll('.has-submenu');
//     submenuItems.forEach(item => {
//       item.addEventListener('click', function(e) {
//         e.preventDefault();
//         e.stopPropagation();
//         this.classList.toggle('active');

//         submenuItems.forEach(other => {
//           if (other !== this && other.classList.contains('active')) {
//             other.classList.remove('active');
//           }
//         });
//       });
//     });

//     // Submenu item click handlers
//     document.querySelectorAll('.submenu a').forEach(link => {
//       link.addEventListener('click', (e) => {
//         e.preventDefault();
//         e.stopPropagation();

//         const linkText = link.querySelector('span').textContent;
//         switch(linkText) {
//           case 'قيد يومية':
//             showView('journal-entry-view');
//             break;
//           case 'عرض قيود اليومية':
//             showView('view-journal-view');
//             break;
//           case 'كشف حساب':
//             showView('account-statement-view');
//             break;
//         }
//       });
//     });

//     // Add advance functionality
//     const addAdvanceBtn = document.querySelector('.add-advance-btn');
//     const backToAdvancesBtn = document.querySelector('.back-to-advances-btn');


//     addAdvanceBtn?.addEventListener('click', () => {
//       showView('add-advance-view');
//     });

//     backToAdvancesBtn?.addEventListener('click', () => {
//       showView('advances-view');
//     });



//     // Payment method change handler
//     const paymentMethodSelect = document.querySelector('select[name="payment-method"]');
//     const installmentsInput = document.querySelector('input[name="installments"]');

//     if (paymentMethodSelect && installmentsInput) {
//       paymentMethodSelect.addEventListener('change', (e) => {
//         installmentsInput.parentElement.style.display =
//           e.target.value === 'monthly' ? 'block' : 'none';
//       });

//       // Initially hide installments input
//       installmentsInput.parentElement.style.display = 'none';
//     }

//     // Cancel button functionality
//     const cancelBtn = document.querySelector('.cancel-btn');
//     cancelBtn?.addEventListener('click', () => {
//       showView('advances-view');
//     });
//   });
