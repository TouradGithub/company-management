// Task Manager functionality
let tasks = {
    current: [],
    new: [],
    completed: []
};

// Load tasks from localStorage
function loadTasks() {
    const savedTasks = localStorage.getItem('taskManager');
    if (savedTasks) {
        tasks = JSON.parse(savedTasks);
    }
}

// Save tasks to localStorage
function saveTasks() {
    localStorage.setItem('taskManager', JSON.stringify(tasks));
}

function setupTaskManager(taskWindow) {
    // First create the modal content
    taskWindow.innerHTML = `
        <div class="modal-content">
            <div class="task-manager">
                <div class="task-header">
                    <h2>إدارة المهام</h2>
                    <div class="task-input">
                        <input type="text" id="taskInput" placeholder="أضف مهمة جديدة">
                        <button id="addTask" class="add-task-btn">
                            <svg viewBox="0 0 24 24" class="task-icon">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            إضافة
                        </button>
                    </div>
                </div>
                
                <div class="tasks-container">
                    <div class="task-column">
                        <h3>المهام الجديدة</h3>
                        <div class="task-list new-tasks"></div>
                    </div>
                    
                    <div class="task-column">
                        <h3>المهام الحالية</h3>
                        <div class="task-list current-tasks"></div>
                    </div>
                    
                    <div class="task-column">
                        <h3>المهام المنتهية</h3>
                        <div class="task-list completed-tasks"></div>
                    </div>
                </div>
            </div>
            <button class="close-modal">×</button>
        </div>
    `;

    // Then load tasks and set up event handlers
    loadTasks();
    
    const input = taskWindow.querySelector('#taskInput');
    const addButton = taskWindow.querySelector('#addTask');

    function renderTasks() {
        const lists = {
            new: taskWindow.querySelector('.new-tasks'),
            current: taskWindow.querySelector('.current-tasks'),
            completed: taskWindow.querySelector('.completed-tasks')
        };

        Object.keys(lists).forEach(status => {
            lists[status].innerHTML = tasks[status].map((task, index) => `
                <div class="task-item" draggable="true" data-status="${status}" data-index="${index}">
                    <div class="task-content">
                        <div class="task-checkbox">
                            <input type="checkbox" ${status === 'completed' ? 'checked' : ''}>
                        </div>
                        <span class="task-text">${task.text}</span>
                        <span class="task-date">${new Date(task.date).toLocaleDateString('ar-SA')}</span>
                    </div>
                    <div class="task-actions">
                        <button class="delete-task" onclick="deleteTask('${status}', ${index})">
                            <svg viewBox="0 0 24 24" class="task-icon">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');

            setupDragAndDrop(lists[status]);
        });
    }

    function setupDragAndDrop(container) {
        container.addEventListener('dragstart', (e) => {
            e.target.classList.add('dragging');
        });

        container.addEventListener('dragend', (e) => {
            e.target.classList.remove('dragging');
        });

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            const draggable = taskWindow.querySelector('.dragging');
            const afterElement = getDragAfterElement(container, e.clientY);
            
            if (afterElement) {
                container.insertBefore(draggable, afterElement);
            } else {
                container.appendChild(draggable);
            }
        });
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.task-item:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    window.deleteTask = (status, index) => {
        tasks[status].splice(index, 1);
        saveTasks();
        renderTasks();
    };

    addButton.onclick = () => {
        const text = input.value.trim();
        if (text) {
            tasks.new.push({
                text,
                date: new Date().toISOString(),
                priority: 'normal'
            });
            saveTasks();
            input.value = '';
            renderTasks();
        }
    };

    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            addButton.click();
        }
    });

    renderTasks();
}