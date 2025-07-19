<div class="container-fluid">
  <div class="row">
    {{-- 📄 بيانات المستند (Left Column) --}}
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-center mb-4">
            <h5 class="fw-bold mb-1">- بيانات المستند -</h5>
            <p class="text-muted mb-0 badge bg-label-info">تفاصيل المستند</p>
          </div>

          <div class="mb-6 d-flex align-items-center">
            <i class="icon-base ti tabler-file-description text-primary me-2 mt-1"></i>
            <div class="flex-grow-1">
              <strong class="d-block mb-1 fs-5">اسم المستند</strong>
              <div class="text-muted text-center fs-6">{{ $company_document->name }}</div>
            </div>
          </div>

          <div class="mb-6 d-flex align-items-center">
            <i class="icon-base ti tabler-calendar-plus text-success me-2 mt-1"></i>
            <div class="flex-grow-1">
              <strong class="d-block mb-1 fs-5">تاريخ الإصدار</strong>
              <div class="text-muted text-center fs-6">{{ $company_document->issuance_date }}</div>
            </div>
          </div>

          <div class="mb-6 d-flex align-items-center">
            <i class="icon-base ti tabler-building-bank text-success me-2 mt-1"></i>
            <div class="flex-grow-1">
              <strong class="d-block mb-1 fs-5">جهة الإصدار</strong>
              <div class="text-muted text-center fs-6">{{ $company_document->issuing_authority }}</div>
            </div>
          </div>

          <div class="mb-6 d-flex align-items-center">
            <i class="icon-base ti tabler-refresh text-info me-2 mt-1"></i>
            <div class="flex-grow-1">
              <strong class="d-block mb-1 fs-5">تاريخ التجديد</strong>
              <div class="text-muted text-center fs-6">{{ $company_document->renewal_date ?? '—' }}</div>
            </div>
          </div>

        </div>
      </div>
    </div>

    {{-- 🖼️ عرض المرفق (Right Column) --}}
    <div class="col">
      <div class="card h-100">
        <div class="card-body d-flex flex-column">
          @if ($company_document->document_image_path)
            <div class="row mb-3">
              <a href="{{ asset('storage/' . $company_document->document_image_path) }}" download
                class="btn btn-sm btn-outline-primary">
                <i class="icon-base ti tabler-cloud-download icon-md me-2"></i> تحميل الملف
              </a>
            </div>
          @endif

          @if ($company_document->document_image_path)
            @php
              $relativePath = 'storage/' . $company_document->document_image_path;
              $fullUrl = asset($relativePath);
              $extension = strtolower(pathinfo($fullUrl, PATHINFO_EXTENSION));
            @endphp

            <div class="d-flex justify-content-center align-items-center flex-grow-1">
              @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <img src="{{ $fullUrl }}" alt="صورة المستند" class="img-fluid rounded shadow mh-100"
                  style="max-height: 600px">
              @elseif ($extension === 'pdf')
                @php
                  $filePath = storage_path('app/public/' . $company_document->document_image_path);
                  $base64 = base64_encode(file_get_contents($filePath));
                @endphp
                <iframe src="data:application/pdf;base64,{{ $base64 }}" width="100%" height="420"
                  class="border rounded" allowfullscreen></iframe>
              @else
                <p class="text-muted">نوع الملف غير مدعوم للعرض. <a href="{{ $fullUrl }}" target="_blank"
                    class="text-primary">تحميل الملف</a></p>
              @endif
            </div>
          @else
            <div class="alert alert-secondary text-center flex-grow-1 d-flex align-items-center justify-content-center">
              لا يوجد ملف مرفق.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
