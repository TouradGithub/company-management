document.addEventListener('DOMContentLoaded', function() {
    // Check if we should load demo data
    if (!localStorage.getItem('assetsDataLoaded')) {
        // Generate sample assets
        generateSampleAssets();
        // Mark as loaded so we don't regenerate on every page load
        localStorage.setItem('assetsDataLoaded', 'true');
    }
    
    function generateSampleAssets() {
        // Get existing assets and categories
        const assets = window.getAssets ? window.getAssets() : [];
        const categories = window.getAssetCategories ? window.getAssetCategories() : [
            { name: 'المباني', code: 'building', lifespan: 25 },
            { name: 'السيارات', code: 'vehicle', lifespan: 5 },
            { name: 'المعدات والآلات', code: 'equipment', lifespan: 10 },
            { name: 'الأثاث', code: 'furniture', lifespan: 7 }
        ];
        
        // Sample assets data
        const sampleAssets = [
            {
                name: 'مبنى المقر الرئيسي',
                type: 'building',
                branch: 'الرئيسي',
                purchaseDate: '2018-03-15',
                originalCost: 2500000,
                salvageValue: 500000,
                usefulLife: 25,
                depreciations: []
            },
            {
                name: 'مبنى فرع الرياض',
                type: 'building',
                branch: 'فرع 1',
                purchaseDate: '2019-05-20',
                originalCost: 1800000,
                salvageValue: 400000,
                usefulLife: 25,
                depreciations: []
            },
            {
                name: 'سيارة مرسيدس S-Class',
                type: 'vehicle',
                branch: 'الرئيسي',
                purchaseDate: '2020-07-10',
                originalCost: 350000,
                salvageValue: 70000,
                usefulLife: 5,
                depreciations: []
            },
            {
                name: 'سيارة تويوتا هايلكس',
                type: 'vehicle',
                branch: 'فرع 2',
                purchaseDate: '2021-02-05',
                originalCost: 120000,
                salvageValue: 20000,
                usefulLife: 5,
                depreciations: []
            },
            {
                name: 'أجهزة كمبيوتر مكتبية',
                type: 'equipment',
                branch: 'الرئيسي',
                purchaseDate: '2022-01-15',
                originalCost: 85000,
                salvageValue: 5000,
                usefulLife: 3,
                depreciations: []
            },
            {
                name: 'آلة تصوير مستندات',
                type: 'equipment',
                branch: 'فرع 1',
                purchaseDate: '2022-03-20',
                originalCost: 45000,
                salvageValue: 3000,
                usefulLife: 5,
                depreciations: []
            },
            {
                name: 'أثاث مكتبي للطابق الأول',
                type: 'furniture',
                branch: 'الرئيسي',
                purchaseDate: '2021-09-10',
                originalCost: 120000,
                salvageValue: 10000,
                usefulLife: 7,
                depreciations: []
            },
            {
                name: 'مكاتب وكراسي للموظفين',
                type: 'furniture',
                branch: 'فرع 2',
                purchaseDate: '2022-04-18',
                originalCost: 75000,
                salvageValue: 7500,
                usefulLife: 7,
                depreciations: []
            },
            {
                name: 'سيارة هيونداي سوناتا',
                type: 'vehicle',
                branch: 'فرع 1',
                purchaseDate: '2020-11-25',
                originalCost: 95000,
                salvageValue: 15000,
                usefulLife: 5,
                depreciations: []
            },
            {
                name: 'معدات إنتاج خط 1',
                type: 'equipment',
                branch: 'فرع 2',
                purchaseDate: '2019-08-12',
                originalCost: 320000,
                salvageValue: 40000,
                usefulLife: 10,
                depreciations: []
            }
        ];
        
        // Add depreciation entries for assets older than 1 year
        const currentYear = new Date().getFullYear();
        
        sampleAssets.forEach((asset, index) => {
            // Add ID to each asset
            asset.id = index + 1;
            
            // Calculate depreciation for past years
            const purchaseYear = new Date(asset.purchaseDate).getFullYear();
            const yearsToDepreciate = Math.min(
                currentYear - purchaseYear,
                asset.usefulLife
            );
            
            const annualDepreciation = (asset.originalCost - asset.salvageValue) / asset.usefulLife;
            
            // Add depreciation entries for past years
            for (let i = 0; i < yearsToDepreciate; i++) {
                const year = purchaseYear + i;
                
                // Add some variation to depreciation amounts (±10%)
                const variation = 0.9 + Math.random() * 0.2; // Between 0.9 and 1.1
                const depreciationAmount = Math.round(annualDepreciation * variation);
                
                asset.depreciations.push({
                    year: year,
                    value: depreciationAmount
                });
            }
            
            // Add to assets array
            assets.push(asset);
        });
        
        // Add one sold asset as an example
        if (assets.length > 0) {
            const soldAssetIndex = 3; // Example: sell the 4th asset (Toyota Hilux)
            if (assets.length > soldAssetIndex) {
                const soldAsset = assets[soldAssetIndex];
                const totalDepreciation = soldAsset.depreciations.reduce((sum, d) => sum + d.value, 0);
                const bookValue = soldAsset.originalCost - totalDepreciation;
                
                // Set sale date to 3 months ago
                const saleDate = new Date();
                saleDate.setMonth(saleDate.getMonth() - 3);
                const saleDateStr = saleDate.toISOString().split('T')[0];
                
                // Sale amount with a small profit
                const saleAmount = bookValue * 1.05;
                const gainOrLoss = saleAmount - bookValue;
                
                soldAsset.sold = true;
                soldAsset.saleDetails = {
                    date: saleDateStr,
                    amount: saleAmount,
                    gainOrLoss: gainOrLoss
                };
            }
        }
        
        // Update UI if available
        if (window.updateDepreciationTable) window.updateDepreciationTable();
        if (window.updateAnnualDepreciationTable) window.updateAnnualDepreciationTable();
        if (window.updateAssetSelectList) window.updateAssetSelectList();
    }
});