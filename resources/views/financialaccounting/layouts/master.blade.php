<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام المحاسبة</title>
    <link rel="stylesheet" href="{{asset('financialaccounting/styles.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Ensure SweetAlert2 is above everything */
        .swal2-container {
            z-index: 10000 !important; /* Higher than your modal */
        }

        /*!* Your custom modal and overlay *!*/
        /*.invoice-form-modal {*/
        /*    z-index: 1000; !* Already set in your JS, but confirm it’s not higher *!*/
        /*}*/

        /*.page-overlay {*/
        /*    z-index: 999; !* Already set in your JS, should be below modal *!*/
        /*}*/
        .error { color: red; font-size: 12px; }
        .success { color: green; }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            z-index: 1000;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .entry-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .entry-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .entry-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .entry-info {
            display: flex;
            gap: 15px;
        }

        .entry-number {
            font-weight: bold;
        }

        .entry-totals {
            display: flex;
            gap: 20px;
        }

        .total-debit {
            color: #2ecc71;
        }

        .total-credit {
            color: #e74c3c;
        }

        .entry-details {
            padding: 15px;
        }

        .details-table {
            width: 100%;
            margin: 0;
        }

        .details-table th {
            background: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
            padding: 10px;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .amount-cell {
            text-align: left;
            direction: ltr;
            font-family: 'Courier New', monospace;
        }
    </style>


</head>
<body>
<div class="calculator-button">
    <i class="fas fa-calculator"></i>
</div>
<div class="container">
  @include('financialaccounting.layouts.sidebar')

    <main>
       @include('financialaccounting.layouts.header')

        <div class="content">

        @yield('content')
        </div>
    </main>
</div>
<div class="task-button">
    <i class="fas fa-tasks"></i>
</div>
<div class="task-menu">
    <button id="addTaskBtn">
        <i class="fas fa-plus"></i>
        إضافة مهمة
    </button>
    <button id="viewTasksBtn">
        <i class="fas fa-list"></i>
        عرض المهام
    </button>
</div>

<div class="modal-overlay"></div>
<div class="task-modal">
    <h2>إضافة مهمة جديدة</h2>
    <form id="taskForm" class="task-form">
        <div class="form-group">
            <label>اسم المهمة</label>
            <input type="text" id="taskName" required>
        </div>
        <div class="form-group">
            <label>الأهمية</label>
            <select id="taskPriority" required>
                <option value="high">عالية</option>
                <option value="medium">متوسطة</option>
                <option value="low">منخفضة</option>
            </select>
        </div>
        <div class="form-group">
            <label>اللون</label>
            <div class="color-picker">
                <div class="color-option selected" style="background: #ff4757" data-color="#ff4757"></div>
                <div class="color-option" style="background: #2ed573" data-color="#2ed573"></div>
                <div class="color-option" style="background: #1e90ff" data-color="#1e90ff"></div>
                <div class="color-option" style="background: #ffa502" data-color="#ffa502"></div>
            </div>
        </div>
        <div class="form-group">
            <label>الوقت والتاريخ</label>
            <input type="datetime-local" id="taskDateTime" required>
        </div>
        <div class="modal-buttons">
            <button type="button" class="cancel-btn">إلغاء</button>
            <button type="submit" class="save-btn">حفظ</button>
        </div>
    </form>
</div>

<div class="tasks-list-modal">
    <h2>قائمة المهام</h2>
    <div id="tasksList"></div>
    <div class="modal-buttons">
        <button class="cancel-btn">إغلاق</button>
    </div>
</div>
<a href="https://wa.me/966590025167" class="whatsapp-button" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>
<div class="calculator-modal">
    <input type="text" class="calculator-screen" value="0" readonly>
    <div class="calculator-buttons">
        <button class="calc-btn clear" data-value="clear">C</button>
        <button class="calc-btn operator" data-value="/">/</button>
        <button class="calc-btn operator" data-value="*">×</button>
        <button class="calc-btn operator" data-value="-">-</button>
        <button class="calc-btn number" data-value="7">7</button>
        <button class="calc-btn number" data-value="8">8</button>
        <button class="calc-btn number" data-value="9">9</button>
        <button class="calc-btn operator" data-value="+">+</button>
        <button class="calc-btn number" data-value="4">4</button>
        <button class="calc-btn number" data-value="5">5</button>
        <button class="calc-btn number" data-value="6">6</button>
        <button class="calc-btn number" data-value="0">0</button>
        <button class="calc-btn number" data-value="1">1</button>
        <button class="calc-btn number" data-value="2">2</button>
        <button class="calc-btn number" data-value="3">3</button>
        <button class="calc-btn equals" data-value="=">=</button>
    </div>
</div>

<script >
    function showLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(overlay);
    }
    function hideLoadingOverlay() {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }

</script>
<script src="{{asset('financialaccounting/script.js')}}"></script>

@yield('js')
</body>
</html>
