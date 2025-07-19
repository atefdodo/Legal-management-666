<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>مستندات الشركة</title>
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
      font-size: 14px;
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

  <h2>مستندات الشركة</h2>

  <table>
    <thead>
      <tr>
        <th>نوع الوثيقة</th>
        <th>تاريخ الإصدار</th>
        <th>جهة الإصدار</th>
        <th>تاريخ التجديد</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($documents as $doc)
        <tr>
          <td>{{ $doc->name ?? '—' }}</td>
          <td>{{ $doc->issuance_date ?? '—' }}</td>
          <td>{{ $doc->issuing_authority ?? '—' }}</td>
          <td>{{ $doc->renewal_date ?? '—' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

</body>

</html>
