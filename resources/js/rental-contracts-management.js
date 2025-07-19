'use strict';

$(function () {
  const table = initDocumentsDataTable();
  setupEventHandlers(table);
});

// 🔖 Selector constants
const selectors = {
  table: '.datatables-documents',
  modalForm: '#modalCompanyDoc',
  modalView: '#modalViewDocument',
  form: '#company-doc-form',
  modalLabel: '#modalLabel',
  docId: '#doc_id',
  viewBody: '#document-view-body',
  selectAll: '#select-all',
  csrfToken: 'meta[name="csrf-token"]'
};

function initDocumentsDataTable() {
  if ($.fn.DataTable.isDataTable(selectors.table)) {
    $(selectors.table).DataTable().destroy();
    $(selectors.table).empty();
  }

  const selectedRowsCondition = (idx, data) => $(`.doc-select[value="${data.id}"]`).is(':checked');

  return $(selectors.table).DataTable({
    processing: true,
    deferRender: true,
    serverSide: true,
    ajax: '/rental-contracts-list',
    columns: [
      {
        data: 'id',
        orderable: false,
        searchable: false,
        render: data => `<input type="checkbox" class="form-check-input doc-select" value="${data}">`
      },
      { data: 'lessor_name' },
      { data: 'lessee_name' },
      { data: 'contract_date', render: renderArabicDate },
      { data: 'start_date', render: renderArabicDate },
      { data: 'end_date', render: renderArabicDate },
      { data: 'rent_amount' },
      {
        data: 'action',
        orderable: false,
        searchable: false
      }
    ],
    dom: `
      <'row m-3 my-0 justify-content-between'
        <'d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto'l>
        <'d-md-flex align-items-center dt-layout-end col-md-auto ms-auto d-flex gap-md-4 justify-content-md-between justify-content-center gap-2 flex-wrap'fB>
      >
      <'row'<'col-sm-12'tr>>
      <'row mx-3 justify-content-between'
        <'d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto'i>
        <'d-md-flex align-items-center dt-layout-end col-md-auto ms-auto d-flex gap-md-4 justify-content-md-between justify-content-center gap-2 flex-wrap'p>
      >
    `,
    columnDefs: [{ targets: '_all', className: 'text-center' }],
    language: { url: '/vendor/datatables/i18n/ar.json' },
    buttons: [
      {
        extend: 'collection',
        className: 'btn btn-label-secondary dropdown-toggle',
        text: '<i class="icon-base ti tabler-upload me-2 icon-sm"></i>تصدير العقد',
        autoClose: true,
        buttons: getExportButtons(selectedRowsCondition)
      },
      getAddDocumentButton()
    ],
    initComplete: function () {
      $('.dt-buttons .btn').removeClass('btn-secondary').addClass('add-new btn-primary');

      $(selectors.selectAll).on('click', function () {
        $('.doc-select').prop('checked', this.checked);
      });
    }
  });
}

function getExportButtons(selectedRowsCondition) {
  return [
    {
      text: '<i class="icon-base ti tabler-file-code-2 me-2"></i>PDF',
      className: 'dropdown-item',
      action: () => handleExport('pdf')
    },
    {
      text: '<i class="icon-base ti tabler-file-text me-2"></i>DOCX',
      className: 'dropdown-item',
      action: () => handleExport('docx')
    },
    {
      extend: 'copy',
      className: 'dropdown-item',
      text: '<i class="icon-base ti tabler-copy me-2"></i>نسخ المحدد',
      exportOptions: {
        rows: selectedRowsCondition,
        columns: [1, 2, 3, 4]
      }
    },
    {
      extend: 'print',
      className: 'dropdown-item',
      text: '<i class="icon-base ti tabler-printer me-2"></i>طباعة المحدد',
      exportOptions: {
        rows: selectedRowsCondition,
        columns: [1, 2, 3, 4]
      },
      customize: function (win) {
        // إجبار الاتجاه من اليمين لليسار
        $(win.document.body).css('direction', 'rtl');

        // تنسيق الجدول
        $(win.document.body)
          .find('table')
          .addClass('table table-bordered')
          .css('direction', 'rtl')
          .css('text-align', 'right');

        // العناوين والقيم
        $(win.document.body).find('th, td').css('text-align', 'right');
        // 🕒 إضافة التاريخ والوقت أسفل المستند
        const now = new Date();
        const options = {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        };
        const printedAt = now.toLocaleDateString('ar-EG', options);

        $(win.document.body).append(`
      <div style="margin-top: 40px; text-align: center; font-size: 14px; color: #888;">
        تمت الطباعة بواسطة نظام إدارة المستندات - أ/ محمد عاطف بتاريخ: ${printedAt}
      </div>
      `);
      }
    }
  ];
}

function getAddDocumentButton() {
  return {
    text: `
      <i class="icon-base ti tabler-plus icon-sm me-0 me-sm-2"></i>
      <span class="d-none d-sm-inline-block">إضافة عقد</span>
    `,
    className: 'btn btn-primary ms-2',
    attr: {
      'data-bs-toggle': 'modal',
      'data-bs-target': selectors.modalForm
    },
    action: function () {
      resetDocumentForm();
      $(selectors.modalLabel).text('إضافة عقد');
      $(selectors.modalForm).modal('show');
    }
  };
}

function setupEventHandlers(table) {
  $(selectors.table).on('click', '.delete-record', function () {
    const id = $(this).data('id');
    confirmDelete(id, table);
  });

  $(selectors.table).on('click', '.edit-record', function () {
    const id = $(this).data('id');
    loadDocumentForEdit(id);
  });

  $(document).on('click', '.view-record', function () {
    const id = $(this).data('id');
    viewDocument(id);
  });

  $(selectors.form).submit(function (e) {
    e.preventDefault();
    submitDocumentForm(this, table);
  });
}

function handleExport(format) {
  const ids = $('.doc-select:checked')
    .map((_, el) => el.value)
    .get();

  if (!ids.length) {
    Swal.fire({
      icon: 'warning',
      title: 'تنبيه',
      text: 'يرجى تحديد عقد واحد على الأقل',
      confirmButtonText: 'حسناً'
    });
    return;
  }

  window.open(`/export-contracts/${format}?ids=${ids.join(',')}`, '_blank');
}

function resetDocumentForm() {
  const form = $(selectors.form)[0];
  if (form) form.reset();

  $(selectors.docId).val('');
  $('.form-control').removeClass('is-invalid');
  $('.invalid-feedback').remove();
}

async function confirmDelete(id, table) {
  const result = await Swal.fire({
    icon: 'question',
    title: 'تأكيد الحذف',
    text: 'هل أنت متأكد من حذف هذا العقد؟',
    showCancelButton: true,
    confirmButtonText: 'نعم، احذف',
    cancelButtonText: 'إلغاء',
    customClass: {
      confirmButton: 'btn btn-danger',
      cancelButton: 'btn btn-label-secondary'
    }
  });

  if (!result.isConfirmed) return;

  try {
    await $.ajax({
      url: `/rental_contracts/${id}`,
      type: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': $(selectors.csrfToken).attr('content')
      }
    });

    Swal.fire({
      icon: 'success',
      title: 'تم الحذف!',
      text: 'تم حذف العقد بنجاح',
      timer: 2000,
      showConfirmButton: false
    });

    table.ajax.reload(null, false);
  } catch {
    Swal.fire({
      icon: 'error',
      title: 'خطأ!',
      text: 'حدث خطأ أثناء محاولة الحذف',
      confirmButtonText: 'حسناً'
    });
  }
}

function loadDocumentForEdit(id) {
  $.get(`/rental_contracts/${id}/edit`)
    .done(data => {
      $(selectors.docId).val(data.id);
      $('#lessor_name').val(data.lessor_name);
      $('#lessee_name').val(data.lessee_name);
      $('#contract_date').val(data.contract_date);
      $('#start_date').val(data.start_date);
      $('#end_date').val(data.end_date);
      $('#rental_location').val(data.rental_location);
      $('#rent_amount').val(data.rent_amount);
      $(selectors.modalLabel).text('تعديل عقد إيجار');
      $(selectors.modalForm).modal('show');
    })
    .fail(() => {
      Swal.fire({
        icon: 'error',
        title: 'خطأ!',
        text: 'فشل في تحميل بيانات العقد',
        confirmButtonText: 'حسناً'
      });
    });
}

function viewDocument(id) {
  fetch(`/rental_contracts/${id}`)
    .then(res => res.text())
    .then(html => {
      $(selectors.viewBody).html(html);
      $(selectors.modalView).modal('show');
    })
    .catch(() => {
      Swal.fire({
        icon: 'error',
        title: 'خطأ!',
        text: 'فشل في تحميل العقد',
        confirmButtonText: 'حسناً'
      });
    });
}

function submitDocumentForm(form, table) {
  const id = $(selectors.docId).val();
  const url = id ? `/rental_contracts/${id}` : '/rental_contracts';
  const formData = new FormData(form);
  const $submitBtn = $(form).find('button[type="submit"]');

  if (id) formData.append('_method', 'PUT');

  $submitBtn.prop('disabled', true);

  $.ajax({
    url,
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    headers: {
      'X-CSRF-TOKEN': $(selectors.csrfToken).attr('content')
    }
  })
    .done(() => {
      $(selectors.modalForm).modal('hide');
      Swal.fire({
        icon: 'success',
        title: id ? 'تم التحديث!' : 'تم الإضافة!',
        text: id ? 'تم تحديث العقد بنجاح' : 'تم إضافة العقد بنجاح',
        timer: 2000,
        showConfirmButton: false
      });
      table.ajax.reload(null, false);
      resetDocumentForm();
    })
    .fail(error => {
      if (error.status === 422) {
        handleValidationErrors(error.responseJSON.errors);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'خطأ!',
          text: 'حدث خطأ أثناء حفظ العقد',
          confirmButtonText: 'حسناً'
        });
      }
    })
    .always(() => {
      $submitBtn.prop('disabled', false);
    });
}

function handleValidationErrors(errors) {
  // إزالة التنسيقات القديمة
  $('.is-invalid').removeClass('is-invalid');
  $('.invalid-feedback').remove();

  let allErrors = '';
  let firstErrorField = null;

  for (const [field, messages] of Object.entries(errors)) {
    const input = $(`[name="${field}"], #${field}`);

    if (input.length > 0) {
      input.addClass('is-invalid');

      // عرض رسالة أسفل العنصر
      const errorHtml = `<div class="invalid-feedback">${messages[0]}</div>`;
      if (input.next('.invalid-feedback').length === 0) {
        input.after(errorHtml);
      }

      // حفظ أول حقل يحتوي على خطأ
      if (!firstErrorField) {
        firstErrorField = input;
      }
    }

    // تجميع الأخطاء للعرض في Swal
    allErrors += `• ${messages[0]}<br>`;
  }

  if (allErrors) {
    Swal.fire({
      icon: 'error',
      title: 'حدثت أخطاء في البيانات:',
      html: allErrors,
      confirmButtonText: 'حسناً'
    });
  }

  if (firstErrorField) {
    setTimeout(() => firstErrorField.focus(), 500);
  }
}
