// Task management functionality
const taskStates = ['new', 'current', 'completed'];
let boardTasks = JSON.parse(localStorage.getItem('boardTasks')) || [];

function addNewBoardTask(event) {
    event.preventDefault();
    const title = document.getElementById('newTaskTitle').value;
    const date = document.getElementById('newTaskDate').value;
    const color = document.getElementById('newTaskColor').value;
    
    const task = {
        id: Date.now(),
        title,
        date,
        color,
        status: 'new'
    };
    
    boardTasks.push(task);
    saveBoardTasks();
    renderBoardTasks();
    
    // Reset form
    event.target.reset();
}

function deleteBoardTask(taskId) {
    boardTasks = boardTasks.filter(task => task.id !== taskId);
    saveBoardTasks();
    renderBoardTasks();
}

function changeTaskStatus(taskId) {
    const task = boardTasks.find(t => t.id === taskId);
    if (task) {
        const currentIndex = taskStates.indexOf(task.status);
        task.status = taskStates[(currentIndex + 1) % taskStates.length];
        saveBoardTasks();
        renderBoardTasks();
    }
}

function saveBoardTasks() {
    localStorage.setItem('boardTasks', JSON.stringify(boardTasks));
}

function renderBoardTasks() {
    const tasksGrid = document.getElementById('tasksGrid');
    tasksGrid.innerHTML = boardTasks.map(task => `
        <div class="task-card" style="background: ${task.color}">
            <div class="task-header">
                <span class="task-title">${task.title}</span>
                <span class="task-status status-${task.status}">
                    ${getStatusText(task.status)}
                </span>
            </div>
            <div class="task-date">${new Date(task.date).toLocaleDateString('ar-SA')}</div>
            <div class="task-actions">
                <button class="task-action-btn change-status-btn" onclick="changeTaskStatus(${task.id})">
                    تغيير الحالة
                </button>
                <button class="task-action-btn delete-task-btn" onclick="deleteBoardTask(${task.id})">
                    حذف
                </button>
            </div>
        </div>
    `).join('');
}

function getStatusText(status) {
    switch(status) {
        case 'new': return 'جديدة';
        case 'current': return 'حالية';
        case 'completed': return 'منتهية';
        default: return status;
    }
}

// Initialize tasks on page load
document.addEventListener('DOMContentLoaded', () => {
    renderBoardTasks();
});