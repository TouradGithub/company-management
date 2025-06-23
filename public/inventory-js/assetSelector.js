document.addEventListener('DOMContentLoaded', function() {
    // Get the select element
    const sellAssetSelect = document.getElementById('sellAssetSelect');
    const assetSelectContainer = document.createElement('div');
    assetSelectContainer.className = 'autocomplete-container';
    
    // Create text input for autocomplete
    const autocompleteInput = document.createElement('input');
    autocompleteInput.setAttribute('type', 'text');
    autocompleteInput.setAttribute('placeholder', 'اكتب اسم الأصل...');
    autocompleteInput.className = 'autocomplete-input';
    
    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'autocomplete-results';
    
    // Insert the new elements
    sellAssetSelect.parentNode.insertBefore(assetSelectContainer, sellAssetSelect);
    assetSelectContainer.appendChild(autocompleteInput);
    assetSelectContainer.appendChild(resultsContainer);
    assetSelectContainer.appendChild(sellAssetSelect);
    
    // Hide the original select
    sellAssetSelect.style.display = 'none';
    
    // Function to filter assets based on input
    function filterAssets(searchText) {
        resultsContainer.innerHTML = '';
        if (!searchText) {
            resultsContainer.style.display = 'none';
            return;
        }
        
        // Get all options from the select
        const options = Array.from(sellAssetSelect.options).slice(1); // Skip the first "select asset" option
        
        // Filter options based on input text
        const filteredOptions = options.filter(option => 
            option.textContent.toLowerCase().includes(searchText.toLowerCase())
        );
        
        // Display results
        if (filteredOptions.length > 0) {
            filteredOptions.forEach(option => {
                const resultItem = document.createElement('div');
                resultItem.className = 'autocomplete-item';
                resultItem.textContent = option.textContent;
                resultItem.dataset.value = option.value;
                
                resultItem.addEventListener('click', function() {
                    autocompleteInput.value = this.textContent;
                    sellAssetSelect.value = this.dataset.value;
                    
                    // Trigger the change event on the select
                    const event = new Event('change', { bubbles: true });
                    sellAssetSelect.dispatchEvent(event);
                    
                    resultsContainer.style.display = 'none';
                });
                
                resultsContainer.appendChild(resultItem);
            });
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.style.display = 'none';
        }
    }
    
    // Event listeners
    autocompleteInput.addEventListener('input', function() {
        filterAssets(this.value);
    });
    
    autocompleteInput.addEventListener('focus', function() {
        if (this.value) {
            filterAssets(this.value);
        }
    });
    
    // Close the dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!assetSelectContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });
    
    // Update autocomplete when assets list changes
    const originalUpdateAssetSelectList = window.updateAssetSelectList;
    if (originalUpdateAssetSelectList) {
        window.updateAssetSelectList = function() {
            originalUpdateAssetSelectList();
            autocompleteInput.value = '';
        };
    }
});