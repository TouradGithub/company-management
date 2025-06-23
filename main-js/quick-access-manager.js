// Quick access management functionality
document.addEventListener('DOMContentLoaded', () => {
    const quickActionsContainer = document.querySelector('.quick-actions');
    if (!quickActionsContainer.querySelector('.add-new')) {
        // Add "Add New" button to quick access section if it doesn't exist
        const addNewButton = document.createElement('button');
        addNewButton.className = 'quick-action-btn add-new';
        addNewButton.innerHTML = `
            <svg class="icon" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            <span>إضافة وصول سريع</span>
        `;
        quickActionsContainer.appendChild(addNewButton);

        // Create and show quick access manager modal
        addNewButton.addEventListener('click', () => {
            const modal = document.createElement('div');
            modal.className = 'modal quick-access-modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <h2>إدارة الوصول السريع</h2>
                    <div class="quick-access-items">
                        ${searchableModules.map(module => `
                            <div class="quick-access-item" data-id="${module.id}">
                                <div class="item-content">
                                    <svg class="icon" viewBox="0 0 24 24">
                                        ${getIconPath(module.id)}
                                    </svg>
                                    <span>${module.title}</span>
                                </div>
                                <button class="add-to-quick-access">
                                    <svg viewBox="0 0 24 24" class="icon">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="16"/>
                                        <line x1="8" y1="12" x2="16" y2="12"/>
                                    </svg>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                    <button class="close-modal">×</button>
                </div>
            `;
            document.body.appendChild(modal);
            setupQuickAccessModal(modal);
        });
    }
});

function setupQuickAccessModal(modal) {
    const items = modal.querySelectorAll('.quick-access-item');
    items.forEach(item => {
        const addButton = item.querySelector('.add-to-quick-access');
        const moduleId = item.dataset.id;
        
        // Check if already in quick access
        const existingQuickAccess = document.querySelector(`.quick-action-btn[data-id="${moduleId}"]:not(.add-new)`);
        if (existingQuickAccess) {
            addButton.classList.add('active');
            addButton.innerHTML = `
                <svg viewBox="0 0 24 24" class="icon">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
            `;
        }
        
        addButton.addEventListener('click', () => {
            const isActive = addButton.classList.toggle('active');
            if (isActive) {
                addToQuickAccess(moduleId);
                addButton.innerHTML = `
                    <svg viewBox="0 0 24 24" class="icon">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                `;
            } else {
                removeFromQuickAccess(moduleId);
                addButton.innerHTML = `
                    <svg viewBox="0 0 24 24" class="icon">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                `;
            }
        });
    });
    
    setupModalClose(modal);
}

function getIconPath(moduleId) {
    // Return appropriate SVG path based on module ID
    const iconPaths = {
        'journal-entry': `
            <path d="M12 3v18M3 12h18M8 7h3M8 17h3M17 8v3M17 13v3"/>
        `,
        'sales-invoice': `
            <path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
            <path d="M16 2v4M8 2v4M2 8h20M12 12l4 4M16 12l-4 4"/>
        `,
        // Add more icon paths as needed
    };
    return iconPaths[moduleId] || '';
}

function addToQuickAccess(moduleId) {
    const module = searchableModules.find(m => m.id === moduleId);
    if (!module) return;
    
    const quickActionBtn = document.createElement('button');
    quickActionBtn.className = 'quick-action-btn';
    quickActionBtn.setAttribute('data-id', moduleId);
    quickActionBtn.onclick = () => navigate(moduleId);
    quickActionBtn.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24">
            ${getIconPath(moduleId)}
        </svg>
        <span>${module.title}</span>
    `;
    
    const addNewBtn = document.querySelector('.quick-action-btn.add-new');
    addNewBtn.parentNode.insertBefore(quickActionBtn, addNewBtn);
    
    // Setup the new button with drag and remove functionality
    setupQuickActionButton(quickActionBtn);
}

function removeFromQuickAccess(moduleId) {
    const quickActionBtn = document.querySelector(`.quick-action-btn[data-id="${moduleId}"]`);
    if (quickActionBtn) {
        quickActionBtn.remove();
    }
}

function setupQuickActionButton(button) {
    // You need to define this function to handle drag and remove functionality
    // For example:
    button.setAttribute('draggable', true);
    // Add event listeners for dragstart, dragover, drop, dragend
    // Add event listener for button click to remove from quick access
}

function setupModalClose(modal) {
    const closeModalButton = modal.querySelector('.close-modal');
    closeModalButton.addEventListener('click', () => {
        modal.remove();
    });
}