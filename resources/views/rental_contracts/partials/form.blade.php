<div class="modal fade" id="modalCompanyDoc" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" dir="rtl">
      <form id="company-doc-form" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title " id="modalLabel">تعديل عقد إيجار</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="doc_id">

          <div class="row g-3 mb-4">
            <!-- اسم المؤجر -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="lessor_name" class="col-form-label form-label required">اسم المؤجر</label>
                <input type="text" name="lessor_name" id="lessor_name" class="form-control" autocomplete="yes">
              </div>
            </div>

            <!-- اسم المستأجر -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="lessee_name" class="col-form-label form-label required">اسم المستأجر</label>
                <input type="text" name="lessee_name" id="lessee_name" class="form-control" autocomplete="yes">
              </div>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <!-- تاريخ تحرير العقد -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="contract_date" class="col-form-label form-label required">تاريخ تحرير العقد</label>
                <input type="text" name="contract_date" id="contract_date" class="form-control flatpickr-date"
                  placeholder="YYYY-MM-DD" required>
              </div>
            </div>

            <!-- تاريخ بداية الإيجار -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date" class="col-form-label form-label required">تاريخ بداية الإيجار</label>
                <input type="text" name="start_date" id="start_date" class="form-control flatpickr-date"
                  placeholder="YYYY-MM-DD" required>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <!-- تاريخ نهاية الإيجار -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date" class="col-form-label form-label required">تاريخ نهاية الإيجار</label>
                <input type="text" name="end_date" id="end_date" class="form-control flatpickr-date"
                  placeholder="YYYY-MM-DD" required>
              </div>
            </div>

            <!-- قيمة الإيجار الشهري -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="rent_amount" class="col-form-label form-label required">قيمة الإيجار الشهري (ج.م)</label>
                <input type="number" step="0.01" name="rent_amount" id="rent_amount" class="form-control" required>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <!-- محل الإيجار -->
            <div class="form-group">
              <label for="rental_location" class="col-form-label form-label required">محل الإيجار</label>
              <input type="text" name="rental_location" id="rental_location" class="form-control" required>
            </div>
          </div>

          <div class="mb-3">
            <!-- ملف المستند -->
            <div class="form-group">
              <label for="document_image_path" class="col-form-label form-label">ملف المستند (PDF/صورة)</label>
              <input type="file" name="document_image_path" id="document_image_path" class="form-control">
              <div class="form-text text-muted">يُسمح بالملفات: PDF, JPG, PNG (الحجم الأقصى: 5MB)</div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">
            <i class="icon-base ti tabler-plus icon-xs me-1_5"></i>حفظ
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="icon-base ti tabler-cancel icon-close me-1_5"></i>إلغاء
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
