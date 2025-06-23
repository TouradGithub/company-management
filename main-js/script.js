// إضافة تأثيرات التحميل
function updateDate() {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    // document.getElementById('current-date').textContent = today.toLocaleDateString('ar-SA', options) ?? '';
}

function navigateToProfile() {
    alert('جاري الانتقال إلى الملف الشخصي');
}

document.addEventListener('DOMContentLoaded', () => {
    updateDate();
    const cards = document.querySelectorAll('.module-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);
    });
});

// Calculator functionality
function calculator() {
    const calcWindow = document.createElement('div');
    calcWindow.className = 'modal calculator-modal';
    calcWindow.innerHTML = `
        <div class="modal-content">
            <div class="calculator">
                <div class="calc-display">0</div>
                <div class="calc-buttons">
                    <button class="calc-btn clear">C</button>
                    <button class="calc-btn operator">±</button>
                    <button class="calc-btn operator">%</button>
                    <button class="calc-btn operator">÷</button>
                    <button class="calc-btn">7</button>
                    <button class="calc-btn">8</button>
                    <button class="calc-btn">9</button>
                    <button class="calc-btn operator">×</button>
                    <button class="calc-btn">4</button>
                    <button class="calc-btn">5</button>
                    <button class="calc-btn">6</button>
                    <button class="calc-btn operator">-</button>
                    <button class="calc-btn">1</button>
                    <button class="calc-btn">2</button>
                    <button class="calc-btn">3</button>
                    <button class="calc-btn operator">+</button>
                    <button class="calc-btn zero">0</button>
                    <button class="calc-btn">.</button>
                    <button class="calc-btn operator">=</button>
                </div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    document.body.appendChild(calcWindow);

    setupCalculator(calcWindow);
    setupModalClose(calcWindow);
}

// Task Manager functionality
function taskManager() {
    const taskWindow = document.createElement('div');
    taskWindow.className = 'modal';
    document.body.appendChild(taskWindow);

    setupTaskManager(taskWindow);
    setupModalClose(taskWindow);
}

// Agenda functionality
function agenda() {
    const agendaWindow = document.createElement('div');
    agendaWindow.className = 'modal agenda-modal';
    agendaWindow.innerHTML = `
        <div class="modal-content">
            <div class="calendar">
                <div class="calendar-header">
                    <button id="prevMonth">&lt;</button>
                    <h3 id="currentMonth"></h3>
                    <button id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-grid">
                    <div class="weekdays"></div>
                    <div class="days"></div>
                </div>
            </div>
            <div class="appointments">
                <h3>المواعيد</h3>
                <div class="appointment-input">
                    <input type="datetime-local" id="appointmentTime">
                    <input type="text" id="appointmentTitle" placeholder="عنوان الموعد">
                    <button id="addAppointment">إضافة</button>
                </div>
                <div class="appointment-list"></div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    document.body.appendChild(agendaWindow);

    setupAgenda(agendaWindow);
    setupModalClose(agendaWindow);
}

// Email functionality
function email() {
    const emailWindow = document.createElement('div');
    emailWindow.className = 'modal email-modal';
    emailWindow.innerHTML = `
        <div class="modal-content">
            <div class="email-app">
                <div class="email-compose">
                    <input type="text" placeholder="إلى" class="email-input">
                    <input type="text" placeholder="الموضوع" class="email-input">
                    <textarea placeholder="نص الرسالة" class="email-body"></textarea>
                    <div class="email-actions">
                        <button class="send-btn">إرسال</button>
                        <button class="draft-btn">حفظ كمسودة</button>
                    </div>
                </div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    document.body.appendChild(emailWindow);

    setupEmail(emailWindow);
    setupModalClose(emailWindow);
}

// Chat functionality
function chat() {
    const chatWindow = document.createElement('div');
    chatWindow.className = 'modal';
    chatWindow.innerHTML = `
        <div class="modal-content">
            <div class="chat-window">
                <div class="chat-sidebar">
                    <div class="chat-sidebar-header">
                        <h2>المحادثات</h2>
                        <input type="text" class="chat-search" placeholder="بحث...">
                    </div>
                    <div class="chat-contacts">
                        <div class="chat-contact active">
                            <div class="contact-avatar">س</div>
                            <div class="contact-info">
                                <div class="contact-name">سارة أحمد</div>
                                <div class="contact-status">متصل الآن</div>
                            </div>
                            <div class="chat-status status-online"></div>
                        </div>
                        <div class="chat-contact">
                            <div class="contact-avatar">م</div>
                            <div class="contact-info">
                                <div class="contact-name">محمد علي</div>
                                <div class="contact-status">منذ 5 دقائق</div>
                            </div>
                            <div class="chat-status status-offline"></div>
                        </div>
                        <div class="chat-contact">
                            <div class="contact-avatar">ف</div>
                            <div class="contact-info">
                                <div class="contact-name">فاطمة حسن</div>
                                <div class="contact-status">متصل الآن</div>
                            </div>
                            <div class="chat-status status-online"></div>
                        </div>
                        <div class="chat-contact">
                            <div class="contact-avatar">ع</div>
                            <div class="contact-info">
                                <div class="contact-name">عمر خالد</div>
                                <div class="contact-status">منذ ساعة</div>
                            </div>
                            <div class="chat-status status-offline"></div>
                        </div>
                    </div>
                </div>
                <div class="chat-main">
                    <div class="chat-header">
                        <div class="contact-avatar">س</div>
                        <div class="contact-info">
                            <div class="contact-name">سارة أحمد</div>
                            <div class="contact-status">متصل الآن</div>
                        </div>
                    </div>
                    <div class="chat-messages">
                        <div class="message received">
                            <div class="message-content">مرحباً، كيف يمكنني مساعدتك اليوم؟</div>
                            <div class="message-time">10:30</div>
                        </div>
                        <div class="message sent">
                            <div class="message-content">مرحباً سارة، أحتاج مساعدة في تقرير المبيعات</div>
                            <div class="message-time">10:31</div>
                        </div>
                        <div class="message received">
                            <div class="message-content">بالتأكيد! سأقوم بمراجعة التقرير وإرسال الملاحظات لك</div>
                            <div class="message-time">10:32</div>
                        </div>
                    </div>
                    <div class="chat-input">
                        <input type="text" placeholder="اكتب رسالتك هنا...">
                        <button>إرسال</button>
                    </div>
                </div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;
    document.body.appendChild(chatWindow);

    setupChat(chatWindow);
    setupModalClose(chatWindow);
}

// Helper functions
function setupModalClose(modal) {
    const closeBtn = modal.querySelector('.close-modal');
    closeBtn.onclick = () => modal.remove();
}

function setupCalculator(calcWindow) {
    let display = calcWindow.querySelector('.calc-display');
    let buttons = calcWindow.querySelectorAll('.calc-btn');
    let firstOperand = null;
    let operator = null;
    let newNumber = true;

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const value = button.textContent;

            if (value >= '0' && value <= '9' || value === '.') {
                if (newNumber) {
                    display.textContent = value;
                    newNumber = false;
                } else {
                    display.textContent += value;
                }
            } else if (value === 'C') {
                display.textContent = '0';
                firstOperand = null;
                operator = null;
                newNumber = true;
            } else if ('+-×÷%'.includes(value)) {
                firstOperand = parseFloat(display.textContent);
                operator = value;
                newNumber = true;
            } else if (value === '=') {
                if (operator && firstOperand !== null) {
                    const secondOperand = parseFloat(display.textContent);
                    let result;
                    switch(operator) {
                        case '+': result = firstOperand + secondOperand; break;
                        case '-': result = firstOperand - secondOperand; break;
                        case '×': result = firstOperand * secondOperand; break;
                        case '÷': result = firstOperand / secondOperand; break;
                        case '%': result = firstOperand % secondOperand; break;
                    }
                    display.textContent = result;
                    firstOperand = result;
                    newNumber = true;
                }
            }
        });
    });
}

function setupTaskManager(taskWindow) {
    const input = taskWindow.querySelector('#taskInput');
    const addButton = taskWindow.querySelector('#addTask');
    const taskList = taskWindow.querySelector('.task-list');
    const tasks = JSON.parse(localStorage.getItem('tasks') || '[]');

    function renderTasks() {
        taskList.innerHTML = tasks.map((task, index) => `
            <div class="task-item ${task.completed ? 'completed' : ''}">
                <input type="checkbox" ${task.completed ? 'checked' : ''} onchange="toggleTask(${index})">
                <span>${task.text}</span>
                <button onclick="deleteTask(${index})">×</button>
            </div>
        `).join('');
    }

    window.toggleTask = (index) => {
        tasks[index].completed = !tasks[index].completed;
        localStorage.setItem('tasks', JSON.stringify(tasks));
        renderTasks();
    };

    window.deleteTask = (index) => {
        tasks.splice(index, 1);
        localStorage.setItem('tasks', JSON.stringify(tasks));
        renderTasks();
    };

    addButton.onclick = () => {
        const text = input.value.trim();
        if (text) {
            tasks.push({ text, completed: false });
            localStorage.setItem('tasks', JSON.stringify(tasks));
            input.value = '';
            renderTasks();
        }
    };

    renderTasks();
}

function setupAgenda(agendaWindow) {
    const calendar = agendaWindow.querySelector('.calendar');
    const monthDisplay = calendar.querySelector('#currentMonth');
    const daysGrid = calendar.querySelector('.days');
    const weekdaysDiv = calendar.querySelector('.weekdays');
    const appointments = JSON.parse(localStorage.getItem('appointments') || '[]');
    let currentDate = new Date();

    // Add weekday names
    const weekdays = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    weekdaysDiv.innerHTML = weekdays.map(day => `<div>${day}</div>`).join('');

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthDisplay.textContent = new Date(year, month, 1)
            .toLocaleDateString('ar-SA', { month: 'long', year: 'numeric' });

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();

        let daysHTML = '';
        for (let i = 0; i < firstDay; i++) {
            daysHTML += '<div class="calendar-day"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day).toISOString().split('T')[0];
            const hasAppointment = appointments.some(apt => apt.date.startsWith(date));
            const isToday = today.getDate() === day &&
                           today.getMonth() === month &&
                           today.getFullYear() === year;

            daysHTML += `
                <div class="calendar-day ${hasAppointment ? 'has-appointment' : ''}
                                       ${isToday ? 'today' : ''}"
                     data-date="${date}">
                    ${day}
                </div>
            `;
        }

        daysGrid.innerHTML = daysHTML;

        // Add click event to days
        daysGrid.querySelectorAll('.calendar-day[data-date]').forEach(dayEl => {
            dayEl.addEventListener('click', () => {
                const date = dayEl.dataset.date;
                const timeInput = agendaWindow.querySelector('#appointmentTime');
                timeInput.value = date + 'T00:00';
            });
        });
    }

    function renderAppointments() {
        const appointmentList = agendaWindow.querySelector('.appointment-list');
        appointmentList.innerHTML = appointments
            .sort((a, b) => new Date(a.date) - new Date(b.date))
            .map((apt, index) => `
                <div class="appointment-item">
                    <span class="date">${new Date(apt.date).toLocaleDateString('ar-SA')}</span>
                    <span class="title">${apt.title}</span>
                    <button onclick="deleteAppointment(${index})">×</button>
                </div>
            `).join('');
    }

    window.deleteAppointment = (index) => {
        appointments.splice(index, 1);
        localStorage.setItem('appointments', JSON.stringify(appointments));
        renderAppointments();
        renderCalendar();
    };

    agendaWindow.querySelector('#addAppointment').onclick = () => {
        const time = agendaWindow.querySelector('#appointmentTime').value;
        const title = agendaWindow.querySelector('#appointmentTitle').value;

        if (time && title) {
            appointments.push({ date: time, title });
            localStorage.setItem('appointments', JSON.stringify(appointments));
            agendaWindow.querySelector('#appointmentTime').value = '';
            agendaWindow.querySelector('#appointmentTitle').value = '';
            renderAppointments();
            renderCalendar();
        }
    };

    agendaWindow.querySelector('#prevMonth').onclick = () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    };

    agendaWindow.querySelector('#nextMonth').onclick = () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    };

    renderCalendar();
    renderAppointments();
}

function setupEmail(emailWindow) {
    const sendBtn = emailWindow.querySelector('.send-btn');
    const draftBtn = emailWindow.querySelector('.draft-btn');

    sendBtn.onclick = () => {
        alert('تم إرسال الرسالة بنجاح');
        emailWindow.remove();
    };

    draftBtn.onclick = () => {
        alert('تم حفظ المسودة');
    };
}

function setupChat(chatWindow) {
    const input = chatWindow.querySelector('.chat-input input');
    const sendButton = chatWindow.querySelector('.chat-input button');
    const messagesContainer = chatWindow.querySelector('.chat-messages');
    const contacts = chatWindow.querySelectorAll('.chat-contact');

    // Handle sending messages
    function sendMessage() {
        const message = input.value.trim();
        if (message) {
            const time = new Date().toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
            const messageElement = document.createElement('div');
            messageElement.className = 'message sent';
            messageElement.innerHTML = `
                <div class="message-content">${message}</div>
                <div class="message-time">${time}</div>
            `;
            messagesContainer.appendChild(messageElement);
            input.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    sendButton.onclick = sendMessage;
    input.onkeypress = (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    };

    // Handle contact selection
    contacts.forEach(contact => {
        contact.onclick = () => {
            contacts.forEach(c => c.classList.remove('active'));
            contact.classList.add('active');
            // Update chat header
            const avatar = contact.querySelector('.contact-avatar').textContent;
            const name = contact.querySelector('.contact-name').textContent;
            const status = contact.querySelector('.contact-status').textContent;

            chatWindow.querySelector('.chat-header .contact-avatar').textContent = avatar;
            chatWindow.querySelector('.chat-header .contact-name').textContent = name;
            chatWindow.querySelector('.chat-header .contact-status').textContent = status;
        };
    });
}

// Update navigate function
function navigate(module) {
    switch(module) {
        case 'assets':
            window.location.href = `/assets/index`;
            break;
        case 'home':
            window.location.href = `/`;
            break;
        case 'vouchers':
            window.location.href = `/vouchers`;
            break;
        case 'bills':
            window.location.href = `/bills`;
            break;
        case 'properties':
            window.location.href = `/properties`;
            break;
        case 'bank-managment':
            window.location.href = `/bank-managment`;
            break;
        case 'funds':
            window.location.href = `/fund/index/`;
            break;
        case 'inventory':
            window.location.href = `/inventory`;
            // showInventoryModule();
            break;
        case 'email':
            email();
            break;
        case 'calculator':
            calculator();
            break;
        case 'tasks':
            taskManager();
            break;
        case 'agenda':
            agenda();
            break;
        case 'chat':
            chat();
            break;
        case 'accounting':
            showAccountingModule();
            break;
        case 'journal-entry':
            showJournalEntryForm();
            break;
        case 'sales-invoice':
            showSalesInvoiceModule();
            break;
        case 'purchase-invoice':
        case 'financial-statements':
        case 'reports':
        case 'account-statement':
            alert(`جاري الانتقال إلى ${module}`);
            break;
        case 'fixed-assets':
            showFixedAssetsModule();
            break;
        case 'pos':
            showPOSModule();
        case 'add':
            showADDModule();
            break;

        default:
            alert(`جاري الانتقال إلى ${module}`);
    }
}

function showInventoryModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="inventory-dashboard">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>إدارة المخازن</h2>
            <div class="inventory-modules">
                <div class="inventory-module" onclick="navigate('sales-units')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <path d="M22 6l-10 7L2 6"/>
                        <line x1="12" y1="3" x2="12" y2="7"/>
                        <line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="16" y1="3" x2="16" y2="7"/>
                    </svg>
                    <h3>تقرير الوحدات المباعة</h3>
                </div>

                <div class="inventory-module" onclick="navigate('production-cost')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                        <path d="M3.27 6.96L12 12.01l8.73-5.05"/>
                        <path d="M12 22.08V12"/>
                    </svg>
                    <h3>تقرير تكلفة الإنتاج</h3>
                </div>

                <div class="inventory-module" onclick="navigate('stock-items')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M20 3H4a1 1 0 00-1 1v16a1 1 0 001 1h16a1 1 0 001-1V4a1 1 0 00-1-1z"/>
                        <path d="M12 8v8M8 12h8"/>
                    </svg>
                    <h3>إضافة أصناف جديدة</h3>
                </div>

                <div class="inventory-module" onclick="navigate('stock-transfer')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M17 1l4 4-4 4"/>
                        <path d="M3 11V9a4 4 0 014-4h14"/>
                        <path d="M7 23l-4-4 4-4"/>
                        <path d="M21 13v2a4 4 0 01-4 4H3"/>
                    </svg>
                    <h3>تحويل مخزون</h3>
                </div>

                <div class="inventory-module" onclick="navigate('inventory-adjustment')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    <h3>تسوية المخزون</h3>
                </div>

                <div class="inventory-module" onclick="navigate('minimum-stock')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <path d="M12 11v6M8 11v6M16 11v6M4 11h16"/>
                    </svg>
                    <h3>الحد الأدنى للمخزون</h3>
                </div>

                <div class="inventory-module" onclick="navigate('expiry-report')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        <path d="M8 14h8"/>
                    </svg>
                    <h3>تقرير الصلاحية</h3>
                </div>

                <div class="inventory-module" onclick="navigate('inventory-movement')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 3v18h18"/>
                        <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
                    </svg>
                    <h3>حركة المخزون</h3>
                </div>
            </div>
        </div>
    `;
    // mainContent.innerHTML = `
    //     <div class="inventory-dashboard">
    //         <div class="header-actions">
    //             <button class="back-to-home" onclick="backToHome()">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M19 12H5M12 19l-7-7 7-7"/>
    //                 </svg>
    //                 العودة للرئيسية
    //             </button>
    //         </div>
    //         <h2>إدارة المخازن</h2>
    //         <div class="inventory-modules">
    //             <div class="inventory-module" onclick="navigate('sales-units')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
    //                     <path d="M22 6l-10 7L2 6"/>
    //                     <line x1="12" y1="3" x2="12" y2="7"/>
    //                     <line x1="8" y1="3" x2="8" y2="7"/>
    //                     <line x1="16" y1="3" x2="16" y2="7"/>
    //                 </svg>
    //                 <h3>تقرير الوحدات المباعة</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('production-cost')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
    //                     <path d="M3.27 6.96L12 12.01l8.73-5.05"/>
    //                     <path d="M12 22.08V12"/>
    //                 </svg>
    //                 <h3>تقرير تكلفة الإنتاج</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('stock-items')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M20 3H4a1 1 0 00-1 1v16a1 1 0 001 1h16a1 1 0 001-1V4a1 1 0 00-1-1z"/>
    //                     <path d="M12 8v8M8 12h8"/>
    //                 </svg>
    //                 <h3>إضافة أصناف جديدة</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('stock-transfer')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M17 1l4 4-4 4"/>
    //                     <path d="M3 11V9a4 4 0 014-4h14"/>
    //                     <path d="M7 23l-4-4 4-4"/>
    //                     <path d="M21 13v2a4 4 0 01-4 4H3"/>
    //                 </svg>
    //                 <h3>تحويل مخزون</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('inventory-adjustment')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M12 20h9"/>
    //                     <path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/>
    //                 </svg>
    //                 <h3>تسوية المخزون</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('minimum-stock')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
    //                     <path d="M12 11v6M8 11v6M16 11v6M4 11h16"/>
    //                 </svg>
    //                 <h3>الحد الأدنى للمخزون</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('expiry-report')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
    //                     <line x1="16" y1="2" x2="16" y2="6"/>
    //                     <line x1="8" y1="2" x2="8" y2="6"/>
    //                     <line x1="3" y1="10" x2="21" y2="10"/>
    //                     <path d="M8 14h8"/>
    //                 </svg>
    //                 <h3>تقرير الصلاحية</h3>
    //             </div>

    //             <div class="inventory-module" onclick="navigate('inventory-movement')">
    //                 <svg class="icon" viewBox="0 0 24 24">
    //                     <path d="M3 3v18h18"/>
    //                     <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"/>
    //                 </svg>
    //                 <h3>حركة المخزون</h3>
    //             </div>
    //         </div>
    //     </div>
    // `;
}

function showSalesInvoiceModule() {
    // This function is not defined in the provided code,
    // so it's left empty for now. You should implement the functionality for this function.
}
