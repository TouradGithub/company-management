// Quick access drag & drop functionality
document.addEventListener('DOMContentLoaded', () => {
    const quickActionsContainer = document.querySelector('.quick-actions');
    
    // Make items draggable
    const actionButtons = document.querySelectorAll('.quick-action-btn:not(.add-new)');
    actionButtons.forEach(setupQuickActionButton);
    
    // Handle drag over container
    quickActionsContainer.addEventListener('dragover', (e) => {
        e.preventDefault();
        const draggable = document.querySelector('.dragging');
        if (!draggable) return;
        
        const afterElement = getDragAfterElement(quickActionsContainer, e.clientX);
        const addNewBtn = quickActionsContainer.querySelector('.add-new');
        
        if (afterElement) {
            if (afterElement !== addNewBtn) {
                quickActionsContainer.insertBefore(draggable, afterElement);
            }
        } else {
            // Insert before "add new" button
            quickActionsContainer.insertBefore(draggable, addNewBtn);
        }
    });
});

function setupQuickActionButton(btn) {
    if (!btn) return; // Guard clause for null elements
    
    btn.setAttribute('draggable', true);
    
    // Add remove button if it doesn't exist
    if (!btn.querySelector('.quick-action-remove')) {
        const removeBtn = document.createElement('button');
        removeBtn.className = 'quick-action-remove';
        removeBtn.innerHTML = `
            <svg viewBox="0 0 24 24" class="remove-icon">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        `;
        btn.appendChild(removeBtn);
        
        // Handle remove button click
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent triggering the button's navigate action
            btn.remove();
        });
    }
    
    btn.addEventListener('dragstart', () => {
        btn.classList.add('dragging');
    });
    
    btn.addEventListener('dragend', () => {
        btn.classList.remove('dragging');
    });
}

function getDragAfterElement(container, x) {
    const draggableElements = [...container.querySelectorAll('.quick-action-btn:not(.dragging):not(.add-new)')];
    
    if (!draggableElements.length) return null;
    
    return draggableElements.reduce((closest, child) => {
        const box = child.getBoundingClientRect();
        const offset = x - box.left - box.width / 2;
        
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
}