<div class="modal fade" id="modalCompanyDoc" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" dir="rtl">
      <form id="company-doc-form" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">إضافة / تعديل مستند</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="doc_id">

          <div class="row g-3 mb-4">
            <!-- اسم المستند -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="name" class="col-form-label form-label required">اسم المستند</label>
                <input type="text" name="name" id="name" class="form-control" autocomplete="yes">
              </div>
            </div>

            <!-- جهة الإصدار -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="issuing_authority" class="col-form-label form-label required">جهة الإصدار</label>
                <input type="text" name="issuing_authority" id="issuing_authority" class="form-control">
              </div>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <!-- تاريخ الإصدار -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="issuance_date" class="col-form-label form-label required">تاريخ الإصدار</label>
                <input type="text" name="issuance_date" placeholder="YYYY-MM-DD" id="issuance_date"
                  class="form-control flatpickr-date">
              </div>
            </div>

            <!-- تاريخ التجديد -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="renewal_date" class="col-form-label form-label">تاريخ التجديد</label>
                <input type="text" name="renewal_date" placeholder="YYYY-MM-DD" id="renewal_date"
                  class="form-control flatpickr-date">
                <div class="form-text text-muted">(اختياري)</div>
              </div>
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
