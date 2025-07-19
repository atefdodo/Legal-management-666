<button class="btn btn-sm btn-icon view-record badge-center rounded-pill bg-label-warning " data-id="{{ $row->id }}"
  title="عرض">
  <i class="icon-base ti tabler-eye icon-22px"></i>
</button>

<button class="btn btn-sm btn-icon edit-record badge-center rounded-pill bg-label-success" data-id="{{ $row->id }}"
  title="تعديل">
  <i class="icon-base ti tabler-edit icon-22px"></i>
</button>

<button class="btn btn-sm btn-icon delete-record badge-center rounded-pill bg-label-danger" data-id="{{ $row->id }}"
  title="حذف">
  <i class="icon-base ti tabler-trash icon-22px"></i>
</button>
</div>
