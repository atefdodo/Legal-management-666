<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>عقود الإيجار</title>
  <style>
    body {
      font-family: 'amiri', sans-serif;
      direction: rtl;
      text-align: right;
      line-height: 1.8;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 10px;
      font-size: 13px;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }
  </style>
</head>

<body>

  <h2>عقود الإيجار</h2>

  <table>
    <thead>
      <tr>
        <th>اسم المؤجر</th>
        <th>اسم المستأجر</th>
        <th>تاريخ العقد</th>
        <th>بداية الإيجار</th>
        <th>نهاية الإيجار</th>
        <th>محل الإيجار</th>
        <th>قيمة الإيجار</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($contracts as $contract)
        <tr>
          <td>{{ $contract->lessor_name ?? '—' }}</td>
          <td>{{ $contract->lessee_name ?? '—' }}</td>
          <td>{{ $contract->contract_date ?? '—' }}</td>
          <td>{{ $contract->start_date ?? '—' }}</td>
          <td>{{ $contract->end_date ?? '—' }}</td>
          <td>{{ $contract->rental_location ?? '—' }}</td>
          <td>{{ number_format($contract->rent_amount, 2) }} ج.م</td>
        </tr>
      @endforeach
    </tbody>
  </table>

</body>

</html>
