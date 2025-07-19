<div class="container-fluid">
  <div class="row row-cols-2">
    {{-- 📄 بيانات العقد (Left Column) --}}
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-center mb-4">
            <h5 class="fw-bold mb-1">بيانات عقد الإيجار</h5>
            <p class="text-muted mb-0 badge bg-label-info">تفاصيل الأطراف ومعلومات العقد</p>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-user-circle me-2"></i>
            <div>
              <strong>اسم المؤجر:</strong>
              <div class="text-muted">{{ $rental_contract->lessor_name }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-user me-2"></i>
            <div>
              <strong>اسم المستأجر:</strong>
              <div class="text-muted">{{ $rental_contract->lessee_name }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-calendar-stats me-2"></i>
            <div>
              <strong>تاريخ تحرير العقد:</strong>
              <div class="text-muted">{{ $rental_contract->contract_date }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-calendar-up me-2"></i>
            <div>
              <strong>بداية الإيجار:</strong>
              <div class="text-muted">{{ $rental_contract->start_date }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-calendar-down me-2"></i>
            <div>
              <strong>نهاية الإيجار:</strong>
              <div class="text-muted">{{ $rental_contract->end_date }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-map-pin me-2"></i>
            <div>
              <strong>محل الإيجار:</strong>
              <div class="text-muted">{{ $rental_contract->rental_location }}</div>
            </div>
          </div>

          <div class="mb-4 d-flex align-items-center">
            <i class="icon-base ti tabler-currency-dollar me-2"></i>
            <div>
              <strong>قيمة الإيجار الشهري:</strong>
              <div class="text-muted">{{ number_format($rental_contract->rent_amount, 2) }} ج.م</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- 🖼️ عرض المرفق (Right Column) --}}
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          @if ($rental_contract->document_image_path)
            <div class="row mb-3">
              <a href="{{ asset('storage/' . $rental_contract->document_image_path) }}" download
                class="btn btn-sm btn-outline-primary">
                <i class="icon-base ti tabler-cloud-download icon-md me-2"></i> تحميل الملف
              </a>
            </div>
          @endif

          @if ($rental_contract->document_image_path)
            @php
              $relativePath = 'storage/' . $rental_contract->document_image_path;
              $fullUrl = asset($relativePath);
              $extension = strtolower(pathinfo($fullUrl, PATHINFO_EXTENSION));
            @endphp

            <div class="d-flex justify-content-center align-items-center">
              @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <img src="{{ $fullUrl }}" alt="صورة المستند" class="img-fluid rounded shadow"
                  style="max-height:1000px;">
              @elseif ($extension === 'pdf')
                @php
                  $filePath = storage_path('app/public/' . $rental_contract->document_image_path);
                  $base64 = base64_encode(file_get_contents($filePath));
                @endphp
                <iframe src="data:application/pdf;base64,{{ $base64 }}" width="100%" height="420"
                  class="border rounded" allowfullscreen></iframe>
              @else
                <p class="text-muted">نوع الملف غير مدعوم للعرض. <a href="{{ $fullUrl }}" target="_blank">تحميل
                    الملف</a></p>
              @endif
            </div>
          @else
            <div class="alert alert-secondary text-center m-0">لا يوجد ملف مرفق.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
