document.addEventListener('DOMContentLoaded', function() {
    // Function to hide all sections
    function hideAllSections() {
      // const sections = [
      //   'mainDashboard',
      //   'additionsSection',
      //   'settingsSection',
      //   'trialBalanceSection',
      //   'incomeStatementSection',
      //   'balanceSheetSection',
      //   'categoriesSection',
      //   'productsSection',
      //   'salesInvoiceSection',
      //   'accountsTreeSection'
      // ];
      //
      // sections.forEach(sectionId => {
      //   const section = document.getElementById(sectionId);
      //   if (section) {
      //     section.style.display = 'none';
      //   }
      // });
    }

    // Add logo click handler
    const logoLink = document.querySelector('.logo-link');
    logoLink.addEventListener('click', function(e) {
        e.preventDefault();

        hideAllSections();

        document.getElementById('mainDashboard').style.display = 'block';

        // Remove active class from all menu items
        document.querySelectorAll('nav a').forEach(link => {
            link.classList.remove('active');
        });
    });

    // Add digital clock functionality
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.querySelector('.digital-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initial call to avoid delay

    // Toggle submenu functionality
    const menuGroups = document.querySelectorAll('.menu-group > a');

    menuGroups.forEach(group => {
        group.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;

            // Close other open menus
            document.querySelectorAll('.menu-group').forEach(item => {
                if (item !== parent && item.classList.contains('active')) {
                    item.classList.remove('active');
                }
            });

            // Toggle current menu
            parent.classList.toggle('active');
        });
    });

    // Active state for menu items
    const menuItems = document.querySelectorAll('.submenu a');

    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Remove active class from all items
            menuItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
        });
    });

    // Notification functionality
    const notificationBtn = document.querySelector('.user-info .fa-bell');
    notificationBtn.addEventListener('click', function() {
        alert('لا توجد إشعارات جديدة');
    });

    // Search functionality
    // const searchInput = document.querySelector('.search-bar input');
    // searchInput.addEventListener('keypress', function(e) {
    //     if (e.key === 'Enter') {
    //         alert('جاري البحث عن: ' + this.value);
    //     }
    // });
    //
    // // Add hover effects to cards
    // const cards = document.querySelectorAll('.card');
    // cards.forEach(card => {
    //     card.addEventListener('mouseenter', function() {
    //         this.style.transform = 'translateY(-5px)';
    //         this.style.transition = 'all 0.3s ease';
    //     });
    //
    //     card.addEventListener('mouseleave', function() {
    //         this.style.transform = 'translateY(0)';
    //     });
    // });

    // Add WhatsApp button hover effect
    const whatsappButton = document.querySelector('.whatsapp-button');

    whatsappButton.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1)';
    });

    whatsappButton.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });

    // Replace modal functionality with direct content switching
    // const additionsBtn = document.getElementById('additionsBtn');
    // const mainDashboard = document.getElementById('mainDashboard');
    // const additionsSection = document.getElementById('additionsSection');
    //
    // additionsBtn.addEventListener('click', function(e) {
    //     e.preventDefault();
    //
    //     hideAllSections();
    //
    //     document.getElementById('additionsSection').style.display = 'block';
    //
    //     // Update active state in menu
    //     document.querySelectorAll('nav a').forEach(link => {
    //         link.classList.remove('active');
    //     });
    //     this.classList.add('active');
    // });

    // Action buttons functionality
    // document.querySelectorAll('.action-btn').forEach(button => {
    //     button.addEventListener('click', function(e) {
    //         e.stopPropagation(); // Prevent event bubbling
    //         const category = this.closest('.addition-card')?.querySelector('h3')?.textContent;
    //         if (!category) return;
    //
    //         if (this.classList.contains('add')) {
    //             // Add functionality
    //             const additionModal = document.createElement('div');
    //             additionModal.className = 'addition-form-modal';
    //
    //             // Customize form based on category
    //             let accountNameField = '';
    //             if (['العملاء', 'الموردين', 'الصناديق', 'البنوك', 'الفروع', 'المخازن'].includes(category)) {
    //                 accountNameField = `
    //                     <div class="form-group">
    //                         <label>اسم الحساب</label>
    //                         <select required>
    //                             ${getAccountOptions(category)}
    //                         </select>
    //                     </div>
    //                 `;
    //             }
    //
    //             additionModal.innerHTML = `
    //                 <h2 class="addition-form-title">إضافة ${category}</h2>
    //                 <form class="addition-form">
    //                     <div class="form-group">
    //                         <label>الاسم</label>
    //                         <input type="text" required>
    //                     </div>
    //                     ${accountNameField}
    //                     <div class="form-group">
    //                         <label>الرقم</label>
    //                         <input type="number" step="0.01" required>
    //                     </div>
    //                     <div class="modal-buttons">
    //                         <button type="button" class="cancel-btn">إلغاء</button>
    //                         <button type="submit" class="save-btn">حفظ</button>
    //                     </div>
    //                 </form>
    //             `;
    //
    //             document.body.appendChild(additionModal);
    //             modalOverlay.classList.add('active');
    //             additionModal.classList.add('active');
    //
    //             // Handle form submission
    //             additionModal.querySelector('form').addEventListener('submit', function(e) {
    //                 e.preventDefault();
    //                 closeModals();
    //             });
    //
    //             // Handle cancel button
    //             additionModal.querySelector('.cancel-btn').addEventListener('click', closeModals);
    //
    //             function closeModals() {
    //                 modalOverlay.classList.remove('active');
    //                 additionModal.classList.remove('active');
    //                 setTimeout(() => {
    //                     additionModal.remove();
    //                 }, 300);
    //             }
    //         } else if (this.classList.contains('view')) {
    //             showReport(category);
    //         }
    //     });
    // });

    // Add hover effects to addition cards
    // const additionCards = document.querySelectorAll('.addition-card');
    // additionCards.forEach(card => {
    //     card.addEventListener('mouseenter', function() {
    //         this.style.transform = 'translateY(-5px)';
    //         this.style.transition = 'all 0.3s ease';
    //     });
    //
    //     card.addEventListener('mouseleave', function() {
    //         this.style.transform = 'translateY(0)';
    //     });
    // });

    document.querySelectorAll('.action-btn.view').forEach(button => {
        button.addEventListener('click', function() {
            const category = this.closest('.addition-card').querySelector('h3').textContent;
            showReport(category);
        });
    });

    // Add click handler for addition cards
    // document.querySelectorAll('.addition-card').forEach(card => {
    //     card.addEventListener('click', function(e) {
    //         // Don't trigger if clicking action buttons
    //         if (e.target.closest('.card-actions')) return;
    //
    //         const category = this.querySelector('h3').textContent;
    //       //  showAccountsModal(category);
    //     });
    // });

    function showAccountsModal(category) {
        // Hide the additions grid
        document.querySelector('.additions-grid').style.display = 'none';

        // Sample data - in a real application, this would come from a database
        const accounts = [
            { name: 'حساب 1', balance: '150,000', type: 'مدين' },
            { name: 'حساب 2', balance: '75,000', type: 'دائن' },
            { name: 'حساب 3', balance: '225,000', type: 'مدين' },
            { name: 'حساب 4', balance: '95,000', type: 'دائن' },
        ];

        const totalBalance = accounts.reduce((sum, account) => {
            return sum + parseInt(account.balance.replace(/,/g, ''));
        }, 0).toLocaleString();

        let icon;
        switch(category) {
            case 'مراكز التكلفة': icon = 'building'; break;
            case 'العملاء': icon = 'users'; break;
            case 'الموردين': icon = 'truck'; break;
            case 'الصناديق': icon = 'cash-register'; break;
            case 'البنوك': icon = 'university'; break;
            case 'الفروع': icon = 'code-branch'; break;
            case 'المخازن': icon = 'warehouse'; break;
            default: icon = 'folder';
        }

        // Create or update accounts modal
        let accountsModal = document.querySelector('#accountsView');
        if (!accountsModal) {
            accountsModal = document.createElement('div');
            accountsModal.id = 'accountsView';
            accountsModal.className = 'accounts-modal';
            document.querySelector('#additionsSection').appendChild(accountsModal);
        }

        accountsModal.innerHTML = `
            <div class="accounts-modal-header">
                <h2 class="accounts-modal-title">
                    <i class="fas fa-${icon}"></i>
                    ${category}
                </h2>
                <div class="total-balance">
                    الرصيد الإجمالي: ${totalBalance} ريال
                </div>
            </div>
            <div class="accounts-grid">
                ${accounts.map(account => `
                    <div class="account-card">
                        <i class="fas fa-${icon} account-icon"></i>
                        <div class="account-name">${account.name}</div>
                        <div class="account-balance">${account.balance} ريال</div>
                        <div class="account-type">النوع: ${account.type}</div>
                        <div class="card-actions">
                            <button class="action-btn view" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                `).join('')}
            </div>
            <div class="modal-buttons">
                <button class="back-btn">رجوع</button>
            </div>
        `;

        accountsModal.classList.add('active');

        // Add back button handler
        accountsModal.querySelector('.back-btn').addEventListener('click', () => {
            accountsModal.classList.remove('active');
            document.querySelector('.additions-grid').style.display = 'grid';
        });

        // Add click handlers for view buttons
        accountsModal.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', () => {
                const accountName = btn.closest('.account-card').querySelector('.account-name').textContent;
                showReport(category, accountName);
            });
        });

        // Add edit button functionality
        accountsModal.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const accountCard = this.closest('.account-card');
                const accountName = accountCard.querySelector('.account-name').textContent;
                const accountBalance = accountCard.querySelector('.account-balance').textContent
                    .replace(' ريال', '');

                showEditAccountModal(accountName, accountBalance);
            });
        });
    }

    // function showEditAccountModal(accountName, accountBalance) {
    //     const editModal = document.createElement('div');
    //     editModal.className = 'addition-form-modal';
    //
    //     editModal.innerHTML = `
    //         <h2 class="addition-form-title">تعديل الحساب</h2>
    //         <form class="addition-form">
    //             <div class="form-group">
    //                 <label>اسم الحساب</label>
    //                 <input type="text" value="${accountName}" required>
    //             </div>
    //             <div class="form-group">
    //                 <label>الرصيد</label>
    //                 <input type="number" step="0.01" value="${parseFloat(accountBalance)}" required>
    //             </div>
    //             <div class="form-group">
    //                 <label>نوع الحساب</label>
    //                 <select required>
    //                     <option value="debit">مدين</option>
    //                     <option value="credit">دائن</option>
    //                 </select>
    //             </div>
    //             <div class="modal-buttons">
    //                 <button type="button" class="cancel-btn">إلغاء</button>
    //                 <button type="submit" class="save-btn">حفظ التعديلات</button>
    //             </div>
    //         </form>
    //     `;
    //
    //     document.body.appendChild(editModal);
    //     modalOverlay.classList.add('active');
    //     editModal.classList.add('active');
    //
    //     // Handle form submission
    //     editModal.querySelector('form').addEventListener('submit', function(e) {
    //         e.preventDefault();
    //         // Here you would typically update the account data in your database
    //         alert('تم حفظ التعديلات بنجاح');
    //         closeModals();
    //     });
    //
    //     // Handle cancel button
    //     editModal.querySelector('.cancel-btn').addEventListener('click', function() {
    //         modalOverlay.classList.remove('active');
    //         editModal.classList.remove('active');
    //         setTimeout(() => {
    //             editModal.remove();
    //         }, 300);
    //     });
    // }

    function closeModals() {
        modalOverlay.classList.remove('active');
        addTaskModal.classList.remove('active');
        tasksListModal.classList.remove('active');
        calculatorModal.classList.remove('active');

        const additionModal = document.querySelector('.addition-form-modal');
        if (additionModal) {
            additionModal.classList.remove('active');
            setTimeout(() => {
                additionModal.remove();
            }, 300);
        }

        // Remove only modals that should be closed, not the accounts view
        const reportModal = document.querySelector('.report-modal');
        if (reportModal) {
            reportModal.classList.remove('active');
            setTimeout(() => {
                reportModal.remove();
            }, 300);
        }
    }

    function showReport(category, accountName = null) {
        const reportModal = document.createElement('div');
        reportModal.className = 'report-modal';

        // Sample data - in a real application, this would come from a database
        const sampleData = [
            { name: 'حساب 1', type: 'مدين', balance: '10,000' },
            { name: 'حساب 2', type: 'دائن', balance: '15,000' },
            { name: 'حساب 3', type: 'عادي', balance: '20,000' }
        ];

        const rows = sampleData.map(account => `
            <tr>
                <td>${account.name}</td>
                <td>${account.type}</td>
                <td>${account.balance} ريال</td>
            </tr>
        `).join('');

        reportModal.innerHTML = `
            <div class="report-content">
                <h2>تقرير ${category}</h2>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>اسم الحساب</th>
                            <th>نوع الحساب</th>
                            <th>الرصيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rows}
                    </tbody>
                </table>
                <div class="modal-buttons">
                    <button class="cancel-btn">إغلاق</button>
                </div>
            </div>
        `;

        document.body.appendChild(reportModal);
        modalOverlay.classList.add('active');
        reportModal.classList.add('active');

        reportModal.querySelector('.cancel-btn').addEventListener('click', () => {
            modalOverlay.classList.remove('active');
            reportModal.classList.remove('active');
            setTimeout(() => reportModal.remove(), 300);
        });
    }

    // Initialize tasks array from localStorage or empty array
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];

    // Task Button and Menu Toggle
    const taskButton = document.querySelector('.task-button');
    const taskMenu = document.querySelector('.task-menu');
    const modalOverlay = document.querySelector('.modal-overlay');
    const addTaskModal = document.querySelector('.task-modal');
    const tasksListModal = document.querySelector('.tasks-list-modal');

    taskButton.addEventListener('click', () => {
        taskMenu.classList.toggle('active');
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!taskButton.contains(e.target) && !taskMenu.contains(e.target)) {
            taskMenu.classList.remove('active');
        }
    });

    // Add Task Button Click
    document.querySelector('#addTaskBtn').addEventListener('click', () => {
        taskMenu.classList.remove('active');
        modalOverlay.classList.add('active');
        addTaskModal.classList.add('active');
    });

    // View Tasks Button Click
    document.querySelector('#viewTasksBtn').addEventListener('click', () => {
        taskMenu.classList.remove('active');
        modalOverlay.classList.add('active');
        tasksListModal.classList.add('active');
        renderTasks();
    });

    // Close modals
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', closeModals);
    });

    // Color picker functionality
    const colorOptions = document.querySelectorAll('.color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', () => {
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
        });
    });

    // Save task
    document.querySelector('#taskForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const taskName = document.querySelector('#taskName').value;
        const priority = document.querySelector('#taskPriority').value;
        const selectedColor = document.querySelector('.color-option.selected').dataset.color;
        const datetime = document.querySelector('#taskDateTime').value;

        const task = {
            id: Date.now(),
            name: taskName,
            priority,
            color: selectedColor,
            datetime,
            completed: false
        };

        tasks.push(task);
        localStorage.setItem('tasks', JSON.stringify(tasks));

        closeModals();
        document.querySelector('#taskForm').reset();
    });

    function renderTasks() {
        const tasksList = document.querySelector('#tasksList');
        tasksList.innerHTML = '';

        tasks.forEach(task => {
            const taskElement = document.createElement('div');
            taskElement.className = 'task-item';
            taskElement.style.backgroundColor = `${task.color}20`;
            taskElement.style.borderLeft = `4px solid ${task.color}`;

            taskElement.innerHTML = `
                <div class="task-info">
                    <input type="checkbox" ${task.completed ? 'checked' : ''}
                        onchange="toggleTaskStatus(${task.id})">
                    <div>
                        <h3 style="text-decoration: ${task.completed ? 'line-through' : 'none'}">${task.name}</h3>
                        <small>${task.datetime}</small>
                    </div>
                </div>
                <div class="task-actions">
                    <button onclick="deleteTask(${task.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            tasksList.appendChild(taskElement);
        });
    }

    window.toggleTaskStatus = function(taskId) {
        const task = tasks.find(t => t.id === taskId);
        if (task) {
            task.completed = !task.completed;
            localStorage.setItem('tasks', JSON.stringify(tasks));
            renderTasks();
        }
    };

    window.deleteTask = function(taskId) {
        tasks = tasks.filter(t => t.id !== taskId);
        localStorage.setItem('tasks', JSON.stringify(tasks));
        renderTasks();
    };

    // Calculator functionality
    const calculatorButton = document.querySelector('.calculator-button');
    const calculatorModal = document.querySelector('.calculator-modal');
    const calculatorScreen = document.querySelector('.calculator-screen');
    let currentInput = '';
    let previousInput = '';
    let operation = null;

    calculatorButton.addEventListener('click', () => {
        modalOverlay.classList.add('active');
        calculatorModal.classList.add('active');
    });

    document.querySelectorAll('.calc-btn').forEach(button => {
        button.addEventListener('click', () => {
            const value = button.dataset.value;

            if (value === 'clear') {
                currentInput = '';
                previousInput = '';
                operation = null;
                calculatorScreen.value = '0';
            } else if (value === '=') {
                if (previousInput && currentInput && operation) {
                    const result = calculate(previousInput, currentInput, operation);
                    calculatorScreen.value = result;
                    currentInput = result.toString();
                    previousInput = '';
                    operation = null;
                }
            } else if (['+', '-', '*', '/'].includes(value)) {
                if (currentInput) {
                    previousInput = currentInput;
                    operation = value;
                    currentInput = '';
                }
            } else {
                currentInput += value;
                calculatorScreen.value = currentInput;
            }
        });
    });

    function calculate(a, b, operation) {
        const num1 = parseFloat(a);
        const num2 = parseFloat(b);
        switch(operation) {
            case '+': return num1 + num2;
            case '-': return num1 - num2;
            case '*': return num1 * num2;
            case '/': return num2 !== 0 ? num1 / num2 : 'Error';
            default: return num2;
        }
    }

    // Quick action cards functionality


    // document.querySelector('.quick-action-card:nth-child(2)').addEventListener('click', showSalesInvoiceForm);
    //
    // document.querySelector('.quick-action-card:nth-child(3)').addEventListener('click', function() {
    //     // Add functionality for payment voucher
    // });

    // document.querySelector('.quick-action-card:nth-child(4)').addEventListener('click', function() {
    //     window.location.href = '#account-statement';
    // });

    // Settings Section Functionality
    // const settingsBtn = document.getElementById('settingsBtn');
    // settingsBtn.addEventListener('click', function(e) {
    //     e.preventDefault();
    //
    //     hideAllSections();
    //
    //     document.getElementById('settingsSection').style.display = 'block';
    //
    //     // Update active state in menu
    //     document.querySelectorAll('nav a').forEach(link => {
    //         link.classList.remove('active');
    //     });
    //     this.classList.add('active');
    // });

    // Company Info Edit
    // document.querySelector('.settings-card .edit').addEventListener('click', function() {
    //     // Here you would implement the company info edit functionality
    //     alert('سيتم فتح نموذج تعديل بيانات الشركة');
    // });
    //
    // // Backup/Restore
    // document.querySelector('.settings-card .backup').addEventListener('click', function() {
    //     alert('جاري إنشاء نسخة احتياطية...');
    // });
    //
    // document.querySelector('.settings-card .restore').addEventListener('click', function() {
    //     alert('الرجاء اختيار ملف النسخة الاحتياطية لاستعادتها');
    // });

    // New Year
    // document.querySelector('.settings-card .new-year').addEventListener('click', function() {
    //     alert('سيتم فتح نموذج إنشاء سنة مالية جديدة');
    // });

    // User Permissions
    // document.querySelector('.settings-card .permissions').addEventListener('click', function() {
    //     const settingsGrid = document.querySelector('.settings-grid');
    //     const usersManagement = initializeUsersManagement();
    //
    //     // Hide settings grid and show users management
    //     settingsGrid.style.display = 'none';
    //     settingsGrid.parentElement.appendChild(usersManagement);
    //
    //     // Add return button functionality
    //     const cancelBtn = usersManagement.querySelector('.cancel-user-btn');
    //     cancelBtn.addEventListener('click', () => {
    //         usersManagement.remove();
    //         settingsGrid.style.display = 'grid';
    //     });
    //
    //     // Add save functionality
    //     const saveBtn = usersManagement.querySelector('.save-user-btn');
    //     saveBtn.addEventListener('click', () => {
    //         // Here you would typically save the user data and permissions
    //         alert('تم حفظ بيانات المستخدم وصلاحياته بنجاح');
    //     });
    //
    //     // Add edit user functionality
    //     const editButtons = usersManagement.querySelectorAll('.user-actions .fa-edit');
    //     editButtons.forEach(btn => {
    //         btn.addEventListener('click', function() {
    //             const userCard = this.closest('.user-card');
    //             const userName = userCard.querySelector('h4').textContent;
    //             const userEmail = userCard.querySelector('p').textContent;
    //
    //             // Populate form with user data
    //             document.getElementById('username').value = userName;
    //             document.getElementById('email').value = userEmail;
    //
    //             // Scroll to form
    //             document.querySelector('.user-form').scrollIntoView({ behavior: 'smooth' });
    //         });
    //     });
    //
    //     // Add delete user functionality
    //     const deleteButtons = usersManagement.querySelectorAll('.user-actions .fa-trash');
    //     deleteButtons.forEach(btn => {
    //         btn.addEventListener('click', function() {
    //             if(confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
    //                 this.closest('.user-card').remove();
    //             }
    //         });
    //     });
    // });

    // function initializeUsersManagement() {
    //     const usersSection = document.createElement('div');
    //     usersSection.className = 'users-grid';
    //
    //     const userPermissionsCard = document.createElement('div');
    //     userPermissionsCard.className = 'user-permissions-card';
    //
    //     userPermissionsCard.innerHTML = `
    //         <h2>إضافة مستخدم جديد</h2>
    //         <form class="user-form" id="userForm">
    //             <div class="form-group">
    //                 <label>اسم المستخدم</label>
    //                 <input type="text" id="username" required>
    //             </div>
    //             <div class="form-group">
    //                 <label>كلمة المرور</label>
    //                 <input type="password" id="password" required>
    //             </div>
    //             <div class="form-group">
    //                 <label>تأكيد كلمة المرور</label>
    //                 <input type="password" id="confirmPassword" required>
    //             </div>
    //             <div class="form-group">
    //                 <label>البريد الإلكتروني</label>
    //                 <input type="email" id="email" required>
    //             </div>
    //         </form>
    //
    //         <div class="permissions-section">
    //             <h3>الصلاحيات</h3>
    //             <div class="permissions-grid">
    //                 <div class="permission-group">
    //                     <h3>المبيعات</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="sales_view">
    //                         <label for="sales_view">عرض المبيعات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="sales_add">
    //                         <label for="sales_add">إضافة فاتورة مبيعات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="sales_edit">
    //                         <label for="sales_edit">تعديل فاتورة مبيعات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="sales_delete">
    //                         <label for="sales_delete">حذف فاتورة مبيعات</label>
    //                     </div>
    //                 </div>
    //
    //                 <div class="permission-group">
    //                     <h3>المشتريات</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="purchases_view">
    //                         <label for="purchases_view">عرض المشتريات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="purchases_add">
    //                         <label for="purchases_add">إضافة فاتورة مشتريات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="purchases_edit">
    //                         <label for="purchases_edit">تعديل فاتورة مشتريات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="purchases_delete">
    //                         <label for="purchases_delete">حذف فاتورة مشتريات</label>
    //                     </div>
    //                 </div>
    //
    //                 <div class="permission-group">
    //                     <h3>الحسابات</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="accounts_view">
    //                         <label for="accounts_view">عرض القيود</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="accounts_add">
    //                         <label for="accounts_add">إضافة قيد</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="accounts_edit">
    //                         <label for="accounts_edit">تعديل قيد</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="accounts_delete">
    //                         <label for="accounts_delete">حذف قيد</label>
    //                     </div>
    //                 </div>
    //
    //                 <div class="permission-group">
    //                     <h3>السندات</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="vouchers_view">
    //                         <label for="vouchers_view">عرض السندات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="vouchers_add">
    //                         <label for="vouchers_add">إضافة سند</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="vouchers_edit">
    //                         <label for="vouchers_edit">تعديل سند</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="vouchers_delete">
    //                         <label for="vouchers_delete">حذف سند</label>
    //                     </div>
    //                 </div>
    //
    //                 <div class="permission-group">
    //                     <h3>التقارير</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="report_sales">
    //                         <label for="report_sales">تقارير المبيعات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="report_purchases">
    //                         <label for="report_purchases">تقارير المشتريات</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="report_financial">
    //                         <label for="report_financial">التقارير المالية</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="report_employees">
    //                         <label for="report_employees">تقارير الموظفين</label>
    //                     </div>
    //                 </div>
    //
    //                 <div class="permission-group">
    //                     <h3>شؤون الموظفين</h3>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="hr_view">
    //                         <label for="hr_view">عرض بيانات الموظفين</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="hr_add">
    //                         <label for="hr_add">إضافة موظف</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="hr_edit">
    //                         <label for="hr_edit">تعديل بيانات موظف</label>
    //                     </div>
    //                     <div class="permission-item">
    //                         <input type="checkbox" id="hr_salary">
    //                         <label for="hr_salary">إدارة الرواتب</label>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>
    //
    //         <div class="user-form-buttons">
    //             <button type="button" class="cancel-user-btn">إلغاء</button>
    //             <button type="button" class="save-user-btn">حفظ المستخدم</button>
    //         </div>
    //
    //         <div class="users-list">
    //             <h3>المستخدمون الحاليون</h3>
    //             <div class="user-card">
    //                 <div class="user-info">
    //                     <h4>أحمد محمد</h4>
    //                     <p>ahmed@example.com</p>
    //                 </div>
    //                 <div class="user-actions">
    //                     <button title="تعديل"><i class="fas fa-edit"></i></button>
    //                     <button title="حذف"><i class="fas fa-trash"></i></button>
    //                 </div>
    //             </div>
    //             <div class="user-card">
    //                 <div class="user-info">
    //                     <h4>سارة أحمد</h4>
    //                     <p>sara@example.com</p>
    //                 </div>
    //                 <div class="user-actions">
    //                     <button title="تعديل"><i class="fas fa-edit"></i></button>
    //                     <button title="حذف"><i class="fas fa-trash"></i></button>
    //                 </div>
    //             </div>
    //         </div>
    //     `;
    //
    //     usersSection.appendChild(userPermissionsCard);
    //     return usersSection;
    // }
    //
    // function getAccountOptions(category) {
    //     switch(category) {
    //         case 'العملاء':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="c1">حساب عميل 1</option>
    //                 <option value="c2">حساب عميل 2</option>
    //                 <option value="c3">حساب عميل 3</option>
    //             `;
    //         case 'الموردين':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="s1">حساب مورد 1</option>
    //                 <option value="s2">حساب مورد 2</option>
    //                 <option value="s3">حساب مورد 3</option>
    //             `;
    //         case 'الصناديق':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="f1">صندوق 1</option>
    //                 <option value="f2">صندوق 2</option>
    //                 <option value="f3">صندوق 3</option>
    //             `;
    //         case 'البنوك':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="b1">حساب بنك 1</option>
    //                 <option value="b2">حساب بنك 2</option>
    //                 <option value="b3">حساب بنك 3</option>
    //             `;
    //         case 'الفروع':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="br1">حساب فرع 1</option>
    //                 <option value="br2">حساب فرع 2</option>
    //                 <option value="br3">حساب فرع 3</option>
    //             `;
    //         case 'المخازن':
    //             return `
    //                 <option value="">اختر من شجرة الحسابات...</option>
    //                 <option value="w1">حساب مخزن 1</option>
    //                 <option value="w2">حساب مخزن 2</option>
    //                 <option value="w3">حساب مخزن 3</option>
    //             `;
    //         default:
    //             return '';
    //     }
    // }
    //



    function createBalanceSheetSection() {
        const section = document.createElement('div');
        section.id = 'balanceSheetSection';

        // Sample data - in a real application, this would come from your database
        const assets = {
            currentAssets: [
                { name: 'النقدية بالصندوق', amount: 150000 },
                { name: 'النقدية بالبنك', amount: 450000 },
                { name: 'المدينون', amount: 280000 },
                { name: 'المخزون', amount: 320000 },
                { name: 'مصروفات مدفوعة مقدماً', amount: 45000 }
            ],
            nonCurrentAssets: [
                { name: 'الأراضي', amount: 1200000 },
                { name: 'المباني', amount: 850000 },
                { name: 'السيارات', amount: 320000 },
                { name: 'الأثاث والمعدات', amount: 180000 }
            ]
        };

        const liabilities = {
            currentLiabilities: [
                { name: 'الدائنون', amount: 420000 },
                { name: 'أوراق الدفع', amount: 180000 },
                { name: 'مصروفات مستحقة', amount: 65000 }
            ],
            nonCurrentLiabilities: [
                { name: 'قروض طويلة الأجل', amount: 750000 },
                { name: 'سندات', amount: 500000 }
            ]
        };

        const equity = [
            { name: 'رأس المال', amount: 1500000 },
            { name: 'الاحتياطي القانوني', amount: 180000 },
            { name: 'الأرباح المحتجزة', amount: 150000 }
        ];

        const totalCurrentAssets = assets.currentAssets.reduce((sum, item) => sum + item.amount, 0);
        const totalNonCurrentAssets = assets.nonCurrentAssets.reduce((sum, item) => sum + item.amount, 0);
        const totalAssets = totalCurrentAssets + totalNonCurrentAssets;

        const totalCurrentLiabilities = liabilities.currentLiabilities.reduce((sum, item) => sum + item.amount, 0);
        const totalNonCurrentLiabilities = liabilities.nonCurrentLiabilities.reduce((sum, item) => sum + item.amount, 0);
        const totalLiabilities = totalCurrentLiabilities + totalNonCurrentLiabilities;

        const totalEquity = equity.reduce((sum, item) => sum + item.amount, 0);

        section.innerHTML = `
            <div class="balance-sheet-container">
                <div class="report-header">
                    <div class="branch-info">
                        <h2>الفرع: الفرع الرئيسي</h2>
                    </div>
                    <h1>قائمة المركز المالي</h1>
                    <div class="report-period">
                        <span>كما في:</span>
                        <input type="date" id="balanceSheetDate">
                        <button class="view-report-btn">
                            <i class="fas fa-search"></i>
                            عرض التقرير
                        </button>
                    </div>
                </div>

                <div class="balance-sheet-content">
                    <div class="balance-sheet-section assets">
                        <h2>
                            <i class="fas fa-coins"></i>
                            الأصول
                        </h2>

                        <div class="balance-sheet-subsection">
                            <h3>الأصول المتداولة</h3>
                            <div class="balance-sheet-items">
                                ${assets.currentAssets.map(item => `
                                    <div class="balance-sheet-item">
                                        <span class="item-name">${item.name}</span>
                                        <span class="item-amount">${item.amount.toLocaleString()} ريال</span>
                                    </div>
                                `).join('')}
                                <div class="balance-sheet-subtotal">
                                    <span>مجموع الأصول المتداولة</span>
                                    <span>${totalCurrentAssets.toLocaleString()} ريال</span>
                                </div>
                            </div>
                        </div>

                        <div class="balance-sheet-subsection">
                            <h3>الأصول غير المتداولة</h3>
                            <div class="balance-sheet-items">
                                ${assets.nonCurrentAssets.map(item => `
                                    <div class="balance-sheet-item">
                                        <span class="item-name">${item.name}</span>
                                        <span class="item-amount">${item.amount.toLocaleString()} ريال</span>
                                    </div>
                                `).join('')}
                                <div class="balance-sheet-subtotal">
                                    <span>مجموع الأصول غير المتداولة</span>
                                    <span>${totalNonCurrentAssets.toLocaleString()} ريال</span>
                                </div>
                            </div>
                        </div>

                        <div class="balance-sheet-total">
                            <span>إجمالي الأصول</span>
                            <span>${totalAssets.toLocaleString()} ريال</span>
                        </div>
                    </div>

                    <div class="balance-sheet-section liabilities">
                        <h2>
                            <i class="fas fa-file-invoice-dollar"></i>
                            الالتزامات
                        </h2>

                        <div class="balance-sheet-subsection">
                            <h3>الالتزامات المتداولة</h3>
                            <div class="balance-sheet-items">
                                ${liabilities.currentLiabilities.map(item => `
                                    <div class="balance-sheet-item">
                                        <span class="item-name">${item.name}</span>
                                        <span class="item-amount">${item.amount.toLocaleString()} ريال</span>
                                    </div>
                                `).join('')}
                                <div class="balance-sheet-subtotal">
                                    <span>مجموع الالتزامات المتداولة</span>
                                    <span>${totalCurrentLiabilities.toLocaleString()} ريال</span>
                                </div>
                            </div>
                        </div>

                        <div class="balance-sheet-subsection">
                            <h3>الالتزامات غير المتداولة</h3>
                            <div class="balance-sheet-items">
                                ${liabilities.nonCurrentLiabilities.map(item => `
                                    <div class="balance-sheet-item">
                                        <span class="item-name">${item.name}</span>
                                        <span class="item-amount">${item.amount.toLocaleString()} ريال</span>
                                    </div>
                                `).join('')}
                                <div class="balance-sheet-subtotal">
                                    <span>مجموع الالتزامات غير المتداولة</span>
                                    <span>${totalNonCurrentLiabilities.toLocaleString()} ريال</span>
                                </div>
                            </div>
                        </div>

                        <div class="balance-sheet-total">
                            <span>إجمالي الالتزامات</span>
                            <span>${totalLiabilities.toLocaleString()} ريال</span>
                        </div>
                    </div>

                    <div class="balance-sheet-section equity">
                        <h2>
                            <i class="fas fa-chart-pie"></i>
                            حقوق الملكية
                        </h2>

                        <div class="balance-sheet-items">
                            ${equity.map(item => `
                                <div class="balance-sheet-item">
                                    <span class="item-name">${item.name}</span>
                                    <span class="item-amount">${item.amount.toLocaleString()} ريال</span>
                                </div>
                            `).join('')}
                            <div class="balance-sheet-total">
                                <span>إجمالي حقوق الملكية</span>
                                <span>${totalEquity.toLocaleString()} ريال</span>
                            </div>
                        </div>
                    </div>

                    <div class="balance-sheet-grand-total">
                        <span>إجمالي الالتزامات وحقوق الملكية</span>
                        <span>${(totalLiabilities + totalEquity).toLocaleString()} ريال</span>
                    </div>
                </div>

                <div class="balance-sheet-summary">
                    <div class="summary-card">
                        <i class="fas fa-coins"></i>
                        <div class="summary-details">
                            <h3>إجمالي الأصول</h3>
                            <p>${totalAssets.toLocaleString()} ريال</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <div class="summary-details">
                            <h3>إجمالي الالتزامات</h3>
                            <p>${totalLiabilities.toLocaleString()} ريال</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-chart-pie"></i>
                        <div class="summary-details">
                            <h3>إجمالي حقوق الملكية</h3>
                            <p>${totalEquity.toLocaleString()} ريال</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add event listener for view report button
        section.querySelector('.view-report-btn').addEventListener('click', () => {
            alert('جاري تحميل البيانات...');
        });

        return section;
    }


    function createCategoriesSection() {
        const section = document.createElement('div');
        section.id = 'categoriesSection';

        // Sample data - in a real application, this would come from your database
        const categories = [
            { id: '001', name: 'الإلكترونيات', branch: 'الفرع الرئيسي', type: 'رئيسي' },
            { id: '002', name: 'الهواتف الذكية', branch: 'الفرع الرئيسي', type: 'فرعي' },
            { id: '003', name: 'الأجهزة المنزلية', branch: 'فرع الرياض', type: 'رئيسي' }
        ];

        section.innerHTML = `
            <div class="categories-container">
                <div class="categories-header">
                    <h1>تصنيفات المنتجات</h1>
                    <button class="add-category-btn">
                        <i class="fas fa-plus"></i>
                        إضافة تصنيف جديد
                    </button>
                </div>

                <div class="categories-form-modal">
                    <div class="modal-content">
                        <h2>إضافة تصنيف جديد</h2>
                        <form class="category-form" id="categoryForm">
                            <div class="form-group">
                                <label>رقم التصنيف</label>
                                <input type="text" id="categoryId" required>
                            </div>
                            <div class="form-group">
                                <label>اسم التصنيف</label>
                                <input type="text" id="categoryName" required>
                            </div>
                            <div class="form-group">
                                <label>الفرع</label>
                                <select id="categoryBranch" required>
                                    <option value="">اختر الفرع...</option>
                                    <option value="main">الفرع الرئيسي</option>
                                    <option value="riyadh">فرع الرياض</option>
                                    <option value="jeddah">فرع جدة</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>نوع التصنيف</label>
                                <select id="categoryType" required>
                                    <option value="">اختر النوع...</option>
                                    <option value="main">رئيسي</option>
                                    <option value="sub">فرعي</option>
                                </select>
                            </div>
                            <div class="modal-buttons">
                                <button type="button" class="cancel-btn">إلغاء</button>
                                <button type="submit" class="save-btn">حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="categories-grid">
                    ${categories.map(category => `
                        <div class="category-card">
                            <div class="category-info">
                                <h3>${category.name}</h3>
                                <p class="category-type ${category.type === 'رئيسي' ? 'main' : 'sub'}">
                                    ${category.type}
                                </p>
                            </div>
                            <div class="category-actions">
                                <button class="action-btn edit" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        // Add event listeners
        const addButton = section.querySelector('.add-category-btn');
        const modal = section.querySelector('.categories-form-modal');
        const form = section.querySelector('#categoryForm');
        const cancelBtn = section.querySelector('.cancel-btn');

        addButton.addEventListener('click', () => {
            modal.classList.add('active');
            modalOverlay.classList.add('active');
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
            form.reset();
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            // Here you would typically save the category data
            alert('تم حفظ التصنيف بنجاح');
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
            form.reset();
        });

        // Add edit and delete functionality
        section.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = this.closest('.category-card');
                const name = card.querySelector('h3').textContent;
                const id = card.querySelector('.category-details p:first-child').textContent.split(' ')[1];
                const branch = card.querySelector('.category-details p:last-child').textContent.split(' ')[1];
                const type = card.querySelector('.category-type').textContent.trim();

                // Populate form with category data
                document.getElementById('categoryId').value = id;
                document.getElementById('categoryName').value = name;
                document.getElementById('categoryBranch').value = branch === 'الفرع الرئيسي' ? 'main' : 'riyadh';
                document.getElementById('categoryType').value = type === 'رئيسي' ? 'main' : 'sub';

                modal.classList.add('active');
                modalOverlay.classList.add('active');
            });
        });

        section.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('هل أنت متأكد من حذف هذا التصنيف؟')) {
                    this.closest('.category-card').remove();
                }
            });
        });

        return section;
    }


    // function createProductsSection() {
    //     const section = document.createElement('div');
    //     section.id = 'productsSection';
    //
    //     // Sample data - in a real application, this would come from your database
    //     const products = [
    //         {
    //             id: 'P001',
    //             name: 'هاتف ذكي',
    //             category: 'الإلكترونيات',
    //             price: 2499.99,
    //             cost: 1800.00,
    //             minPrice: '1800.00',
    //             tax: '15',
    //             mainUnit: 'قطعة',
    //             units: [
    //                 { unit: 'علبة', conversion: '12' },
    //                 { unit: 'كرتون', conversion: '144' }
    //             ],
    //             images: [
    //                 'https://via.placeholder.com/150',
    //                 'https://via.placeholder.com/150'
    //             ]
    //         },
    //         {
    //             id: 'P002',
    //             name: 'لابتوب',
    //             category: 'الإلكترونيات',
    //             price: 4999.99,
    //             cost: 3500.00,
    //             minPrice: '1800.00',
    //             tax: '15',
    //             mainUnit: 'قطعة',
    //             units: [
    //                 { unit: 'علبة', conversion: '12' },
    //                 { unit: 'كرتون', conversion: '144' }
    //             ],
    //             images: [
    //                 'https://via.placeholder.com/150',
    //                 'https://via.placeholder.com/150'
    //             ]
    //         },
    //         {
    //             id: 'P003',
    //             name: 'سماعات لاسلكية',
    //             category: 'الإلكترونيات',
    //             price: 499.99,
    //             cost: 300.00,
    //             minPrice: '1800.00',
    //             tax: '15',
    //             mainUnit: 'قطعة',
    //             units: [
    //                 { unit: 'علبة', conversion: '12' },
    //                 { unit: 'كرتون', conversion: '144' }
    //             ],
    //             images: [
    //                 'https://via.placeholder.com/150',
    //                 'https://via.placeholder.com/150'
    //             ]
    //         }
    //     ];
    //
    //     section.innerHTML = `
    //         <div class="products-container">
    //             <div class="products-header">
    //                 <h1>المنتجات</h1>
    //                 <button class="add-product-btn">
    //                     <i class="fas fa-plus"></i>
    //                     إضافة منتج جديد
    //                 </button>
    //             </div>
    //
    //             <div class="products-grid">
    //                 ${products.map(product => `
    //                     <div class="product-card">
    //                         <div class="product-icon">
    //                             <i class="fas fa-box"></i>
    //                         </div>
    //                         <div class="product-info">
    //                             <h3>${product.name}</h3>
    //                             <p class="product-category"><i class="fas fa-tag"></i> ${product.category}</p>
    //                             <p class="product-price"><i class="fas fa-money-bill-wave"></i> ${product.price.toLocaleString()} ريال</p>
    //                         </div>
    //                         <div class="product-actions">
    //                             <button class="action-btn view" title="عرض">
    //                                 <i class="fas fa-eye"></i>
    //                             </button>
    //                             <button class="action-btn edit" title="تعديل">
    //                                 <i class="fas fa-edit"></i>
    //                             </button>
    //                             <button class="action-btn delete" title="حذف">
    //                                 <i class="fas fa-trash"></i>
    //                             </button>
    //                         </div>
    //                     </div>
    //                 `).join('')}
    //             </div>
    //         </div>
    //
    //         <div class="product-form-modal">
    //             <div class="modal-content">
    //                 <h2>إضافة منتج جديد</h2>
    //                 <div class="tabs">
    //                     <button class="tab-btn active" data-tab="basic">البيانات الأساسية</button>
    //                     <button class="tab-btn" data-tab="pricing">الأسعار والتكاليف</button>
    //                     <button class="tab-btn" data-tab="units">الوحدات</button>
    //                     <button class="tab-btn" data-tab="images">الصور</button>
    //                 </div>
    //
    //                 <form id="productForm">
    //                     <div class="tab-content active" data-tab="basic">
    //                         <div class="form-group">
    //                             <label>اسم المنتج</label>
    //                             <input type="text" id="productName" required>
    //                         </div>
    //                         <div class="form-group">
    //                             <label>التصنيف</label>
    //                             <select id="productCategory" required>
    //                                 <option value="">اختر التصنيف...</option>
    //                                 <option value="electronics">الإلكترونيات</option>
    //                                 <option value="clothing">الملابس</option>
    //                                 <option value="furniture">الأثاث</option>
    //                             </select>
    //                         </div>
    //                         <div class="form-group">
    //                             <label>الوصف</label>
    //                             <textarea id="productDescription"></textarea>
    //                         </div>
    //                     </div>
    //
    //                     <div class="tab-content" data-tab="pricing">
    //                         <div class="form-group">
    //                             <label>سعر البيع</label>
    //                             <input type="number" id="productPrice" step="0.01" required>
    //                         </div>
    //                         <div class="form-group">
    //                             <label>تكلفة الشراء</label>
    //                             <input type="number" id="productCost" step="0.01" required>
    //                         </div>
    //                         <div class="form-group">
    //                             <label>الحد الأدنى للسعر</label>
    //                             <input type="number" id="productMinPrice" step="0.01">
    //                         </div>
    //                         <div class="form-group">
    //                             <label>نسبة الضريبة</label>
    //                             <input type="number" id="productTax" step="0.01">
    //                         </div>
    //                     </div>
    //
    //                     <div class="tab-content" data-tab="units">
    //                         <div class="form-group">
    //                             <label>الوحدة الرئيسية</label>
    //                             <select id="productMainUnit" required>
    //                                 <option value="piece">قطعة</option>
    //                                 <option value="box">علبة</option>
    //                                 <option value="kg">كيلوجرام</option>
    //                                 <option value="meter">متر</option>
    //                             </select>
    //                         </div>
    //                         <div class="unit-conversions">
    //                             <h4>وحدات التحويل</h4>
    //                             <button type="button" class="add-unit-btn">
    //                                 <i class="fas fa-plus"></i>
    //                                 إضافة وحدة
    //                             </button>
    //                             <div class="unit-list">
    //                                 <!-- Units will be added here dynamically -->
    //                             </div>
    //                         </div>
    //                     </div>
    //
    //                     <div class="tab-content" data-tab="images">
    //                         <div class="form-group">
    //                             <label>صور المنتج</label>
    //                             <div class="image-upload-container">
    //                                 <div class="image-upload-zone">
    //                                     <input type="file" id="productImages" multiple accept="image/*" class="image-input">
    //                                     <div class="upload-placeholder">
    //                                         <i class="fas fa-cloud-upload-alt"></i>
    //                                         <p>اسحب الصور هنا أو انقر للاختيار</p>
    //                                     </div>
    //                                 </div>
    //                                 <div class="product-images-preview">
    //                                     <!-- Images will be displayed here -->
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //
    //                     <div class="modal-buttons">
    //                         <button type="button" class="cancel-btn">إلغاء</button>
    //                         <button type="submit" class="save-btn">حفظ</button>
    //                     </div>
    //                 </form>
    //             </div>
    //         </div>
    //     `;
    //
    //     // Add event listeners after creating the section
    //     const addButton = section.querySelector('.add-product-btn');
    //     const modal = section.querySelector('.product-form-modal');
    //     const form = section.querySelector('#productForm');
    //     const cancelBtn = section.querySelector('.cancel-btn');
    //     const tabButtons = section.querySelectorAll('.tab-btn');
    //     const tabContents = section.querySelectorAll('.tab-content');
    //
    //     addButton.addEventListener('click', () => {
    //         modal.classList.add('active');
    //         modalOverlay.classList.add('active');
    //     });
    //
    //     cancelBtn.addEventListener('click', () => {
    //         modal.classList.remove('active');
    //         modalOverlay.classList.remove('active');
    //         form.reset();
    //     });
    //
    //     // Tab switching functionality
    //     tabButtons.forEach(button => {
    //         button.addEventListener('click', () => {
    //             const tabName = button.dataset.tab;
    //
    //             // Update active states
    //             tabButtons.forEach(btn => btn.classList.remove('active'));
    //             tabContents.forEach(content => content.classList.remove('active'));
    //
    //             button.classList.add('active');
    //             section.querySelector(`.tab-content[data-tab="${tabName}"]`).classList.add('active');
    //         });
    //     });
    //
    //     // Add unit conversion row
    //     const addUnitBtn = section.querySelector('.add-unit-btn');
    //     const unitList = section.querySelector('.unit-list');
    //
    //     addUnitBtn.addEventListener('click', () => {
    //         const unitRow = document.createElement('div');
    //         unitRow.innerHTML = `
    //             <select class="sub-unit">
    //                 <option value="box">علبة</option>
    //                 <option value="piece">قطعة</option>
    //                 <option value="dozen">دزينة</option>
    //             </select>
    //             <span>يساوي</span>
    //             <input type="number" min="1" step="0.01" value="1">
    //             <select class="main-unit">
    //                 <option value="piece">قطعة</option>
    //                 <option value="box">علبة</option>
    //                 <option value="dozen">دزينة</option>
    //             </select>
    //             <button type="button" class="remove-unit-btn">
    //                 <i class="fas fa-times"></i>
    //             </button>
    //         `;
    //
    //         unitList.appendChild(unitRow);
    //
    //         // Add remove functionality
    //         unitRow.querySelector('.remove-unit-btn').addEventListener('click', () => {
    //             unitRow.remove();
    //         });
    //     });
    //
    //     // Form submission
    //     form.addEventListener('submit', (e) => {
    //         e.preventDefault();
    //         // Here you would typically save the product data
    //         alert('تم حفظ المنتج بنجاح');
    //         modal.classList.remove('active');
    //         modalOverlay.classList.remove('active');
    //         form.reset();
    //     });
    //
    //     // Product actions
    //     section.querySelectorAll('.product-card .action-btn').forEach(btn => {
    //         btn.addEventListener('click', function(e) {
    //             e.stopPropagation();
    //             const card = this.closest('.product-card');
    //             const productName = card.querySelector('h3').textContent;
    //
    //             if (this.classList.contains('view')) {
    //                 // View product details
    //                 alert(`عرض تفاصيل المنتج: ${productName}`);
    //             } else if (this.classList.contains('edit')) {
    //                 // Show edit form with product data
    //                 modal.classList.add('active');
    //                 modalOverlay.classList.add('active');
    //                 // Here you would populate the form with product data
    //             } else if (this.classList.contains('delete')) {
    //                 if (confirm(`هل أنت متأكد من حذف المنتج: ${productName}؟`)) {
    //                     card.remove();
    //                 }
    //             }
    //         });
    //     });
    //
    //     // Add image upload functionality
    //     const imageInput = section.querySelector('#productImages');
    //     const previewContainer = section.querySelector('.product-images-preview');
    //     const uploadZone = section.querySelector('.image-upload-zone');
    //
    //     uploadZone.addEventListener('dragover', (e) => {
    //         e.preventDefault();
    //         uploadZone.classList.add('dragover');
    //     });
    //
    //     uploadZone.addEventListener('dragleave', () => {
    //         uploadZone.classList.remove('dragover');
    //     });
    //
    //     uploadZone.addEventListener('drop', (e) => {
    //         e.preventDefault();
    //         uploadZone.classList.remove('dragover');
    //         const files = e.dataTransfer.files;
    //         handleImageFiles(files);
    //     });
    //
    //     imageInput.addEventListener('change', (e) => {
    //         handleImageFiles(e.target.files);
    //     });
    //
    //     function handleImageFiles(files) {
    //         for (const file of files) {
    //             if (file.type.startsWith('image/')) {
    //                 const reader = new FileReader();
    //                 reader.onload = (e) => {
    //                     const imageContainer = document.createElement('div');
    //                     imageContainer.className = 'preview-image-container';
    //                     imageContainer.innerHTML = `
    //                         <img src="${e.target.result}" alt="Product image">
    //                         <div class="image-actions">
    //                             <button type="button" class="delete-image" title="حذف">
    //                                 <i class="fas fa-trash"></i>
    //                             </button>
    //                             <button type="button" class="main-image" title="تعيين كصورة رئيسية">
    //                                 <i class="fas fa-star"></i>
    //                             </button>
    //                         </div>
    //                     `;
    //                     previewContainer.appendChild(imageContainer);
    //
    //                     // Add delete functionality
    //                     imageContainer.querySelector('.delete-image').addEventListener('click', () => {
    //                         imageContainer.remove();
    //                     });
    //
    //                     // Add main image functionality
    //                     imageContainer.querySelector('.main-image').addEventListener('click', (e) => {
    //                         document.querySelectorAll('.preview-image-container').forEach(container => {
    //                             container.classList.remove('main');
    //                         });
    //                         imageContainer.classList.add('main');
    //                     });
    //                 };
    //                 reader.readAsDataURL(file);
    //             }
    //         }
    //     }
    //
    //     return section;
    // }

    // document.querySelectorAll('.product-card').forEach(card => {
    //     card.addEventListener('click', function(e) {
    //         // Don't trigger if clicking action buttons
    //         if (e.target.closest('.card-actions')) return;
    //
    //         const productName = this.querySelector('h3').textContent;
    //         const productCategory = this.querySelector('.product-category').textContent.replace('التصنيف: ', '');
    //         const productPrice = this.querySelector('.product-price').textContent.replace(' ريال', '');
    //
    //         showProductDetails(productName, productCategory, productPrice);
    //     });
    // });

    // function showProductDetails(productName, productCategory, productPrice) {
    //     const detailsModal = document.createElement('div');
    //     detailsModal.className = 'product-form-modal';
    //
    //     // Sample data - in a real application, this would come from your database
    //     const productData = {
    //         name: productName,
    //         code: 'PRD001', // Sample product code
    //         category: productCategory,
    //         description: 'وصف تجريبي للمنتج',
    //         price: productPrice,
    //         price2: '2200.00', // Sample second price
    //         cost: '1500.00',
    //         minPrice: '1800.00',
    //         tax: '15',
    //         mainUnit: 'قطعة',
    //         units: [
    //             { unit: 'علبة', conversion: '12' },
    //             { unit: 'كرتون', conversion: '144' }
    //         ],
    //         images: [
    //             'https://via.placeholder.com/150',
    //             'https://via.placeholder.com/150'
    //         ]
    //     };
    //
    //     detailsModal.innerHTML = `
    //         <div class="modal-content">
    //             <h2>تفاصيل المنتج</h2>
    //             <div class="tabs">
    //                 <button class="tab-btn active" data-tab="basic">البيانات الأساسية</button>
    //                 <button class="tab-btn" data-tab="pricing">الأسعار والتكاليف</button>
    //                 <button class="tab-btn" data-tab="units">الوحدات</button>
    //                 <button class="tab-btn" data-tab="images">الصور</button>
    //             </div>
    //
    //             <div class="tab-content active" data-tab="basic">
    //                 <div class="details-group">
    //                     <label>اسم المنتج</label>
    //                     <div class="detail-value">${productData.name}</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>رقم الصنف</label>
    //                     <div class="detail-value">${productData.code}</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>التصنيف</label>
    //                     <div class="detail-value">${productData.category}</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>الوصف</label>
    //                     <div class="detail-value">${productData.description}</div>
    //                 </div>
    //             </div>
    //
    //             <div class="tab-content" data-tab="pricing">
    //                 <div class="details-group">
    //                     <label>سعر البيع 1</label>
    //                     <div class="detail-value">${productData.price} ريال</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>سعر البيع 2</label>
    //                     <div class="detail-value">${productData.price2} ريال</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>تكلفة الشراء</label>
    //                     <div class="detail-value">${productData.cost} ريال</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>الحد الأدنى للسعر</label>
    //                     <div class="detail-value">${productData.minPrice} ريال</div>
    //                 </div>
    //                 <div class="details-group">
    //                     <label>نسبة الضريبة</label>
    //                     <div class="detail-value">${productData.tax}%</div>
    //                 </div>
    //             </div>
    //
    //             <!-- Rest of the modal content remains the same -->
    //         </div>
    //     `;
    //
    //     // Rest of the function remains the same...
    // }
    //
    // function showProductEditForm(productData) {
    //     const modal = document.querySelector('.product-form-modal');
    //     modal.classList.add('active');
    //     modalOverlay.classList.add('active');
    //
    //     // Populate form with product data
    //     document.getElementById('productName').value = productData.name;
    //     document.getElementById('productCategory').value = productData.category;
    //     document.getElementById('productDescription').value = productData.description;
    //     document.getElementById('productPrice').value = parseFloat(productData.price);
    //     document.getElementById('productCost').value = parseFloat(productData.cost);
    //     document.getElementById('productMinPrice').value = parseFloat(productData.minPrice);
    //     document.getElementById('productTax').value = parseFloat(productData.tax);
    //     document.getElementById('productMainUnit').value = productData.mainUnit;
    //
    //     // Switch to edit mode
    //     modal.querySelector('h2').textContent = 'تعديل المنتج';
    //
    //     // Show first tab
    //     modal.querySelector('.tab-btn[data-tab="basic"]').click();
    // }


    // function createSalesInvoiceSection() {
    //     const section = document.createElement('div');
    //     section.id = 'salesInvoiceSection';
    //
    //     // Sample data - in a real application, this would come from your database
    //     const invoices = [
    //         {
    //             number: 'INV-001',
    //             date: '2024-01-15',
    //             customer: 'عميل 1',
    //             branch: 'الفرع الرئيسي',
    //             total: 2500.00,
    //             status: 'مدفوعة'
    //         },
    //         {
    //             number: 'INV-002',
    //             date: '2024-01-16',
    //             customer: 'عميل 2',
    //             branch: 'فرع الرياض',
    //             total: 3750.00,
    //             status: 'معلقة'
    //         }
    //     ];
    //
    //     section.innerHTML = `
    //         <div class="invoices-container">
    //             <div class="invoices-header">
    //                 <h1>فواتير المبيعات</h1>
    //                 <button class="add-invoice-btn">
    //                     <i class="fas fa-plus"></i>
    //                     فاتورة جديدة
    //                 </button>
    //             </div>
    //
    //             <div class="invoices-grid">
    //                 ${invoices.map(invoice => `
    //                     <div class="invoice-card">
    //                         <div class="card-header">
    //                             <span class="invoice-number">${invoice.number}</span>
    //                             <span class="invoice-date">${invoice.date}</span>
    //                         </div>
    //                         <div class="invoice-details">
    //                             <p>
    //                                 <span>العميل:</span>
    //                                 <span>${invoice.customer}</span>
    //                             </p>
    //                             <p>
    //                                 <span>الفرع:</span>
    //                                 <span>${invoice.branch}</span>
    //                             </p>
    //                             <p>
    //                                 <span>الحالة:</span>
    //                                 <span>${invoice.status}</span>
    //                             </p>
    //                         </div>
    //                         <div class="invoice-total">
    //                             ${invoice.total.toLocaleString()} ريال
    //                         </div>
    //                         <div class="card-actions">
    //                             <button class="action-btn view" title="عرض">
    //                                 <i class="fas fa-eye"></i>
    //                             </button>
    //                             <button class="action-btn edit" title="تعديل">
    //                                 <i class="fas fa-edit"></i>
    //                             </button>
    //                             <button class="action-btn delete" title="حذف">
    //                                 <i class="fas fa-trash"></i>
    //                             </button>
    //                         </div>
    //                     </div>
    //                 `).join('')}
    //             </div>
    //         </div>
    //     `;
    //
    //     // Add event listeners
    //     const addButton = section.querySelector('.add-invoice-btn');
    //     const modal = document.querySelector('.invoice-form-modal');
    //     const form = document.querySelector('#invoiceForm');
    //
    //     addButton.addEventListener('click', () => {
    //         modal.classList.add('active');
    //         modalOverlay.classList.add('active');
    //     });
    //
    //     // Add item button functionality
    //     const addItemBtn = document.querySelector('.add-item-btn');
    //     const itemsBody = document.querySelector('#invoiceItemsBody');
    //
    //     addItemBtn.addEventListener('click', () => {
    //         const newRow = document.createElement('tr');
    //         newRow.innerHTML = `
    //             <td>
    //                 <select class="item-select" required>
    //                     <option value="">اختر الصنف...</option>
    //                     <option value="1">صنف 1</option>
    //                     <option value="2">صنف 2</option>
    //                 </select>
    //             </td>
    //             <td class="item-code">-</td>
    //             <td>
    //                 <select class="unit-select" required>
    //                     <option value="">اختر الوحدة...</option>
    //                     <option value="piece">قطعة</option>
    //                     <option value="box">علبة</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <input type="number" class="quantity-input" min="1" value="1" required>
    //             </td>
    //             <td>
    //                 <input type="number" class="price-input" step="0.01" required>
    //             </td>
    //             <td class="item-total">0.00</td>
    //             <td>
    //                 <button type="button" class="remove-item-btn">
    //                     <i class="fas fa-times"></i>
    //                 </button>
    //             </td>
    //         `;
    //         itemsBody.appendChild(newRow);
    //
    //         // Add event listeners for calculations
    //         addItemCalculationListeners(newRow);
    //     });
    //
    //     // Form submission
    //     form.addEventListener('submit', (e) => {
    //         e.preventDefault();
    //         // Here you would typically save the invoice data
    //         alert('تم حفظ الفاتورة بنجاح');
    //         modal.classList.remove('active');
    //         modalOverlay.classList.remove('active');
    //         form.reset();
    //     });
    //
    //     // Add event listeners for invoice actions
    //     section.querySelectorAll('.invoice-card .action-btn').forEach(btn => {
    //         btn.addEventListener('click', function(e) {
    //             e.stopPropagation();
    //             const card = this.closest('.invoice-card');
    //             const invoiceNumber = card.querySelector('.invoice-number').textContent;
    //
    //             if (this.classList.contains('view')) {
    //                 showInvoiceDetails(card);
    //             } else if (this.classList.contains('edit')) {
    //                 editInvoice(card);
    //             } else if (this.classList.contains('delete')) {
    //                 deleteInvoice(card);
    //             }
    //         });
    //     });
    //
    //     return section;
    // }
    //
    // function generateInvoiceNumber() {
    //     // In a real application, this would typically come from your backend
    //     const prefix = 'INV';
    //     const randomNum = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    //     document.getElementById('invoiceNumber').value = `${prefix}-${randomNum}`;
    // }
    //
    // function addItemCalculationListeners(row) {
    //     const quantityInput = row.querySelector('.quantity-input');
    //     const priceInput = row.querySelector('.price-input');
    //     const itemTotal = row.querySelector('.item-total');
    //     const removeBtn = row.querySelector('.remove-item-btn');
    //
    //     function calculateItemTotal() {
    //         const quantity = parseFloat(quantityInput.value) || 0;
    //         const price = parseFloat(priceInput.value) || 0;
    //         const total = quantity * price;
    //         itemTotal.textContent = total.toFixed(2);
    //         calculateInvoiceTotals();
    //     }
    //
    //     quantityInput.addEventListener('input', calculateItemTotal);
    //     priceInput.addEventListener('input', calculateItemTotal);
    //     removeBtn.addEventListener('click', () => {
    //         row.remove();
    //         calculateInvoiceTotals();
    //     });
    // }

    // function calculateInvoiceTotals() {
    //     const itemTotals = Array.from(document.querySelectorAll('.item-total'))
    //         .map(el => parseFloat(el.textContent) || 0);
    //
    //     const subtotal = itemTotals.reduce((sum, total) => sum + total, 0);
    //     const discount = parseFloat(document.getElementById('discount').value) || 0;
    //     const taxRate = 0.15; // 15% tax rate
    //
    //     const taxableAmount = subtotal - discount;
    //     const tax = taxableAmount * taxRate;
    //     const total = taxableAmount + tax;
    //
    //     document.getElementById('subtotal').value = subtotal.toFixed(2);
    //     document.getElementById('tax').value = tax.toFixed(2);
    //     document.getElementById('total').value = total.toFixed(2);
    // }

    // document.querySelector('.quick-action-card:nth-child(2)').addEventListener('click', showSalesInvoiceForm);
    //
    // document.querySelector('.add-invoice-btn')?.addEventListener('click', showSalesInvoiceForm);

    // function showSalesInvoiceForm() {
    //     const modal = document.querySelector('.invoice-form-modal');
    //     const overlay = document.querySelector('.modal-overlay');
    //
    //     if (!modal || !overlay) {
    //         console.warn('Invoice form modal or overlay not found');
    //         return;
    //     }
    //
    //     // Show modal and overlay with animation
    //     modal.classList.add('active');
    //     overlay.classList.add('active');
    //
    //     // Generate invoice number
    //     generateInvoiceNumber();
    //
    //     // Set today's date as default
    //     const today = new Date().toISOString().split('T')[0];
    //     const dateInput = document.getElementById('invoiceDate');
    //     if (dateInput) {
    //         dateInput.value = today;
    //     }
    //
    //     // Reset form
    //     const form = document.getElementById('invoiceForm');
    //     if (form) {
    //         form.reset();
    //     }
    //
    //     // Clear items table except header
    //     const itemsBody = document.getElementById('invoiceItemsBody');
    //     if (itemsBody) {
    //         itemsBody.innerHTML = '';
    //     }
    //
    //     // Reset totals
    //     const inputs = ['subtotal', 'discount', 'tax', 'total'];
    //     inputs.forEach(id => {
    //         const input = document.getElementById(id);
    //         if (input) {
    //             input.value = '0.00';
    //         }
    //     });
    //
    //     // Add event listeners for closing modal
    //     const cancelBtn = modal.querySelector('.cancel-btn');
    //     if (cancelBtn) {
    //         cancelBtn.addEventListener('click', () => {
    //             modal.classList.remove('active');
    //             overlay.classList.remove('active');
    //         });
    //     }
    //
    //     // Add event listener for form submission
    //     if (form) {
    //         form.addEventListener('submit', (e) => {
    //             e.preventDefault();
    //             // Here you would typically save the invoice data
    //             alert('تم حفظ الفاتورة بنجاح');
    //             modal.classList.remove('active');
    //             overlay.classList.remove('active');
    //         });
    //     }
    //
    //     // Add event listener for adding new items
    //     const addItemBtn = modal.querySelector('.add-item-btn');
    //     const itemsBodyModal = modal.querySelector('#invoiceItemsBody');
    //
    //     addItemBtn.addEventListener('click', () => {
    //         const newRow = document.createElement('tr');
    //         newRow.innerHTML = `
    //             <td>
    //                 <select class="item-select" required>
    //                     <option value="">اختر الصنف...</option>
    //                     <option value="1">صنف 1</option>
    //                     <option value="2">صنف 2</option>
    //                 </select>
    //             </td>
    //             <td class="item-code">-</td>
    //             <td>
    //                 <select class="unit-select" required>
    //                     <option value="">اختر الوحدة...</option>
    //                     <option value="piece">قطعة</option>
    //                     <option value="box">علبة</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <input type="number" class="quantity-input" min="1" value="1" required>
    //             </td>
    //             <td>
    //                 <input type="number" class="price-input" step="0.01" required>
    //             </td>
    //             <td class="item-total">0.00</td>
    //             <td>
    //                 <button type="button" class="remove-item-btn">
    //                     <i class="fas fa-times"></i>
    //                 </button>
    //             </td>
    //         `;
    //         itemsBodyModal.appendChild(newRow);
    //
    //         // Add event listeners for calculations
    //         const quantityInput = newRow.querySelector('.quantity-input');
    //         const priceInput = newRow.querySelector('.price-input');
    //         const itemSelect = newRow.querySelector('.item-select');
    //         const removeBtn = newRow.querySelector('.remove-item-btn');
    //
    //         if (quantityInput && priceInput) {
    //             [quantityInput, priceInput].forEach(input => {
    //                 input.addEventListener('input', () => calculateRowTotal(newRow));
    //             });
    //         }
    //
    //         if (itemSelect) {
    //             itemSelect.addEventListener('change', () => {
    //                 const itemCode = newRow.querySelector('.item-code');
    //                 if (itemCode) {
    //                     itemCode.textContent = 'ABC' + itemSelect.value;
    //                 }
    //                 if (priceInput) {
    //                     priceInput.value = '100.00';
    //                 }
    //                 calculateRowTotal(newRow);
    //             });
    //         }
    //
    //         if (removeBtn) {
    //             removeBtn.addEventListener('click', () => {
    //                 newRow.remove();
    //                 calculateInvoiceTotals();
    //             });
    //         }
    //     });
    //
    //     // Add event listener for discount changes
    //     const discountInput = document.getElementById('discount');
    //     if (discountInput) {
    //         discountInput.addEventListener('input', calculateInvoiceTotals);
    //     }
    // }
    //
    // function calculateRowTotal(row) {
    //     if (!row) return;
    //
    //     const quantityInput = row.querySelector('.quantity-input');
    //     const priceInput = row.querySelector('.price-input');
    //     const itemTotal = row.querySelector('.item-total');
    //
    //     if (quantityInput && priceInput && itemTotal) {
    //         const quantity = parseFloat(quantityInput.value) || 0;
    //         const price = parseFloat(priceInput.value) || 0;
    //         const total = quantity * price;
    //         itemTotal.textContent = total.toFixed(2);
    //         calculateInvoiceTotals();
    //     }
    // }
    //
    // function calculateInvoiceTotals() {
    //     const itemTotals = Array.from(document.querySelectorAll('.item-total'))
    //         .map(el => parseFloat(el.textContent) || 0);
    //
    //     const subtotal = itemTotals.reduce((sum, total) => sum + total, 0);
    //     const discountInput = document.getElementById('discount');
    //     const discount = discountInput ? (parseFloat(discountInput.value) || 0) : 0;
    //     const taxRate = 0.15; // 15% tax rate
    //
    //     const taxableAmount = subtotal - discount;
    //     const tax = taxableAmount * taxRate;
    //     const total = taxableAmount + tax;
    //
    //     const elements = {
    //         subtotal: document.getElementById('subtotal'),
    //         tax: document.getElementById('tax'),
    //         total: document.getElementById('total')
    //     };
    //
    //     if (elements.subtotal) elements.subtotal.value = subtotal.toFixed(2);
    //     if (elements.tax) elements.tax.value = tax.toFixed(2);
    //     if (elements.total) elements.total.value = total.toFixed(2);
    // }

    // document.querySelector('.invoice-search input').addEventListener('input', function(e) {
    //     const searchTerm = e.target.value.toLowerCase();
    //     const invoiceCards = document.querySelectorAll('.invoice-card');
    //
    //     invoiceCards.forEach(card => {
    //         const invoiceText = card.textContent.toLowerCase();
    //         if (invoiceText.includes(searchTerm)) {
    //             card.style.display = 'block';
    //         } else {
    //             card.style.display = 'none';
    //         }
    //     });
    // });

    // document.querySelector('.invoice-search select').addEventListener('change', function(e) {
    //     const filterValue = e.target.value;
    //     const invoiceCards = document.querySelectorAll('.invoice-card');
    //
    //     invoiceCards.forEach(card => {
    //         if (filterValue === 'all') {
    //             card.style.display = 'block';
    //         } else {
    //             const status = card.querySelector('.invoice-status')?.classList.contains(filterValue);
    //             card.style.display = status ? 'block' : 'none';
    //         }
    //     });
    // });

    document.querySelectorAll('.invoice-card .action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const card = this.closest('.invoice-card');
            const invoiceNumber = card.querySelector('.invoice-number').textContent;

            if (this.classList.contains('view')) {
                showInvoiceDetails(card);
            } else if (this.classList.contains('edit')) {
                editInvoice(card);
            } else if (this.classList.contains('delete')) {
                deleteInvoice(card);
            }
        });
    });

    // function showInvoiceDetails(invoiceCard) {
    //     const modal = document.createElement('div');
    //     modal.className = 'invoice-form-modal';
    //
    //     // Get invoice data from the card
    //     const invoiceNumber = invoiceCard.querySelector('.invoice-number').textContent;
    //     const invoiceDate = invoiceCard.querySelector('.invoice-date').textContent.trim();
    //     const customer = invoiceCard.querySelector('.invoice-details p:nth-child(1) strong').textContent;
    //     const branch = invoiceCard.querySelector('.invoice-details p:nth-child(2) strong').textContent;
    //     const employee = invoiceCard.querySelector('.invoice-details p:nth-child(3) strong').textContent;
    //     const total = invoiceCard.querySelector('.invoice-total').textContent;
    //     const status = invoiceCard.querySelector('.invoice-status')?.textContent || 'معلقة';
    //
    //     // Sample invoice items - in a real application, this would come from your database
    //     const invoiceItems = [
    //         { name: 'منتج 1', code: 'P001', unit: 'قطعة', quantity: 2, price: 500, total: 1000 },
    //         { name: 'منتج 2', code: 'P002', unit: 'علبة', quantity: 1, price: 1500, total: 1500 }
    //     ];
    //
    //     modal.innerHTML = `
    //         <div class="modal-content">
    //             <h2>تفاصيل الفاتورة</h2>
    //             <div class="invoice-header-section">
    //                 <div class="form-row">
    //                     <div class="details-group">
    //                         <label>رقم الفاتورة</label>
    //                         <div class="detail-value">${invoiceNumber}</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>التاريخ</label>
    //                         <div class="detail-value">${invoiceDate}</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الحالة</label>
    //                         <div class="detail-value">${status}</div>
    //                     </div>
    //                 </div>
    //                 <div class="form-row">
    //                     <div class="details-group">
    //                         <label>العميل</label>
    //                         <div class="detail-value">${customer}</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الفرع</label>
    //                         <div class="detail-value">${branch}</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الموظف</label>
    //                         <div class="detail-value">${employee}</div>
    //                     </div>
    //                 </div>
    //             </div>
    //
    //             <div class="invoice-items-section">
    //                 <h3>الأصناف</h3>
    //                 <div class="invoice-items-table">
    //                     <table>
    //                         <thead>
    //                             <tr>
    //                                 <th>اسم الصنف</th>
    //                                 <th>رقم الصنف</th>
    //                                 <th>الوحدة</th>
    //                                 <th>الكمية</th>
    //                                 <th>السعر</th>
    //                                 <th>الإجمالي</th>
    //                             </tr>
    //                         </thead>
    //                         <tbody>
    //                             ${invoiceItems.map(item => `
    //                                 <tr>
    //                                     <td>${item.name}</td>
    //                                     <td>${item.code}</td>
    //                                     <td>${item.unit}</td>
    //                                     <td>${item.quantity}</td>
    //                                     <td>${item.price.toLocaleString()} ريال</td>
    //                                     <td>${item.total.toLocaleString()} ريال</td>
    //                                 </tr>
    //                             `).join('')}
    //                         </tbody>
    //                     </table>
    //                 </div>
    //             </div>
    //
    //             <div class="invoice-totals-section">
    //                 <div class="totals-grid">
    //                     <div class="details-group">
    //                         <label>الإجمالي</label>
    //                         <div class="detail-value">2,500.00 ريال</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الخصم</label>
    //                         <div class="detail-value">0.00 ريال</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الضريبة</label>
    //                         <div class="detail-value">375.00 ريال</div>
    //                     </div>
    //                     <div class="details-group">
    //                         <label>الإجمالي النهائي</label>
    //                         <div class="detail-value">${total}</div>
    //                     </div>
    //                 </div>
    //             </div>
    //
    //             <div class="modal-buttons">
    //                 <button type="button" class="print-btn">
    //                     <i class="fas fa-print"></i>
    //                     طباعة
    //                 </button>
    //                 <button type="button" class="edit-btn">
    //                     <i class="fas fa-edit"></i>
    //                     تعديل
    //                 </button>
    //                 <button type="button" class="cancel-btn">إغلاق</button>
    //             </div>
    //         </div>
    //     `;
    //
    //     document.body.appendChild(modal);
    //     modalOverlay.classList.add('active');
    //     modal.classList.add('active');
    //
    //     // Add event listeners
    //     modal.querySelector('.cancel-btn').addEventListener('click', () => {
    //         modalOverlay.classList.remove('active');
    //         modal.classList.remove('active');
    //         setTimeout(() => {
    //             modal.remove();
    //         }, 300);
    //     });
    //
    //     modal.querySelector('.print-btn').addEventListener('click', () => {
    //         window.print();
    //     });
    //
    //     modal.querySelector('.edit-btn').addEventListener('click', () => {
    //         modalOverlay.classList.remove('active');
    //         modal.classList.remove('active');
    //         editInvoice(invoiceCard);
    //     });
    // }

    // function editInvoice(invoiceCard) {
    //     // Get existing data from the invoice card
    //     const invoiceNumber = invoiceCard.querySelector('.invoice-number').textContent;
    //     const invoiceDate = invoiceCard.querySelector('.invoice-date').textContent.trim();
    //     const customer = invoiceCard.querySelector('.invoice-details p:nth-child(1) strong').textContent;
    //     const branch = invoiceCard.querySelector('.invoice-details p:nth-child(2) strong').textContent;
    //     const employee = invoiceCard.querySelector('.invoice-details p:nth-child(3) strong').textContent;
    //
    //     // Open the invoice form modal
    //     const modal = document.querySelector('.invoice-form-modal');
    //     modalOverlay.classList.add('active');
    //     modal.classList.add('active');
    //
    //     // Populate the form with existing data
    //     document.getElementById('invoiceNumber').value = invoiceNumber.replace('فاتورة رقم: ', '');
    //     document.getElementById('invoiceDate').value = formatDateForInput(invoiceDate);
    //     document.getElementById('customerSelect').value = '1'; // Set appropriate value
    //     document.getElementById('branchSelect').value = '1'; // Set appropriate value
    //     document.getElementById('employeeSelect').value = '1'; // Set appropriate value
    //
    //     // Add sample items - in real application, these would come from your database
    //     const itemsBody = document.getElementById('invoiceItemsBody');
    //     itemsBody.innerHTML = `
    //         <tr>
    //             <td>
    //                 <select class="item-select" required>
    //                     <option value="1" selected>منتج 1</option>
    //                     <option value="2">منتج 2</option>
    //                 </select>
    //             </td>
    //             <td class="item-code">P001</td>
    //             <td>
    //                 <select class="unit-select" required>
    //                     <option value="piece" selected>قطعة</option>
    //                     <option value="box">علبة</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <input type="number" class="quantity-input" value="2" min="1" required>
    //             </td>
    //             <td>
    //                 <input type="number" class="price-input" value="500" step="0.01" required>
    //             </td>
    //             <td class="item-total">1000.00</td>
    //             <td>
    //                 <button type="button" class="remove-item-btn">
    //                     <i class="fas fa-times"></i>
    //                 </button>
    //             </td>
    //         </tr>
    //     `;
    //
    //     // Update totals
    //     document.getElementById('subtotal').value = '2500.00';
    //     document.getElementById('discount').value = '0.00';
    //     document.getElementById('tax').value = '375.00';
    //     document.getElementById('total').value = '2875.00';
    //
    //     // Add calculation listeners to the items
    //     addItemCalculationListeners(itemsBody.querySelector('tr'));
    // }

    function deleteInvoice(invoiceCard) {
        if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
            invoiceCard.classList.add('animate__animated', 'animate__fadeOutRight');
            invoiceCard.addEventListener('animationend', () => {
                invoiceCard.remove();
            });
        }
    }

    function closeModal(modal) {
        modalOverlay.classList.remove('active');
        modal.classList.remove('active');
        setTimeout(() => {
            modal.remove();
        }, 300);
    }

    function formatDateForInput(dateString) {
        // Convert date string to format required by input type="date"
        // This is a simple example - adjust according to your date format
        return '2024-01-15';
    }

    // Helper function to format currency
    function formatCurrency(amount) {
        return parseFloat(amount).toLocaleString('ar-SA', {
            style: 'currency',
            currency: 'SAR'
        });
    }



    // function createAccountsTreeSection() {
    //     const section = document.createElement('div');
    //     section.id = 'accountsTreeSection';
    //


    // });

    // function createAccountNode(formData) {
    //     const node = document.createElement('div');
    //     node.className = 'tree-node';
    //     node.id = generateUniqueId();
    //     node.dataset.accountNumber = formData.number;
    //     node.dataset.accountType = formData.type;
    //
    //     node.innerHTML = `
    //         <div class="node-content">
    //             <i class="fas fa-file"></i>
    //             <span>${formData.name}</span>
    //             <div class="node-actions">
    //                 <button class="add-sub-account" title="إضافة حساب فرعي">
    //                     <i class="fas fa-plus"></i>
    //                 </button>
    //                 <button class="toggle-node" title="توسيع/طي">
    //                     <i class="fas fa-chevron-down"></i>
    //                 </button>
    //                 <button class="edit-account" title="تعديل">
    //                     <i class="fas fa-edit"></i>
    //                 </button>
    //                 <button class="delete-account" title="حذف">
    //                     <i class="fas fa-trash"></i>
    //                 </button>
    //             </div>
    //         </div>
    //         <div class="sub-nodes"></div>
    //     `;
    //

    //     // Add other event listeners (edit, delete, etc)
    //     attachAccountNodeEventListeners(node);
    //
    //     return node;
    // }

    function attachAccountNodeEventListeners(node) {
        // ... existing listeners ...





    }


    // document.querySelectorAll('.edit-account').forEach(btn => {
    //     btn.addEventListener('click', function(e) {
    //         e.stopPropagation();
    //         const accountNode = this.closest('.node-content').parentElement;
    //         const accountName = accountNode.querySelector('span').textContent;
    //
    //         // Show account form modal
    //         const modal = document.querySelector('.account-form-modal');
    //         modal.classList.add('active');
    //         document.querySelector('.modal-overlay').classList.add('active');
    //
    //         // Update form title and populate fields
    //         modal.querySelector('h2').textContent = 'تعديل الحساب';
    //         modal.querySelector('#accountName').value = accountName;
    //         modal.querySelector('#accountNumber').value = accountNode.dataset.accountNumber || '';
    //         modal.querySelector('#accountType').value = accountNode.dataset.accountType || 'debit';
    //
    //         // Store node reference for later use
    //         modal.dataset.editNodeId = accountNode.id || generateUniqueId();
    //         if (!accountNode.id) {
    //             accountNode.id = modal.dataset.editNodeId;
    //         }
    //     });
    // });
    //
    // document.querySelectorAll('.delete-account').forEach(btn => {
    //     btn.addEventListener('click', function(e) {
    //         e.stopPropagation();
    //         const accountNode = this.closest('.node-content').parentElement;
    //         const accountName = accountNode.querySelector('span').textContent;
    //
    //         if (confirm(`هل أنت متأكد من حذف الحساب "${accountName}"؟`)) {
    //             accountNode.remove();
    //         }
    //     });
    // });

    // Form submission handler


    // function createAccountNode(data) {
    //     const node = document.createElement('div');
    //     node.className = 'tree-node';
    //     node.id = generateUniqueId();
    //     node.dataset.accountNumber = data.number;
    //     node.dataset.accountType = data.type;
    //
    //     node.innerHTML = `
    //         <div class="node-content">
    //             <i class="fas fa-file"></i>
    //             <span>${data.name}</span>
    //             <div class="node-actions">
    //                 <button class="add-sub-account" title="إضافة حساب فرعي">
    //                     <i class="fas fa-plus"></i>
    //                 </button>
    //                 <button class="toggle-node" title="توسيع/طي">
    //                     <i class="fas fa-chevron-down"></i>
    //                 </button>
    //                 <button class="edit-account" title="تعديل">
    //                     <i class="fas fa-edit"></i>
    //                 </button>
    //                 <button class="delete-account" title="حذف">
    //                     <i class="fas fa-trash"></i>
    //                 </button>
    //             </div>
    //         </div>
    //         <div class="sub-nodes"></div>
    //     `;
    //
    //     // Add event listeners to new buttons
    //     attachAccountNodeEventListeners(node);
    //
    //     return node;
    // }

    // function attachAccountNodeEventListeners(node) {
    //     const addBtn = node.querySelector('.add-sub-account');
    //     const editBtn = node.querySelector('.edit-account');
    //     const deleteBtn = node.querySelector('.delete-account');
    //
    //     if (addBtn) {
    //         addBtn.addEventListener('click', function(e) {
    //             e.stopPropagation();
    //             // Show add sub-account modal
    //             const modal = document.querySelector('.account-form-modal');
    //             modal.classList.add('active');
    //             document.querySelector('.modal-overlay').classList.add('active');
    //             modal.dataset.parentNodeId = node.id;
    //         });
    //     }
    //
    //     if (editBtn) {
    //         editBtn.addEventListener('click', function(e) {
    //             e.stopPropagation();
    //             // Show edit modal with current data
    //             const modal = document.querySelector('.account-form-modal');
    //             modal.classList.add('active');
    //             document.querySelector('.modal-overlay').classList.add('active');
    //             modal.dataset.editNodeId = node.id;
    //             // Populate form with current data
    //             modal.querySelector('#accountName').value = node.querySelector('span').textContent;
    //             modal.querySelector('#accountNumber').value = node.dataset.accountNumber || '';
    //             modal.querySelector('#accountType').value = node.dataset.accountType || 'debit';
    //         });
    //     }
    //
    //     if (deleteBtn) {
    //         deleteBtn.addEventListener('click', function(e) {
    //             e.stopPropagation();
    //             if (confirm('هل أنت متأكد من حذف هذا الحساب؟')) {
    //                 node.remove();
    //             }
    //         });
    //     }
    // }

    function closeAccountModal() {
        const modal = document.querySelector('.account-form-modal');
        const modalOverlay = document.querySelector('.modal-overlay');
        if (modal && modalOverlay) {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
            modal.querySelector('form').reset();
        }
    }

    function generateUniqueId() {
        return 'account_' + Math.random().toString(36).substr(2, 9);
    }

    function getAccountTypeFromName(name) {
        const types = {
            'الأصول': 'assets',
            'الخصوم': 'liabilities',
            'الإيرادات': 'revenues',
            'المصروفات': 'expenses',
            'حقوق الملكية': 'equity'
        };
        return types[name] || '';
    }

    document.querySelectorAll('.toggle-node').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const node = this.closest('.tree-node');
            node.classList.toggle('collapsed');
            const icon = this.querySelector('i');
            if (node.classList.contains('collapsed')) {
                icon.style.transform = 'rotate(-90deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });

    document.querySelector('.account-form-modal .cancel-btn').addEventListener('click', function() {
        const modal = document.querySelector('.account-form-modal');
        const modalOverlay = document.querySelector('.modal-overlay');
        modal.classList.remove('active');
        modalOverlay.classList.remove('active');
        modal.querySelector('form').reset();
    });

    function closeAccountModal() {
        const modal = document.querySelector('.account-form-modal');
        const modalOverlay = document.querySelector('.modal-overlay');
        if (modal && modalOverlay) {
            modal.classList.remove('active');
            modalOverlay.classList.remove('active');
            modal.querySelector('form').reset();
        }
    }

    const initialData = [
        { id: '1001', name: 'النقدية بالصندوق', level: 'فرعي', type: 'مدين', debit: 120000, credit: 0 },
        { id: '1002', name: 'النقدية بالبنك', level: 'فرعي', type: 'مدين', debit: 250000, credit: 0 }
    ];

    const accountsTable = document.getElementById('accountsTable');
    const tableBody = accountsTable.querySelector('tbody');

    // Function to add a new row


    // Add event listeners for row inputs
    function addRowEventListeners(row) {
        // Update balance when debit or credit changes
        row.querySelectorAll('.number-input').forEach(input => {
            input.addEventListener('input', () => {
                const debit = parseFloat(row.querySelector('td:nth-child(5) input').value) || 0;
                const credit = parseFloat(row.querySelector('td:nth-child(6) input').value) || 0;
                row.querySelector('.balance').textContent = (debit - credit).toLocaleString();
                updateTotals();
            });
        });

        // Delete row button
        row.querySelector('.delete-row').addEventListener('click', () => {
            if (confirm('هل أنت متأكد من حذف هذا السطر؟')) {
                row.remove();
                updateTotals();
            }
        });
    }

    // Update totals


    // Add new row button


    // Initialize table with data






    function getParentAccountOptions(selectedParent) {
        const mainAccounts = Array.from(document.querySelectorAll('.main-node .account-name'))
            .map(el => el.textContent);

        return mainAccounts.map(account =>
            `<option value="${account}" ${account === selectedParent ? 'selected' : ''}>${account}</option>`
        ).join('');
    }

    // Modify the existing code to call updateAccountsTable when tree changes:

    function createAccountNode(formData) {
        // ... existing code ...

        updateAccountsTable();
        return node;
    }




    // Update table when editing account


    // Initial table population
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing code ...

        updateAccountsTable();
    });
});
