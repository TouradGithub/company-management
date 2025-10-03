<div class="invoice-form-modal" id="{{ $modalId }}" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ $title }}</h3>
            <button type="button" class="close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            @if($body === 'customer-form')
                <form id="addCustomerForm">
                    <div class="form-group">
                        <label>اسم العميل</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="tel" class="form-control" name="phone">
                    </div>
                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label>العنوان</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            حفظ العميل
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </button>
                    </div>
                </form>
            @else
                {!! $body !!}
            @endif
        </div>
    </div>
</div>
