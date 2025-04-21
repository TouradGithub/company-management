
<div class="invoice-form-modal">
    <div class="modal-content">
        <h2 id="modal-title">إضافة عميل/مورد</h2>
        <form id="customer-supplier-form">
            <div class="invoice-header-section">
                <div class="form-row">
                    <div class="form-group-model">
                        <label>الاسم</label>
                        <input type="text" id="name_customer_supplier" name="name" required>
                        <span id="error-name" class="error"></span>
                    </div>
                    <div class="form-group-model">
                        <label>رقم الهاتف</label>
                        <input type="number" id="contact_customer_supplier" name="contact_info" required>
                        <span id="error-contact_info" class="error"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>الفرع</label>
                        <select id="branch_id_customer_supplier" name="branch_id" required>
                            <option value="all">اختر الفرع...</option>
                            @foreach($branches as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-model">
                        <label>الحساب</label>
                        <select id="account_id_customer_supplier" name="account_id" required>
                            <option>اختر الحساب...</option>
                            @foreach($accounts as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>الحد الائتماني</label>
                        <input type="number" id="credit_limit" name="credit_limit" step="0.01" value="0" required>
                        <span id="error-credit_limit" class="error"></span>
                    </div>
                    <div class="form-group-model">
                        <div class="form-group-model">
                            <label> الرقم الضريبي</label>
                            <input type="number" id="tax_number" name="tax_number" value="" required>
                            @error('tax_number')
                            <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="cancel-btn" onclick="closeModal()">إلغاء</button>
                <button type="button" class="save-btn" onclick="saveCustomerSupplier()" id="save-btn">حفظ</button>
            </div>
        </form>
    </div>
</div>
