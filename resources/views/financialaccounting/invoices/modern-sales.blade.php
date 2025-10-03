@extends('financialaccounting.layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/modern-invoice-simple.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-invoice-stable.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Select2 CSS الإصدار الأحدث -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* CSS مضمن للتأكد من ظهور التنسيقات */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .modern-invoice-container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 20px !important;
            background: transparent !important;
            min-height: 100vh !important;
        }

        /* رأس الفاتورة */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 2rem !important;
            border-radius: 16px !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3) !important;
        }

        .invoice-title h1 {
            font-size: 2rem !important;
            margin: 0 0 1rem 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
            color: white !important;
        }

        .invoice-number {
            background: rgba(255, 255, 255, 0.2) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-weight: 600 !important;
            backdrop-filter: blur(10px) !important;
            color: white !important;
        }

        /* الإحصائيات السريعة */
        .quick-stats {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
            gap: 1.5rem !important;
            margin-bottom: 2rem !important;
        }

        .stat-card {
            background: white !important;
            padding: 1.5rem !important;
            border-radius: 12px !important;
            text-align: center !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            transition: transform 0.3s ease !important;
            border: 1px solid #e5e7eb !important;
        }

        .stat-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .stat-icon {
            font-size: 2rem !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        .stat-number {
            font-size: 1.8rem !important;
            font-weight: bold !important;
            color: #667eea !important;
            margin: 0.5rem 0 !important;
        }

        .stat-label {
            color: #64748b !important;
            font-size: 0.875rem !important;
            margin: 0 !important;
        }

        /* الأقسام الرئيسية */
        .invoice-info-section,
        .smart-search-container,
        .items-section,
        .invoice-summary {
            background: white !important;
            padding: 2rem !important;
            border-radius: 12px !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid #e5e7eb !important;
        }

        /* شبكة المعلومات الأنيقة */
        .info-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
            gap: 2rem !important;
        }

        .info-card-elegant {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%) !important;
            padding: 0 !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.12) !important;
            border: 2px solid transparent !important;
            overflow: hidden !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
        }

        .info-card-elegant::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            height: 4px !important;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%) !important;
        }

        .info-card-elegant:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 16px 48px rgba(102, 126, 234, 0.2) !important;
            border-color: rgba(102, 126, 234, 0.3) !important;
        }

        .card-icon-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            width: 100% !important;
            padding: 1.5rem !important;
            text-align: center !important;
            font-size: 1.5rem !important;
        }

        .card-content {
            padding: 1.5rem !important;
        }

        /* المدخلات الأنيقة */
        .elegant-label {
            display: block !important;
            margin-bottom: 1rem !important;
            font-weight: 600 !important;
            color: #667eea !important;
            font-size: 0.95rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .elegant-input,
        .elegant-select {
            width: 100% !important;
            padding: 1rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            background: #f8fafc !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            color: #374151 !important;
        }

        .elegant-input:focus,
        .elegant-select:focus {
            outline: none !important;
            border-color: #667eea !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
            transform: scale(1.02) !important;
        }

        /* تخصيص Select2 - نسخة محسنة */
        .select2-container .select2-selection--single {
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            height: 56px !important;
            background: #f8fafc !important;
            transition: all 0.3s ease !important;
        }

        .select2-container .select2-selection__rendered {
            padding: 0.5rem 1rem !important;
            color: #374151 !important;
            font-weight: 500 !important;
            line-height: 2.5 !important;
        }

        .select2-container--focus .select2-selection--single {
            border-color: #667eea !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
        }

        .select2-dropdown {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.25) !important;
            overflow: hidden !important;
        }

        .select2-results__option {
            padding: 1rem 1.5rem !important;
            transition: all 0.2s !important;
            border-bottom: 1px solid rgba(102, 126, 234, 0.08) !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%) !important;
            color: #667eea !important;
        }

        /* إصلاح مشكلة الـ rtl مع Select2 */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            left: 1px !important;
            right: auto !important;
        }

        /* البحث الذكي */
        .smart-search-container {
            position: relative !important;
        }

        .search-icon {
            position: absolute !important;
            left: 1rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #9ca3af !important;
            z-index: 1 !important;
        }

        .smart-search-input {
            width: 100% !important;
            padding: 1rem 1rem 1rem 3rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            font-size: 1rem !important;
            transition: all 0.3s !important;
        }

        .smart-search-input:focus {
            outline: none !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
        }

        .search-results {
            position: absolute !important;
            top: calc(100% + 8px) !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.25) !important;
            max-height: 450px !important;
            overflow-y: auto !important;
            z-index: 9999 !important;
            display: none;
            backdrop-filter: blur(10px) !important;
        }

        .search-results.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 9999 !important;
            position: absolute !important;
        }

        /* إجبار ظهور النتائج - CSS إضافي للتأكد */
        #searchResults.show {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .search-result-item {
            padding: 1.25rem !important;
            border-bottom: 1px solid rgba(102, 126, 234, 0.08) !important;
            cursor: pointer !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
        }

        .search-result-item:hover {
            background: linear-gradient(135deg, #667eea05 0%, #764ba205 100%) !important;
            transform: translateX(8px) !important;
            padding-right: 2rem !important;
        }

        .search-result-item:last-child {
            border-bottom: none !important;
            border-radius: 0 0 16px 16px !important;
        }

        .search-result-item:first-child {
            border-radius: 16px 16px 0 0 !important;
        }

        .product-icon {
            width: 48px !important;
            height: 48px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-radius: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1.2rem !important;
            color: white !important;
            flex-shrink: 0 !important;
        }

        .product-info {
            flex: 1 !important;
        }

        .product-name {
            font-weight: 600 !important;
            color: #374151 !important;
            font-size: 1rem !important;
            margin-bottom: 0.25rem !important;
        }

        .product-price {
            color: #667eea !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            margin-bottom: 0.25rem !important;
        }

        .product-stock {
            color: #10b981 !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
        }

        .add-icon {
            width: 36px !important;
            height: 36px !important;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .search-result-item:hover .add-icon,
        .search-result-item.keyboard-selected .add-icon {
            transform: scale(1.1) rotate(180deg) !important;
        }

        .search-result-item.keyboard-selected {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%) !important;
            border-left: 4px solid #667eea !important;
            transform: translateX(8px) !important;
            padding-right: 2rem !important;
        }

        /* جدول الأصناف وأزرار التحكم */
        .items-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 1.5rem !important;
        }

        /* أزرار التحكم في الكمية */
        .quantity-controls {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            justify-content: center !important;
        }

        .qty-btn {
            width: 32px !important;
            height: 32px !important;
            border: none !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }

        .plus-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .plus-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: translateY(-2px) scale(1.05) !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4) !important;
        }

        .plus-btn:active {
            transform: translateY(0) scale(0.95) !important;
        }

        .minus-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .minus-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
            transform: translateY(-2px) scale(1.05) !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
        }

        .minus-btn:active {
            transform: translateY(0) scale(0.95) !important;
        }

        .btn-pulse {
            animation: pulse 0.2s ease !important;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* تحكم السعر */
        .price-controls {
            display: flex !important;
            align-items: center !important;
            gap: 0.25rem !important;
            justify-content: center !important;
        }

        /* زر الحذف المحسن */
        .delete-btn {
            min-width: 70px !important;
            height: 34px !important;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            gap: 4px !important;
            padding: 0 8px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .delete-btn::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent) !important;
            transition: left 0.6s ease !important;
        }

        .delete-btn:hover::before {
            left: 100% !important;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4) !important;
        }

        .delete-btn:active {
            transform: translateY(0) scale(0.98) !important;
        }

        .delete-btn .btn-text {
            opacity: 0 !important;
            transition: opacity 0.2s ease !important;
        }

        .delete-btn:hover .btn-text {
            opacity: 1 !important;
        }

        /* صف العربة */
        .cart-item-row {
            transition: all 0.3s ease !important;
            border-left: 4px solid transparent !important;
        }

        .cart-item-row:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-left-color: #667eea !important;
        }

        .cart-item-row.updating {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
            border-left-color: #3b82f6 !important;
        }

        .cart-item-row.removing {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            border-left-color: #ef4444 !important;
        }

        /* رسالة العربة الفارغة المحسنة */
        .empty-cart-message {
            text-align: center !important;
            padding: 4rem 2rem !important;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
            border-radius: 16px !important;
            border: 2px dashed #cbd5e1 !important;
            margin: 2rem 0 !important;
        }

        .empty-cart-icon {
            width: 80px !important;
            height: 80px !important;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%) !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto 1.5rem auto !important;
            animation: float 3s ease-in-out infinite !important;
        }

        .empty-cart-icon i {
            font-size: 2rem !important;
            color: #64748b !important;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .empty-cart-message h3 {
            color: #374151 !important;
            margin-bottom: 0.5rem !important;
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }

        .empty-cart-message p {
            color: #64748b !important;
            margin-bottom: 1rem !important;
            font-size: 1rem !important;
        }

        .empty-cart-actions {
            background: white !important;
            padding: 1.5rem !important;
            border-radius: 12px !important;
            margin-top: 1.5rem !important;
            border: 1px solid #e5e7eb !important;
        }

        .empty-cart-actions p {
            margin-bottom: 0.5rem !important;
            font-size: 0.9rem !important;
        }

        .items-counter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3) !important;
        }

        .items-counter.counter-update {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.5) !important;
        }

        .quantity-display.counter-update {
            transform: scale(1.1) !important;
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
            color: #1e40af !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
        }

        .modern-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 1rem !important;
            background: white !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
        }

        .modern-table th,
        .modern-table td {
            padding: 1rem 0.75rem !important;
            text-align: right !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle !important;
        }

        .modern-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            font-weight: 600 !important;
            color: white !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none !important;
        }

        .table-input {
            width: 80px !important;
            padding: 0.5rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            text-align: center !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }

        .table-input:focus {
            outline: none !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        }

        .quantity-input {
            width: 60px !important;
            margin: 0 0.25rem !important;
        }

        .price-input {
            width: 80px !important;
        }

        /* الأزرار */
        .action-buttons {
            display: flex !important;
            gap: 1rem !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            margin-top: 2rem !important;
        }

        .modern-btn {
            padding: 0.75rem 1.5rem !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
        }

        .btn-primary {
            background: #667eea !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: #5a67d8 !important;
            transform: translateY(-2px) !important;
        }

        .btn-success {
            background: #10b981 !important;
            color: white !important;
        }

        .btn-success:hover {
            background: #059669 !important;
            transform: translateY(-2px) !important;
        }

        .btn-info {
            background: #3b82f6 !important;
            color: white !important;
        }

        .btn-info:hover {
            background: #2563eb !important;
            transform: translateY(-2px) !important;
        }

        .btn-outline {
            background: white !important;
            color: #667eea !important;
            border: 2px solid #667eea !important;
        }

        .btn-outline:hover {
            background: #667eea !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }

        /* ملخص الفاتورة */
        .summary-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 2rem !important;
            align-items: start !important;
        }

        .summary-calculations {
            background: #f8fafc !important;
            padding: 1.5rem !important;
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
        }

        .calc-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 1rem !important;
            padding-bottom: 0.5rem !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .calc-row:last-child {
            margin-bottom: 0 !important;
            border-bottom: 2px solid #667eea !important;
            padding-bottom: 1rem !important;
            font-weight: 700 !important;
        }

        .calc-input {
            width: 80px !important;
            padding: 0.25rem 0.5rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 4px !important;
            text-align: center !important;
        }

        /* الإشعارات */
        .notification {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            padding: 1rem 1.5rem !important;
            border-radius: 8px !important;
            color: white !important;
            font-weight: 600 !important;
            z-index: 9999 !important;
            transform: translateX(400px) !important;
            transition: transform 0.3s ease !important;
        }

        .notification.show {
            transform: translateX(0) !important;
        }

        .notification.success {
            background: #10b981 !important;
        }

        .notification.error {
            background: #ef4444 !important;
        }

        .notification.info {
            background: #3b82f6 !important;
        }

        /* تجاوبية */
        @media (max-width: 768px) {
            .modern-invoice-container {
                padding: 1rem !important;
            }

            .invoice-header {
                padding: 1.5rem !important;
            }

            .invoice-title h1 {
                font-size: 1.5rem !important;
            }

            .quick-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
            }

            .info-grid {
                grid-template-columns: 1fr !important;
            }

            .summary-grid {
                grid-template-columns: 1fr !important;
            }

            .action-buttons {
                flex-direction: column !important;
            }
        }
    </style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modern-invoice-container">
    <!-- رأس الفاتورة -->
    <div class="invoice-header">
        <div class="invoice-title">
            <h1>
                <i class="fas fa-receipt"></i>
                فاتورة مبيعات جديدة
            </h1>
            <div class="invoice-number">
                <i class="fas fa-hashtag"></i>
                <span id="invoiceNumber">INV-{{ date('Ymd') }}-001</span>
            </div>
        </div>
        <p style="margin: 0; opacity: 0.9;">إنشاء وإدارة فاتورة مبيعات بطريقة عصرية وذكية</p>
    </div>

    <!-- الإحصائيات السريعة -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">📈</div>
            <div class="stat-number" id="todayStats">{{ $stats['today_invoices'] }}</div>
            <div class="stat-label">فواتير اليوم</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number" id="monthStats">{{ $stats['month_invoices'] }}</div>
            <div class="stat-label">فواتير الشهر</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-number" id="totalAmount">{{ number_format($stats['total_amount'], 2) }}</div>
            <div class="stat-label">إجمالي المبيعات (ر.س)</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">�</div>
            <div class="stat-number" id="customersCount">{{ $stats['customers_count'] }}</div>
            <div class="stat-label">عدد العملاء</div>
        </div>
    </div>

    <form id="modernSalesInvoiceForm">
        <!-- معلومات الفاتورة الأساسية -->
        <div class="invoice-info-section">
            <h3 style="margin-bottom: 1.5rem; color: var(--dark);">
                <i class="fas fa-info-circle"></i> معلومات الفاتورة
            </h3>

            <div class="info-grid">
                <!-- تاريخ الفاتورة -->
                <div class="info-card-elegant">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="card-content">
                        <label class="elegant-label">📅 تاريخ الفاتورة</label>
                        <input type="date" class="elegant-input" id="invoiceDate" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- الفرع -->
                <div class="info-card-elegant">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-content">
                        <label class="elegant-label">🏢 الفرع</label>
                        <select class="elegant-select" id="branchSelect">
                            <option value="">اختر الفرع...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- العميل -->
                <div class="info-card-elegant">
                    <div class="card-icon-wrapper">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="card-content">
                        <label class="elegant-label">👤 العميل</label>
                        <select class="elegant-select customers" id="customerSelect" style="width: 100%;">
                            <option value="">اختر العميل...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- البحث الذكي -->
        <div class="smart-search-container">
            <div class="search-header">
                <h3 style="margin: 0; color: #667eea; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-search"></i>
                    البحث عن المنتجات
                </h3>
                <p style="margin: 0.5rem 0 0 0; color: #64748b; font-size: 0.875rem;">ابحث عن المنتجات المطلوبة وأضفها للفاتورة</p>
            </div>
            <div style="position: relative; margin-top: 1rem;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="smart-search-input" id="smartSearch"
                       placeholder="🔍 اكتب اسم المنتج... (مثال: لابتوب، جوال، كتاب)">
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>

        <!-- قسم الأصناف -->
        <div class="items-section">
            <div class="items-header">
                <h3>
                    <i class="fas fa-shopping-cart"></i>
                    عربة التسوق
                </h3>
                <div class="items-counter">
                    <span id="itemsCount">0</span> صنف
                </div>
            </div>

            <table class="modern-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">م</th>
                        <th>📦 الصنف</th>
                        <th style="width: 100px;">📏 الوحدة</th>
                        <th style="width: 100px;">🔢 الكمية</th>
                        <th style="width: 120px;">💰 السعر</th>
                        <th style="width: 120px;">💵 الإجمالي</th>
                        <th style="width: 80px;">حذف</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                </tbody>
            </table>

            <!-- رسالة فارغة محسنة -->
            <div id="emptyMessage" class="empty-cart-message">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>عربة التسوق فارغة</h3>
                <p>لم يتم إضافة أي منتجات بعد</p>
                <div class="empty-cart-actions">
                    <p>💡 <strong>ابدأ بالبحث عن المنتجات</strong> في الأعلى</p>
                    <p>🛍️ أو استخدم زر "<strong>من الكتالوج</strong>" أدناه</p>
                </div>
            </div>
        </div>

        <!-- أزرار الإجراءات السريعة -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-outline" onclick="openCatalog()">
                <i class="fas fa-book"></i>
                من الكتالوج
            </button>
            <button type="button" class="modern-btn btn-outline" onclick="addNewClient('sales')">
                <i class="fas fa-user-plus"></i>
                عميل جديد
            </button>
        </div>

        <!-- ملخص الفاتورة -->
        <div class="invoice-summary">
            <div class="summary-grid">
                <div>
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">
                        <i class="fas fa-calculator"></i>
                        ملخص الفاتورة
                    </h3>
                    <p style="color: #64748b; margin: 0;">مراجعة المبالغ والحسابات النهائية</p>
                </div>

                <div class="summary-calculations">
                    <div class="calc-row">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotal">0.00 ر.س</span>
                    </div>
                    <div class="calc-row">
                        <span>الخصم:</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" class="calc-input" id="discountPercent" value="0" step="0.01" min="0" max="100">
                            <span>%</span>
                            <span id="discountAmount">0.00 ر.س</span>
                        </div>
                    </div>
                    <div class="calc-row">
                        <span>الضريبة:</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" class="calc-input" id="taxPercent" value="15" step="0.01" min="0" max="100">
                            <span>%</span>
                            <span id="taxAmount">0.00 ر.س</span>
                        </div>
                    </div>
                    <div class="calc-row">
                        <span>🔥 الإجمالي النهائي:</span>
                        <span id="grandTotal" style="color: var(--success); font-size: 1.5rem;">0.00 ر.س</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ والإجراءات -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-primary" id="saveInvoiceBtn">
                <i class="fas fa-save"></i>
                <span class="loading-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                حفظ الفاتورة
            </button>
            <button type="button" class="modern-btn btn-success" onclick="window.print()">
                <i class="fas fa-print"></i>
                طباعة
            </button>
            <button type="button" class="modern-btn btn-outline" onclick="resetForm()">
                <i class="fas fa-undo"></i>
                إعادة تعيين
            </button>
        </div>
    </form>
</div>

<!-- إشعارات النجاح/الخطأ -->
<div id="notification" class="notification"></div>

@include('financialaccounting.invoices.modal')
@endsection

@section('js')
<script>
// متغيرات عامة
let itemCounter = 0;
let invoiceItems = [];
let allProducts = [];
let allCustomers = [];

// تهيئة الصفحة
$(document).ready(function() {
    initializeInvoice();
    setupEventListeners();
    loadProductsAndCustomers();
    console.log('🎨 تم تحميل فاتورة المبيعات العصرية بنجاح');

    // إتاحة دوال الاختبار في وحدة التحكم
    window.testSearch = testSearch;
    window.allProducts = () => allProducts;
    window.allCustomers = () => allCustomers;
    window.forceShowResults = forceShowSearchResults;
    window.debugSearch = debugSearchContainer;
    window.testCustomers = function() {
        console.log('🧪 اختبار العملاء:');
        console.log('📊 عدد العملاء:', allCustomers.length);
        console.log('👥 جميع العملاء:', allCustomers);
        console.log('🔍 عنصر customerSelect:', $('#customerSelect').length);
        console.log('📋 خيارات customerSelect:', $('#customerSelect').find('option').length);
        loadCustomersInSelect();
        return allCustomers;
    };

    // اختبار تلقائي بعد 3 ثوان
    setTimeout(() => {
        console.log('🔍 اختبار تلقائي للبيانات:');
        testSearch();

        if (allProducts.length > 0) {
            console.log('✅ البيانات جاهزة للبحث');
            console.log('💡 جرب البحث في الحقل أو استخدم testSearch() في وحدة التحكم');
        } else {
            console.log('⚠️ لا توجد بيانات للبحث');
            console.log('💡 تأكد من وجود منتجات في قاعدة البيانات');
        }
    }, 3000);
});

// تهيئة الفاتورة
function initializeInvoice() {
    // التأكد من تحميل Select2 قبل التهيئة
    if (typeof $.fn.select2 === 'undefined') {
        console.error('❌ Select2 غير محمل! جاري تحميله...');

        // تحميل Select2 ديناميكياً
        $('<link>')
            .attr('rel', 'stylesheet')
            .attr('href', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css')
            .appendTo('head');

        $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', function() {
            console.log('✅ تم تحميل Select2 ديناميكياً');
            initializeSelect2Elements();
        });
    } else {
        initializeSelect2Elements();
    }

    // إظهار حالة التحميل
    showNotification('🔄 جاري تحميل البيانات...', 'info');

    // تحديث رقم الفاتورة
    updateInvoiceNumber();

    // تحديث العداد
    updateItemsCounter();

    // إضافة مؤشر حالة البحث
    addSearchStatusIndicator();
}

// دالة منفصلة لتهيئة Select2
function initializeSelect2Elements() {
    try {
        // تهيئة Select2 للعملاء بإعدادات مبسطة
        $('#customerSelect').select2({
            placeholder: '🔍 ابحث عن العميل...',
            allowClear: true,
            width: '100%'
        });

        // تهيئة Select2 للفرع بإعدادات مبسطة
        $('#branchSelect').select2({
            placeholder: '🏢 اختر الفرع...',
            allowClear: true,
            width: '100%'
        });

        console.log('✅ تم تهيئة Select2 بنجاح');

        // إعادة تحميل العملاء في Select2 إذا كانوا محملين مسبقاً
        if (allCustomers && allCustomers.length > 0) {
            console.log('🔄 إعادة تحميل العملاء في Select2...');
            loadCustomersInSelect();
        }
    } catch (error) {
        console.error('❌ خطأ في تهيئة Select2:', error);
        // استخدام عناصر select عادية في حالة فشل Select2
        $('#customerSelect, #branchSelect').removeClass('select2-hidden-accessible');
    }
}

// إضافة مؤشر حالة البحث
function addSearchStatusIndicator() {
    const searchContainer = $('#smartSearch').parent();
    if (!searchContainer.find('.search-status').length) {
        searchContainer.append(`
            <div class="search-status" style="
                position: absolute;
                right: 3rem;
                top: 50%;
                transform: translateY(-50%);
                color: #64748b;
                font-size: 0.8rem;
                z-index: 2;
            ">
                <span id="productsStatus">تحميل...</span>
            </div>
        `);
    }

    // تحديث الحالة كل ثانية
    const statusInterval = setInterval(() => {
        const statusEl = $('#productsStatus');
        if (allProducts && allProducts.length > 0) {
            statusEl.html(`✅ ${allProducts.length} منتج`).css('color', '#10b981');
            clearInterval(statusInterval);
        } else {
            statusEl.html('⏳ تحميل...').css('color', '#f59e0b');
        }
    }, 1000);

    // إيقاف المؤشر بعد 10 ثوان
    setTimeout(() => clearInterval(statusInterval), 10000);
}

// تحميل البيانات من الخادم
function loadProductsAndCustomers() {
    // إعداد CSRF token للطلبات
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('🚀 بدء تحميل البيانات...');

    // تحميل المنتجات
    $.ajax({
        url: '/products/fetch',
        method: 'GET',
        timeout: 10000, // مهلة زمنية 10 ثوان
        success: function(response) {
            console.log('📦 استجابة المنتجات:', response);
            allProducts = response.data || response;
            console.log('✅ تم تحميل المنتجات:', allProducts.length);

            if (!allProducts || allProducts.length === 0) {
                showNotification('⚠️ لا توجد منتجات متاحة للبحث', 'info');
                console.log('📝 نصيحة: تأكد من إضافة منتجات في إدارة المخزون');
            } else {
                showNotification(`✅ تم تحميل ${allProducts.length} منتج`, 'success');
                // إظهار أسماء أول 3 منتجات للتأكيد
                if (allProducts.length > 0) {
                    console.log('🔍 أول 3 منتجات:', allProducts.slice(0, 3).map(p => ({
                        id: p.id,
                        name: p.name,
                        price: p.price,
                        stock: p.stock
                    })));
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('❌ خطأ في تحميل المنتجات:');
            console.error('- الحالة:', status);
            console.error('- الخطأ:', error);
            console.error('- كود الاستجابة:', xhr.status);
            console.error('- نص الاستجابة:', xhr.responseText);
            console.error('- JSON الاستجابة:', xhr.responseJSON);

            let errorMsg = '❌ خطأ في تحميل المنتجات';
            if (xhr.status === 0) {
                errorMsg += ' - تحقق من الاتصال بالإنترنت';
            } else if (xhr.status === 404) {
                errorMsg += ' - الصفحة غير موجودة';
            } else if (xhr.status === 500) {
                errorMsg += ' - خطأ في الخادم';
            } else if (xhr.responseJSON?.message) {
                errorMsg += ': ' + xhr.responseJSON.message;
            }

            showNotification(errorMsg, 'error');
            allProducts = [];
        }
    });

    // تحميل العملاء - جربة عدة routes مختلفة
    const customerRoutes = ['/customers/get', '/api/customers', '/customers', '/customer'];
    let currentRouteIndex = 0;

    function tryLoadCustomers() {
        if (currentRouteIndex >= customerRoutes.length) {
            console.error('❌ فشل في تحميل العملاء من جميع الروابط المحتملة');
            showNotification('❌ لا يمكن تحميل العملاء - تحقق من الإعدادات', 'error');
            return;
        }

        const url = customerRoutes[currentRouteIndex];
        console.log(`🔄 محاولة تحميل العملاء من: ${url}`);

        $.ajax({
            url: url,
            method: 'GET',
            timeout: 10000,
        success: function(response) {
            console.log('👥 استجابة العملاء الكاملة:', response);
            console.log('👥 نوع الاستجابة:', typeof response);

            // تجربة طرق مختلفة لاستخراج البيانات
            if (response.data && Array.isArray(response.data)) {
                allCustomers = response.data;
                console.log('✅ تم العثور على العملاء في response.data');
            } else if (Array.isArray(response)) {
                allCustomers = response;
                console.log('✅ تم العثور على العملاء في response مباشرة');
            } else if (response.customers && Array.isArray(response.customers)) {
                allCustomers = response.customers;
                console.log('✅ تم العثور على العملاء في response.customers');
            } else {
                allCustomers = [];
                console.warn('⚠️ لم يتم العثور على مصفوفة العملاء في الاستجابة');
            }

            console.log('📊 عدد العملاء المحملين:', allCustomers.length);
            if (allCustomers.length > 0) {
                console.log('👤 أول عميل:', allCustomers[0]);
            }

            loadCustomersInSelect();

            if (allCustomers.length === 0) {
                showNotification('⚠️ لا يوجد عملاء - يمكنك إضافة عميل جديد', 'info');
            } else {
                showNotification(`✅ تم تحميل ${allCustomers.length} عميل`, 'success');
            }
        },
        error: function(xhr, status, error) {
                console.error(`❌ خطأ في تحميل العملاء من ${url}:`);
                console.error('- الحالة:', status);
                console.error('- الخطأ:', error);
                console.error('- كود الاستجابة:', xhr.status);

                // جربة الرابط التالي
                currentRouteIndex++;
                setTimeout(tryLoadCustomers, 500);
            }
        });
    }

    // بدء محاولة تحميل العملاء
    tryLoadCustomers();
}

// دوال مساعدة لإدارة النتائج
function hideSearchResults() {
    console.log('🙈 إخفاء النتائج');
    $('#searchResults').removeClass('show').hide();
}

function showSearchResultsContainer() {
    console.log('👀 إظهار النتائج');
    const $results = $('#searchResults');

    // إزالة أي تداخلات CSS
    $results.removeAttr('style');

    // إضافة الفئة وإظهار العنصر
    $results.addClass('show').show();

    // فحص فوري
    setTimeout(() => {
        console.log('🔍 فحص سريع - مرئي:', $results.is(':visible'));
        console.log('🔍 فحص سريع - له فئة show:', $results.hasClass('show'));

        if (!$results.is(':visible')) {
            console.log('🚨 إجبار الظهور مباشرة');
            $results.css({
                'display': 'block !important',
                'visibility': 'visible !important',
                'position': 'absolute',
                'z-index': '99999',
                'background': 'white',
                'border': '2px solid red', // لون تجريبي
                'top': '100%',
                'left': '0',
                'right': '0'
            });
        }
    }, 50);
}

// إضافة دالة لاختبار البحث اليدوي
function testSearch() {
    console.log('🧪 اختبار البحث:');
    console.log('عدد المنتجات:', allProducts.length);
    if (allProducts.length > 0) {
        console.log('أول منتج:', allProducts[0]);
        console.log('أسماء المنتجات:', allProducts.map(p => p.name).slice(0, 10));
    }
}

// دالة لإجبار إظهار النتائج للاختبار
function forceShowSearchResults() {
    console.log('🔧 إجبار إظهار نتائج البحث للاختبار...');

    if (allProducts.length === 0) {
        console.log('❌ لا توجد منتجات للاختبار');
        return;
    }

    // أخذ أول منتج للاختبار
    const testResults = allProducts.slice(0, 3);
    console.log('📦 منتجات الاختبار:', testResults);

    // إجبار إظهار النتائج
    showSearchResults(testResults);

    return 'تم إجبار إظهار ' + testResults.length + ' منتجات للاختبار';
}

// دالة لتشخيص حاوية البحث
function debugSearchContainer() {
    console.log('🔍 تشخيص حاوية البحث:');

    const $container = $('.smart-search-container');
    const $input = $('#smartSearch');
    const $results = $('#searchResults');

    console.log('- حاوية البحث:', $container.length, 'مرئية:', $container.is(':visible'));
    console.log('- حقل البحث:', $input.length, 'قيمة:', $input.val());
    console.log('- منطقة النتائج:', $results.length, 'مرئية:', $results.is(':visible'));
    console.log('- موضع النتائج:', $results.position());
    console.log('- أبعاد النتائج:', $results.width(), 'x', $results.height());
    console.log('- CSS display:', $results.css('display'));
    console.log('- z-index:', $results.css('z-index'));

    // اختبار CSS
    $results.css({
        'background': 'red',
        'height': '200px',
        'display': 'block',
        'position': 'relative'
    });

    return 'تم تطبيق CSS تجريبي أحمر';
}

// تحميل العملاء في القائمة المنسدلة
function loadCustomersInSelect() {
    console.log('🔄 بدء تحميل العملاء في القائمة المنسدلة...');
    console.log('📊 allCustomers:', allCustomers);

    const $customerSelect = $('#customerSelect');
    console.log('🔍 عنصر customerSelect موجود:', $customerSelect.length > 0);

    $customerSelect.empty().append('<option value="">اختر العميل...</option>');

    if (allCustomers && allCustomers.length > 0) {
        console.log('✅ إضافة العملاء إلى القائمة...');
        allCustomers.forEach((customer, index) => {
            console.log(`👤 إضافة العميل ${index + 1}:`, {id: customer.id, name: customer.name});

            // تجربة خصائص مختلفة للاسم
            const customerName = customer.name || customer.customer_name || customer.full_name || `عميل ${customer.id}`;

            $customerSelect.append(`
                <option value="${customer.id}">${customerName}</option>
            `);
        });

        console.log('✅ تم إضافة جميع العملاء إلى القائمة');
        console.log('📋 عدد الخيارات في القائمة:', $customerSelect.find('option').length);

        // تحديث Select2 إذا كان مُفعل
        if ($customerSelect.hasClass('select2-hidden-accessible')) {
            $customerSelect.trigger('change');
            console.log('🔄 تم تحديث Select2');
        }
    } else {
        console.warn('⚠️ لا توجد عملاء لإضافتهم إلى القائمة');
    }
}// إعداد مستمعي الأحداث
function setupEventListeners() {
    // البحث الذكي مع debounce
    let searchTimeout;
    $('#smartSearch').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();

        if (query.length === 0) {
            hideSearchResults();
            return;
        }

        searchTimeout = setTimeout(() => {
            handleSmartSearch.call(this);
        }, 300);
    });

    // البحث عند الضغط على Enter
    $('#smartSearch').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSmartSearch.call(this);
        }
    });

    // حسابات الخصم والضريبة
    $('#discountPercent, #taxPercent').on('input', calculateTotals);

    // حفظ الفاتورة
    $('#saveInvoiceBtn').on('click', saveInvoice);

    // إخفاء نتائج البحث عند النقر خارجها
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.smart-search-container').length) {
            hideSearchResults();
        }
    });

    // التنقل في نتائج البحث بالكيبورد
    $('#smartSearch').on('keydown', function(e) {
        const results = $('.search-result-item');
        if (results.length === 0) return;

        let current = results.filter('.keyboard-selected');

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (current.length === 0) {
                    results.first().addClass('keyboard-selected');
                } else {
                    current.removeClass('keyboard-selected');
                    const next = current.next('.search-result-item');
                    if (next.length) {
                        next.addClass('keyboard-selected');
                    } else {
                        results.first().addClass('keyboard-selected');
                    }
                }
                break;

            case 'ArrowUp':
                e.preventDefault();
                if (current.length === 0) {
                    results.last().addClass('keyboard-selected');
                } else {
                    current.removeClass('keyboard-selected');
                    const prev = current.prev('.search-result-item');
                    if (prev.length) {
                        prev.addClass('keyboard-selected');
                    } else {
                        results.last().addClass('keyboard-selected');
                    }
                }
                break;

            case 'Enter':
                if (current.length) {
                    e.preventDefault();
                    current.click();
                }
                break;
        }
    });
}

// البحث الذكي
function handleSmartSearch() {
    const query = $(this).val().trim();

    if (query.length < 2) {
        $('#searchResults').hide();
        return;
    }

    // التحقق من وجود بيانات المنتجات
    if (!allProducts || allProducts.length === 0) {
        $('#searchResults').html(`
            <div class="search-result-item" style="justify-content: center; text-align: center; color: #ef4444;">
                <div class="product-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="margin-right: 1rem;">
                    <div style="font-weight: 600; color: #ef4444;">لا توجد منتجات متاحة</div>
                    <div style="font-size: 0.8rem; color: #64748b;">تأكد من إضافة منتجات أولاً</div>
                </div>
            </div>
        `).show();
        return;
    }

    // إظهار مؤشر التحميل
    showSearchLoading();

    // البحث في المنتجات مع تأخير بسيط للتحسن
    setTimeout(() => {
        try {
            console.log('🔍 بدء البحث عن:', query);
            console.log('📦 عدد المنتجات المتاح:', allProducts.length);
            console.log('🔢 نوع البيانات:', typeof allProducts, Array.isArray(allProducts));

            if (!Array.isArray(allProducts)) {
                console.error('❌ المنتجات ليست مصفوفة:', allProducts);
                showSearchError();
                return;
            }

            const searchTerm = query.toLowerCase();
            const results = allProducts.filter((product, index) => {
                if (!product) {
                    console.warn(`⚠️ منتج فارغ في الفهرس ${index}`);
                    return false;
                }

                const name = (product.name || '').toLowerCase();
                const description = (product.description || '').toLowerCase();
                const category = (product.category?.name || '').toLowerCase();

                const matches = name.includes(searchTerm) ||
                               description.includes(searchTerm) ||
                               category.includes(searchTerm);

                if (matches && index < 5) { // اطبع أول 5 نتائج للتشخيص
                    console.log(`✅ تطابق: ${product.name} - ${name} contains ${searchTerm}`);
                }

                return matches;
            }).slice(0, 10);

            console.log('🔍 نتائج البحث لـ:', query, '- العدد:', results.length);
            if (results.length > 0) {
                console.log('🎯 أول نتيجة:', results[0].name);
            }
            showSearchResults(results);
        } catch (error) {
            console.error('❌ خطأ في البحث:', error);
            console.error('تفاصيل الخطأ:', error.stack);
            showSearchError();
        }
    }, 300);
}

// إظهار خطأ في البحث
function showSearchError() {
    $('#searchResults').html(`
        <div class="search-result-item" style="justify-content: center; text-align: center; color: #ef4444;">
            <div class="product-icon" style="background: #fee2e2; color: #ef4444;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <span style="margin-right: 1rem;">حدث خطأ في البحث، يرجى المحاولة مرة أخرى</span>
        </div>
    `);

    showSearchResultsContainer();
}

// إظهار مؤشر تحميل البحث
function showSearchLoading() {
    console.log('⏳ إظهار مؤشر التحميل');
    const $results = $('#searchResults');

    $results.html(`
        <div class="search-result-item" style="text-align: center; justify-content: center;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-spinner fa-spin" style="color: #667eea;"></i>
                <span>جاري البحث...</span>
            </div>
        </div>
    `);

    showSearchResultsContainer();

    console.log('✅ تم إظهار مؤشر التحميل، مرئي:', $results.is(':visible'));
}

// إظهار نتائج البحث بتصميم جميل
function showSearchResults(results) {
    console.log('🎨 دالة عرض النتائج - البيانات المستلمة:', results);
    console.log('📝 عنصر searchResults موجود؟', $('#searchResults').length > 0);

    if (!results || results.length === 0) {
        console.log('⚠️ لا توجد نتائج لعرضها');
        $('#searchResults').html(`
            <div class="search-result-item" style="justify-content: center; text-align: center; color: #64748b; font-weight: 500;">
                <div class="product-icon" style="background: #f3f4f6; color: #9ca3af;">
                    <i class="fas fa-search"></i>
                </div>
                <div style="margin-right: 1rem;">
                    <div style="font-weight: 600;">لم يتم العثور على منتجات مطابقة</div>
                    <div style="font-size: 0.8rem; margin-top: 0.25rem;">جرب كلمات بحث أخرى</div>
                </div>
            </div>
        `);

        showSearchResultsContainer();
        console.log('✅ تم عرض رسالة "لا توجد نتائج"');
        return;
    }

    console.log('🔨 بدء إنشاء HTML للنتائج...');
    let html = '';
    results.forEach((item, index) => {
        try {
            // التحقق من صحة البيانات وتأمين النص
            const id = item.id || 0;
            const name = item.name || 'منتج غير محدد';
            const safeName = name.replace(/'/g, "\\'").replace(/"/g, '\\"');
            const price = parseFloat(item.price || 0);
            const unit = item.unit || 'وحدة';
            const safeUnit = unit.replace(/'/g, "\\'");
            const stock = item.stock || 0;
            const category = item.category?.name || '';

            // رمز المنتج حسب الفئة
            let productIcon = '📦';
            if (category) {
                if (category.includes('إلكترون')) productIcon = '💻';
                else if (category.includes('طعام') || category.includes('غذا')) productIcon = '🍎';
                else if (category.includes('ملابس') || category.includes('أزياء')) productIcon = '👕';
                else if (category.includes('كتب') || category.includes('تعليم')) productIcon = '📚';
                else if (category.includes('سيارات') || category.includes('مركبات')) productIcon = '🚗';
            }

            html += `
                <div class="search-result-item" onclick="addItemFromSearch(${id}, '${safeName}', ${price}, '${safeUnit}')"
                     data-product-id="${id}">
                    <div class="product-icon">
                        ${productIcon}
                    </div>
                    <div class="product-info">
                        <div class="product-name">${name}</div>
                        <div class="product-price">${price.toFixed(2)} ر.س / ${unit}</div>
                        <div class="product-stock">
                            ${stock > 0 ?
                                `<span style="color: #10b981;">✅ متوفر: ${stock} وحدة</span>` :
                                `<span style="color: #ef4444;">⚠️ غير متوفر</span>`
                            }
                        </div>
                    </div>
                    <div class="add-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            `;
        } catch (error) {
            console.error('❌ خطأ في عرض منتج:', item, error);
        }
    });

    console.log('📏 طول HTML المُنشأ:', html.length);
    console.log('🔍 HTML المُنشأ (أول 200 حرف):', html.substring(0, 200));

    if (html) {
        console.log('✅ إضافة HTML إلى صفحة وإظهار النتائج');

        const $searchResults = $('#searchResults');
        console.log('📍 عنصر النتائج:', $searchResults.length, 'موضع:', $searchResults.position());

        // إضافة HTML
        $searchResults.html(html);

        // استخدام الفئة الجديدة لإظهار النتائج
        $searchResults.addClass('show').show();

        // فحص نهائي
        setTimeout(() => {
            console.log('� فحص نهائي:');
            console.log('- مرئي؟', $searchResults.is(':visible'));
            console.log('- CSS display:', $searchResults.css('display'));
            console.log('- عدد العناصر الفرعية:', $searchResults.children().length);
            console.log('- المحتوى الأول:', $searchResults.html().substring(0, 100));

            // إضافة تحذير للمستخدم
            if (!$searchResults.is(':visible')) {
                console.error('🚨 النتائج غير مرئية رغم المحاولات');
                // إضافة تنبيه مؤقت
                $searchResults.after('<div id="debug-alert" style="position:absolute;background:red;color:white;padding:10px;z-index:9999;">النتائج موجودة لكن غير مرئية - تحقق من CSS</div>');
                setTimeout(() => $('#debug-alert').remove(), 3000);
            }
        }, 100);

        console.log('📦 عدد عناصر search-result-item:', $('.search-result-item').length);
    } else {
        console.error('❌ HTML فارغ - إظهار رسالة خطأ');
        showSearchError();
    }
}

// إضافة صنف من البحث
function addItemFromSearch(id, name, price, unit) {
    // التحقق من وجود الصنف مسبقاً
    const existingItem = invoiceItems.find(item => item.product_id === id);

    if (existingItem) {
        // زيادة الكمية إذا كان الصنف موجود مسبقاً
        const newQuantity = existingItem.quantity + 1;
        updateItemQuantity(existingItem.id, newQuantity);

        showNotification('تم زيادة كمية الصنف! 📈', 'success');
    } else {
        // إضافة صنف جديد
        itemCounter++;

        const item = {
            id: itemCounter,
            product_id: id,
            name: name,
            unit: unit || 'وحدة',
            quantity: 1,
            price: parseFloat(price) || 0,
            total: parseFloat(price) || 0
        };

        invoiceItems.push(item);
        addItemToTable(item);
        showNotification('تم إضافة الصنف بنجاح! ✅', 'success');
    }

    calculateTotals();
    updateItemsCounter();

    // مسح البحث وإخفاء النتائج
    $('#smartSearch').val('');
    hideSearchResults();
}

// إضافة صنف إلى الجدول
function addItemToTable(item) {
    const row = `
        <tr data-item-id="${item.id}" style="animation: slideInRight 0.3s ease;" class="cart-item-row">
            <td style="text-align: center; font-weight: 600; color: #667eea;">${item.id}</td>
            <td style="font-weight: 500;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.2rem;">📦</span>
                    <span>${item.name}</span>
                </div>
            </td>
            <td style="text-align: center; color: #64748b; font-weight: 500;">${item.unit}</td>
            <td>
                <div class="quantity-controls">
                    <button type="button" class="qty-btn minus-btn" onclick="decreaseQuantity(${item.id})" title="تقليل الكمية">
                        <i class="fas fa-minus" style="font-size: 0.7rem;"></i>
                    </button>
                    <div class="quantity-display" style="
                        min-width: 60px;
                        text-align: center;
                        font-weight: 700;
                        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                        border: 2px solid #e3f2fd;
                        border-radius: 10px;
                        padding: 8px 12px;
                        margin: 0 6px;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                        color: #2563eb;
                        position: relative;
                        overflow: hidden;
                    ">
                        <div style="font-size: 1rem; line-height: 1;">${item.quantity}</div>
                        <small style="font-size: 0.65rem; color: #64748b; margin-top: 2px; display: block;">قطعة</small>
                    </div>
                    <button type="button" class="qty-btn plus-btn" onclick="increaseQuantity(${item.id})" title="زيادة الكمية">
                        <i class="fas fa-plus" style="font-size: 0.7rem;"></i>
                    </button>
                </div>
            </td>
            <td>
                <div class="price-controls">
                    <input type="number" class="table-input price-input" value="${item.price}"
                           step="0.01" onchange="updateItemPrice(${item.id}, this.value)"
                           style="text-align: center; font-weight: 600;">
                    <span style="font-size: 0.8rem; color: #64748b;">ر.س</span>
                </div>
            </td>
            <td class="item-total" style="font-weight: 700; color: #10b981; text-align: center;">
                ${item.total.toFixed(2)} ر.س
            </td>
            <td style="text-align: center;">
                <button type="button" class="delete-btn" onclick="confirmRemoveItem(${item.id})" title="حذف الصنف من العربة">
                    <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                    <span class="btn-text" style="font-size: 0.7rem; margin-right: 4px;">حذف</span>
                </button>
            </td>
        </tr>
    `;

    $('#itemsTableBody').append(row);
    $('#emptyMessage').hide();
}

// تحديث كمية الصنف
function updateItemQuantity(itemId, quantity) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        const newQuantity = Math.max(1, parseFloat(quantity) || 1); // الحد الأدنى 1
        item.quantity = newQuantity;
        item.total = item.quantity * item.price;

        // تحديث القيم في الجدول
        const $quantityDisplay = $(`tr[data-item-id="${itemId}"] .quantity-display`);
        $quantityDisplay.html(`
            <div style="font-size: 1rem; line-height: 1;">${newQuantity}</div>
            <small style="font-size: 0.65rem; color: #64748b; margin-top: 2px; display: block;">قطعة</small>
        `);

        $(`tr[data-item-id="${itemId}"] .item-total`).html(`${item.total.toFixed(2)} ر.س`);

        // تأثير بصري للتحديث
        const $row = $(`tr[data-item-id="${itemId}"]`);
        $quantityDisplay.addClass('counter-update');
        $row.addClass('updating');

        setTimeout(() => {
            $quantityDisplay.removeClass('counter-update');
            $row.removeClass('updating');
        }, 300);

        calculateTotals();
        updateItemsCounter();
    }
}

// زيادة الكمية
function increaseQuantity(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        const newQuantity = item.quantity + 1;
        updateItemQuantity(itemId, newQuantity);

        // تأثير بصري للزيادة
        const $btn = $(`tr[data-item-id="${itemId}"] .plus-btn`);
        $btn.addClass('btn-pulse');
        setTimeout(() => $btn.removeClass('btn-pulse'), 200);

        showNotification(`تم زيادة كمية ${item.name} إلى ${newQuantity} ⬆️`, 'success');
    }
}

// تقليل الكمية
function decreaseQuantity(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item && item.quantity > 1) {
        const newQuantity = item.quantity - 1;
        updateItemQuantity(itemId, newQuantity);

        // تأثير بصري للنقصان
        const $btn = $(`tr[data-item-id="${itemId}"] .minus-btn`);
        $btn.addClass('btn-pulse');
        setTimeout(() => $btn.removeClass('btn-pulse'), 200);

        showNotification(`تم تقليل كمية ${item.name} إلى ${newQuantity} ⬇️`, 'info');
    } else if (item && item.quantity === 1) {
        // إظهار تأكيد للحذف إذا كانت الكمية 1
        if (confirm(`هل تريد حذف "${item.name}" من العربة؟`)) {
            removeItem(itemId);
        }
    }
}

// تحديث سعر الصنف
function updateItemPrice(itemId, price) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        item.price = parseFloat(price) || 0;
        item.total = item.quantity * item.price;

        // تحديث الإجمالي في الجدول
        $(`tr[data-item-id="${itemId}"] .item-total`).text(item.total.toFixed(2) + ' ر.س');

        calculateTotals();
    }
}

// تأكيد حذف الصنف
function confirmRemoveItem(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (!item) return;

    // إنشاء رسالة تأكيد مخصصة
    const itemInfo = `
        الصنف: ${item.name}
        الكمية: ${item.quantity} ${item.unit}
        السعر: ${(item.price * item.quantity).toLocaleString('ar-EG')} جنيه
    `;

    if (confirm(`هل أنت متأكد من حذف هذا الصنف من العربة؟\n\n${itemInfo}`)) {
        removeItem(itemId);
    }
}

// حذف صنف
function removeItem(itemId) {

    // إضافة تأثير بصري للحذف
    const $row = $(`tr[data-item-id="${itemId}"]`);
    $row.addClass('removing');

    // إزالة من المصفوفة
    invoiceItems = invoiceItems.filter(item => item.id !== itemId);

    // إزالة من الجدول مع أنيميشن محسن
    $row.addClass('fadeOut').fadeOut(400, function() {
        $(this).remove();

        // إظهار رسالة فارغة إذا لم تعد هناك أصناف
        if (invoiceItems.length === 0) {
            $('#emptyMessage').show().addClass('animate__animated animate__fadeIn');
        }

        calculateTotals();
        updateItemsCounter();
    });

    showNotification(`تم حذف "${itemName}" من العربة 🗑️`, 'error');
}

// حساب الإجماليات
function calculateTotals() {
    const subtotal = invoiceItems.reduce((sum, item) => sum + item.total, 0);
    const discountPercent = parseFloat($('#discountPercent').val()) || 0;
    const taxPercent = parseFloat($('#taxPercent').val()) || 0;

    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = taxableAmount * (taxPercent / 100);
    const grandTotal = taxableAmount + taxAmount;

    // تحديث العرض
    $('#subtotal').text(subtotal.toFixed(2) + ' ر.س');
    $('#discountAmount').text(discountAmount.toFixed(2) + ' ر.س');
    $('#taxAmount').text(taxAmount.toFixed(2) + ' ر.س');
    $('#grandTotal').text(grandTotal.toFixed(2) + ' ر.س');
}

// تحديث عداد الأصناف
function updateItemsCounter() {
    const itemsCount = invoiceItems.length;
    const totalQuantity = invoiceItems.reduce((sum, item) => sum + item.quantity, 0);
    const totalValue = invoiceItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    $('#itemsCount').text(itemsCount);

    // تحديث عنوان القسم مع معلومات إضافية
    const $header = $('.items-header h3');
    const $counterElement = $('#itemsCounter');

    if (itemsCount > 0) {
        const itemsText = itemsCount === 1 ? 'صنف واحد' : `${itemsCount} صنف`;
        const quantityText = totalQuantity === 1 ? 'قطعة واحدة' : `${totalQuantity} قطعة`;
        const valueText = totalValue.toLocaleString('ar-EG', {style: 'currency', currency: 'EGP'});

        $header.html(`
            <i class="fas fa-shopping-cart"></i>
            عربة التسوق
            <span style="font-size: 0.8rem; font-weight: normal; color: #64748b;">
                (${quantityText} • ${itemsText})
            </span>
        `);

        $counterElement.html(`
            <i class="fas fa-box me-1"></i>
            ${itemsText} • ${quantityText}
            <br>
            <small style="opacity: 0.8;">${valueText}</small>
        `);
        $counterElement.css('background', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
    } else {
        $header.html(`
            <i class="fas fa-shopping-cart"></i>
            عربة التسوق
        `);

        $counterElement.html(`
            <i class="fas fa-cart-plus me-1"></i>
            العربة فارغة
        `);
        $counterElement.css('background', 'linear-gradient(135deg, #95a5a6, #7f8c8d)');
    }

    // تأثير بصري للعداد
    $counterElement.addClass('counter-update');
    setTimeout(() => $counterElement.removeClass('counter-update'), 300);
}

// تحديث رقم الفاتورة
function updateInvoiceNumber() {
    const newNumber = generateInvoiceNumber();
    $('#invoiceNumber').text(newNumber);
}

// تحديث رقم الفاتورة التلقائي
function generateInvoiceNumber() {
    const today = new Date();
    const dateStr = today.getFullYear() +
                   String(today.getMonth() + 1).padStart(2, '0') +
                   String(today.getDate()).padStart(2, '0');

    // يمكن إضافة رقم تسلسلي من قاعدة البيانات
    const randomNum = Math.floor(Math.random() * 999) + 1;
    return `INV-${dateStr}-${String(randomNum).padStart(3, '0')}`;
}

// حفظ الفاتورة
function saveInvoice() {
    console.log('🔄 بدء حفظ الفاتورة...');

    // التحقق من البيانات الأساسية
    const validationErrors = [];

    if (invoiceItems.length === 0) {
        validationErrors.push('يجب إضافة صنف واحد على الأقل');
    }

    if (!$('#customerSelect').val()) {
        validationErrors.push('يجب اختيار العميل');
    }

    if (!$('#invoiceDate').val()) {
        validationErrors.push('يجب تحديد تاريخ الفاتورة');
    }

    // التحقق من بيانات الأصناف
    invoiceItems.forEach((item, index) => {
        if (!item.product_id && !item.id) {
            validationErrors.push(`معرف المنتج مطلوب للصنف ${index + 1}`);
        }
        if (!item.price || parseFloat(item.price) <= 0) {
            validationErrors.push(`سعر صحيح مطلوب للصنف ${index + 1}`);
        }
        if (!item.quantity || parseFloat(item.quantity) <= 0) {
            validationErrors.push(`كمية صحيحة مطلوبة للصنف ${index + 1}`);
        }
        if (!item.total || parseFloat(item.total) <= 0) {
            validationErrors.push(`إجمالي صحيح مطلوب للصنف ${index + 1}`);
        }
    });    if (validationErrors.length > 0) {
        showNotification(`خطأ في البيانات: ${validationErrors.join(' • ')}`, 'error');
        return;
    }

    // حساب الإجماليات قبل الحفظ
    const subtotal = invoiceItems.reduce((sum, item) => sum + item.total, 0);
    const discountPercent = parseFloat($('#discountPercent').val()) || 0;
    const taxPercent = parseFloat($('#taxPercent').val()) || 15;
    const discountAmount = (subtotal * discountPercent / 100) || 0; // التأكد من عدم كون القيمة NaN
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = (taxableAmount * taxPercent / 100) || 0; // التأكد من عدم كون القيمة NaN
    const grandTotal = taxableAmount + taxAmount;

    console.log('📊 الإجماليات المحسوبة:', {
        subtotal: subtotal.toFixed(2),
        discountPercent: discountPercent + '%',
        discountAmount: discountAmount.toFixed(2),
        taxPercent: taxPercent + '%',
        taxAmount: taxAmount.toFixed(2),
        grandTotal: grandTotal.toFixed(2)
    });

    console.log('📋 أصناف الفاتورة:', invoiceItems.map((item, index) => ({
        index,
        id: item.product_id || item.id,
        name: item.name,
        quantity: item.quantity,
        price: item.price,
        total: item.total
    })));

    // إظهار مؤشر التحميل
    const btn = $('#saveInvoiceBtn');
    btn.prop('disabled', true);
    btn.find('.loading-spinner').show();
    btn.find('i.fas').hide();

    // تجهيز البيانات للإرسال حسب توقعات الـ backend
    const invoiceData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        invoice_number: $('#invoiceNumber').text() || generateInvoiceNumber(),
        invoice_date: $('#invoiceDate').val(),
        branch_id: $('#branchSelect').val() || 1,
        customer_id: $('#customerSelect').val(),
        subtotal: subtotal.toFixed(2),
        discount: Math.max(0, discountAmount).toFixed(2), // تصحيح: discount بدلاً من discount_amount
        tax: Math.max(0, taxAmount).toFixed(2), // تصحيح: tax بدلاً من tax_amount
        total: grandTotal.toFixed(2),
        invoice_type: 'sales',
        status: 'pending',
        notes: '',
        items: invoiceItems.map((item, index) => ({
            [`items[${index}][productId]`]: item.product_id || item.id, // تصحيح: productId
            [`items[${index}][product_name]`]: item.name,
            [`items[${index}][quantity]`]: parseFloat(item.quantity),
            [`items[${index}][price]`]: parseFloat(item.price), // تصحيح: price بدلاً من unit_price
            [`items[${index}][total]`]: parseFloat(item.total), // تصحيح: total بدلاً من total_price
            [`items[${index}][unit]`]: item.unit || 'قطعة'
        }))
    };

    // تحويل items من مصفوفة إلى حقول منفصلة
    const flattenedData = { ...invoiceData };
    delete flattenedData.items;

    // إضافة items كحقول منفصلة
    invoiceItems.forEach((item, index) => {
        flattenedData[`items[${index}][productId]`] = item.product_id || item.id;
        flattenedData[`items[${index}][product_name]`] = item.name;
        flattenedData[`items[${index}][quantity]`] = parseFloat(item.quantity);
        flattenedData[`items[${index}][price]`] = parseFloat(item.price);
        flattenedData[`items[${index}][total]`] = parseFloat(item.total);
        flattenedData[`items[${index}][unit]`] = item.unit || 'قطعة';
    });

    console.log('📤 بيانات الفاتورة للإرسال:', flattenedData);

    // إرسال البيانات إلى الخادم - جربة روابط مختلفة
    const saveRoutes = [
        '{{ route("invoices.store") ?? "/invoices/store" }}',
        '/sales-invoices/store',
        '/invoices/create',
        '/api/invoices'
    ];

    let currentSaveRouteIndex = 0;

    function trySaveInvoice() {
        if (currentSaveRouteIndex >= saveRoutes.length) {
            console.error('❌ فشل في حفظ الفاتورة من جميع الروابط المحتملة');
            showNotification('❌ خطأ في حفظ الفاتورة - تحقق من الإعدادات', 'error');
            btn.prop('disabled', false);
            btn.find('.loading-spinner').hide();
            btn.find('i.fas').show();
            return;
        }

        const url = saveRoutes[currentSaveRouteIndex];
        console.log(`💾 محاولة حفظ الفاتورة في: ${url}`);

        $.ajax({
            url: url,
            method: 'POST',
            data: flattenedData,
            dataType: 'json',
            timeout: 15000,
            success: function(response) {
                console.log('✅ تم حفظ الفاتورة بنجاح:', response);
            showNotification('تم حفظ الفاتورة بنجاح! 🎉', 'success');

            // إعادة تعيين الزر
            btn.prop('disabled', false);
            btn.find('.loading-spinner').hide();
            btn.find('i.fas').show();

            // يمكن إعادة توجيه المستخدم أو إعادة تعيين النموذج
            setTimeout(() => {
                if (confirm('هل تريد إنشاء فاتورة جديدة؟')) {
                    resetForm();
                } else {
                    window.location.href = '{{ route("invoices.index") }}';
                }
            }, 1500);
        },
        error: function(xhr, status, error) {
            console.error('❌ خطأ في حفظ الفاتورة:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                responseJSON: xhr.responseJSON
            });

            let errorMessage = 'حدث خطأ أثناء حفظ الفاتورة! ❌';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    // معالجة أخطاء Laravel validation
                    const errors = xhr.responseJSON.errors;
                    const errorList = [];

                    Object.keys(errors).forEach(field => {
                        if (Array.isArray(errors[field])) {
                            errorList.push(`${field}: ${errors[field].join(', ')}`);
                        }
                    });

                    if (errorList.length > 0) {
                        errorMessage = `أخطاء التحقق:\n${errorList.join('\n')}`;
                    }
                }
            } else if (xhr.status === 422) {
                errorMessage = 'بيانات غير صحيحة - تحقق من جميع الحقول المطلوبة';
            } else if (xhr.status === 500) {
                errorMessage = 'خطأ في الخادم - حاول مرة أخرى';
            } else if (xhr.status === 0) {
                errorMessage = 'مشكلة في الاتصال - تحقق من الإنترنت';
            }

            // جربة الرابط التالي إذا فشل الحالي
            if (xhr.status === 404 || xhr.status === 0) {
                console.log(`❌ فشل حفظ الفاتورة من ${url}, جربة الرابط التالي...`);
                currentSaveRouteIndex++;
                setTimeout(trySaveInvoice, 500);
                return;
            }

            showNotification(errorMessage, 'error');

            // إعادة تعيين الزر
            btn.prop('disabled', false);
            btn.find('.loading-spinner').hide();
            btn.find('i.fas').show();
            }
        });
    }

    // بدء محاولة حفظ الفاتورة
    trySaveInvoice();
}

// إظهار الإشعارات
function showNotification(message, type) {
    const notification = $('#notification');
    notification.removeClass('success error').addClass(type);
    notification.text(message);
    notification.addClass('show');

    setTimeout(() => {
        notification.removeClass('show');
    }, 3000);
}

// إعادة تعيين النموذج
function resetForm() {
    invoiceItems = [];
    itemCounter = 0;
    $('#itemsTableBody').empty();
    $('#emptyMessage').show();
    $('#smartSearch').val('');
    hideSearchResults();
    calculateTotals();
    updateItemsCounter();
    showNotification('تم إعادة تعيين النموذج ✨', 'success');
}

// فتح الكتالوج
function openCatalog() {
    showNotification('سيتم فتح كتالوج المنتجات قريباً... 📚', 'info');
}

// إضافة عميل جديد
function addNewClient(type = 'sales') {
    try {
        // إظهار النافذة المنبثقة
        $('.invoice-form-modal').show();
        $('#modal-title').text('إضافة عميل جديد');

        // مسح النموذج
        $('#customer-supplier-form')[0].reset();
        $('#customer-supplier-form .error').text('');

        showNotification('قم بملء بيانات العميل الجديد 👤', 'info');
    } catch (error) {
        console.error('خطأ في فتح نافذة العميل:', error);
        showNotification('حدث خطأ في فتح النافذة ❌', 'error');
    }
}

// إغلاق النافذة المنبثقة
function closeModal() {
    $('.invoice-form-modal').hide();
}

// إغلاق النافذة عند النقر خارجها
$(document).on('click', '.invoice-form-modal', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// إغلاق النافذة بـ ESC
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// CSS للأنيميشن
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    .fadeOut {
        animation: fadeOut 0.3s ease;
    }
`;
document.head.appendChild(style);

// إضافة Select2 في حالة عدم وجوده في الـ layout
$(document).ready(function() {
    // التحقق من وجود Select2
    if (typeof $.fn.select2 === 'undefined') {
        console.log('🔄 تحميل Select2 من CDN...');

        // تحميل Select2 JavaScript
        $.getScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js')
        .done(function() {
            console.log('✅ تم تحميل Select2 بنجاح');
        })
        .fail(function() {
            console.error('❌ فشل في تحميل Select2');
        });
    }
});
</script>
@endsection
