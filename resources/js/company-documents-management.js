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

  const table = $(selectors.table).DataTable({
    processing: true,
    deferRender: true,
    serverSide: true,
    ajax: '/company-documents-list',
    columns: [
      {
        data: 'id',
        orderable: false,
        searchable: false,
        render: data => `<input type="checkbox" class="form-check-input doc-select" value="${data}">`
      },
      { data: 'name' },
      { data: 'issuance_date', render: renderArabicDate },
      { data: 'issuing_authority' },
      { data: 'renewal_date', render: renderArabicDate },
      { data: 'status', render: renderStatusBadge },
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
      >`,
    columnDefs: [{ targets: '_all', className: 'text-center' }],
    language: { url: '/vendor/datatables/i18n/ar.json' },
    buttons: [
      {
        extend: 'collection',
        className: 'btn btn-label-secondary dropdown-toggle',
        text: '<i class="icon-base ti tabler-upload me-2 icon-sm"></i>تصدير المستند',
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

  return table;
}

function renderStatusBadge(data) {
  switch (data) {
    case 'ساري':
      return '<span class="badge bg-label-success">ساري</span>';
    case 'قارب على الانتهاء':
      return '<span class="badge bg-label-warning">قارب على الانتهاء</span>';
    case 'منتهي':
      return '<span class="badge bg-label-danger">منتهي</span>';
    default:
      return '<span class="badge bg-label-secondary">غير محدد</span>';
  }
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
        columns: [1, 2, 3, 4, 5]
      }
    },
    {
      extend: 'print',
      className: 'dropdown-item',
      text: '<i class="icon-base ti tabler-printer me-2"></i>طباعة المحدد',
      exportOptions: {
        rows: selectedRowsCondition,
        columns: [1, 2, 3, 4, 5]
      },
      customize: function (win) {
        $(win.document.body).css('direction', 'rtl');
        $(win.document.body).find('table').addClass('table table-bordered').css('text-align', 'right');
        const now = new Date().toLocaleString('ar-EG');
        $(win.document.body).append(`
          <div style="margin-top: 40px; text-align: center; font-size: 14px; color: #888;">
            تمت الطباعة بواسطة نظام إدارة المستندات - أ/ محمد عاطف بتاريخ: ${now}
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
      <span class="d-none d-sm-inline-block">إضافة مستند</span>
    `,
    className: 'btn btn-primary ms-2',
    attr: {
      'data-bs-toggle': 'modal',
      'data-bs-target': selectors.modalForm
    },
    action: function () {
      resetDocumentForm();
      $(selectors.modalLabel).text('إضافة مستند');
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
    Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'يرجى تحديد مستند واحد على الأقل', confirmButtonText: 'حسناً' });
    return;
  }
  window.open(`/export-documents/${format}?ids=${ids.join(',')}`, '_blank');
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
    text: 'هل أنت متأكد من حذف هذا المستند؟',
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
      url: `/company_documents/${id}`,
      type: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $(selectors.csrfToken).attr('content') }
    });

    Swal.fire({
      icon: 'success',
      title: 'تم الحذف!',
      text: 'تم حذف المستند بنجاح',
      timer: 2000,
      showConfirmButton: false
    });
    table.ajax.reload(null, false);
  } catch {
    Swal.fire({ icon: 'error', title: 'خطأ!', text: 'حدث خطأ أثناء محاولة الحذف', confirmButtonText: 'حسناً' });
  }
}

function loadDocumentForEdit(id) {
  $.get(`/company_documents/${id}/edit`)
    .done(data => {
      $(selectors.docId).val(data.id);
      $('#name').val(data.name);
      $('#issuance_date').val(data.issuance_date);
      $('#issuing_authority').val(data.issuing_authority);
      $('#renewal_date').val(data.renewal_date);
      $(selectors.modalLabel).text('تعديل مستند');
      $(selectors.modalForm).modal('show');
    })
    .fail(() => {
      Swal.fire({ icon: 'error', title: 'خطأ!', text: 'فشل في تحميل بيانات المستند', confirmButtonText: 'حسناً' });
    });
}

function viewDocument(id) {
  fetch(`/company_documents/${id}`)
    .then(res => res.text())
    .then(html => {
      $(selectors.viewBody).html(html);
      $(selectors.modalView).modal('show');
    })
    .catch(() => {
      Swal.fire({ icon: 'error', title: 'خطأ!', text: 'فشل في تحميل المستند', confirmButtonText: 'حسناً' });
    });
}

function submitDocumentForm(form, table) {
  const id = $(selectors.docId).val();
  const url = id ? `/company_documents/${id}` : '/company_documents';
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
    headers: { 'X-CSRF-TOKEN': $(selectors.csrfToken).attr('content') }
  })
    .done(() => {
      $(selectors.modalForm).modal('hide');
      Swal.fire({
        icon: 'success',
        title: id ? 'تم التحديث!' : 'تم الإضافة!',
        text: id ? 'تم تحديث المستند بنجاح' : 'تم إضافة المستند بنجاح',
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
        Swal.fire({ icon: 'error', title: 'خطأ!', text: 'حدث خطأ أثناء حفظ المستند', confirmButtonText: 'حسناً' });
      }
    })
    .always(() => {
      $submitBtn.prop('disabled', false);
    });
}

function handleValidationErrors(errors) {
  $('.form-control').removeClass('is-invalid');
  $('.invalid-feedback').remove();
  let allErrors = '';
  let firstErrorField = null;

  for (const [field, messages] of Object.entries(errors)) {
    const input = $(`#${field}`);
    input.addClass('is-invalid');
    if (!firstErrorField) firstErrorField = input;
    allErrors += `• ${messages[0]}<br>`;
  }

  if (allErrors) {
    Swal.fire({ icon: 'error', title: 'حدثت أخطاء في البيانات:', html: allErrors, confirmButtonText: 'حسناً' });
  }

  if (firstErrorField) {
    setTimeout(() => firstErrorField.focus(), 500);
  }
}
