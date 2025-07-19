@extends('layouts/layoutMaster')

@section('title', 'عقود الإيجار')

<!-- Vendor Styles -->
@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
  @vite('resources/js/rental-contracts-management.js')
@endsection

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h4 class="card-title">قائمة عقود الإيجار</h4>
    </div>
    <div class="card-body">
      <table class="table datatables-documents table-bordered">
        <thead>
          <tr>
            <th class="text-center"><input type="checkbox" class="form-check-input" id="select-all" /></th>
            <th class="text-center">اسم المؤجر</th>
            <th class="text-center">اسم المستأجر</th>
            <th class="text-center">تاريخ العقد</th>
            <th class="text-center">بداية الإيجار</th>
            <th class="text-center">نهاية الإيجار</th>
            <th class="text-center">قيمة الإيجار</th>
            <th class="text-center">إجراءات</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  @include('rental_contracts.partials.form')
  @include('rental_contracts.partials.show')
@endsection
