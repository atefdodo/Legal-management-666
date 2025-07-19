@extends('layouts.layoutMaster')
@section('title', 'لوحة التحكم')
<!-- Vendor Scripts -->
@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleave-zen/cleave-zen.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection
@section('content')
  <div class="row match-height g-4">

    {{-- عدد المستندات --}}
    <div class="col-md-3 col-sm-6">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body">
          <i class="tabler-file-description text-primary fs-1 mb-2"></i>
          <h6 class="text-muted">إجمالي المستندات</h6>
          <h3 class="fw-bold">{{ $totalDocuments }}</h3>
        </div>
      </div>
    </div>

    {{-- عدد عقود الإيجار --}}
    <div class="col-md-3 col-sm-6">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body">
          <i class="tabler-building-warehouse text-info fs-1 mb-2"></i>
          <h6 class="text-muted">عقود الإيجار</h6>
          <h3 class="fw-bold">{{ $totalContracts }}</h3>
        </div>
      </div>
    </div>

    {{-- مستندات سارية --}}
    <div class="col-md-3 col-sm-6">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body">
          <i class="tabler-circle-check text-success fs-1 mb-2"></i>
          <h6 class="text-muted">سارية</h6>
          <h3 class="fw-bold">{{ $activeDocuments }}</h3>
        </div>
      </div>
    </div>

    {{-- مستندات قريبة من الانتهاء --}}
    <div class="col-md-3 col-sm-6">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body">
          <i class="tabler-alert-triangle text-warning fs-1 mb-2"></i>
          <h6 class="text-muted">قارب على الانتهاء</h6>
          <h3 class="fw-bold">{{ $expiringSoonDocuments }}</h3>
        </div>
      </div>
    </div>

    {{-- مستندات منتهية --}}
    <div class="col-md-3 col-sm-6">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body">
          <i class="tabler-circle-x text-danger fs-1 mb-2"></i>
          <h6 class="text-muted">منتهية</h6>
          <h3 class="fw-bold">{{ $expiredDocuments }}</h3>
        </div>
      </div>
    </div>
  </div>
  {{-- 🔔 تنبيهات المستندات القريبة من الانتهاء --}}
  <div class="row mt-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning bg-opacity-10">
          <h5 class="card-title text-warning mb-0">
            <i class="tabler-alert-triangle me-1"></i>
            مستندات قريبة من الانتهاء
          </h5>
        </div>
        <div class="card-body">
          @forelse ($expiringSoonList as $doc)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
              <div>
                <strong class="text-dark">{{ $doc->name }}</strong><br>
                <small class="text-muted">جهة الإصدار: {{ $doc->issuing_authority }}</small>
              </div>
              <span class="badge bg-warning text-dark">
                ينتهي في: {{ \Carbon\Carbon::parse($doc->renewal_date)->format('Y-m-d') }}
              </span>
            </div>
          @empty
            <p class="text-muted mb-0">لا توجد مستندات قريبة من الانتهاء حالياً.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
