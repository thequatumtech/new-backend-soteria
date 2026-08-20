<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; margin: 0; padding: 0; }
        .report-container { width: 95%; max-width: 1200px; margin: 0 auto; }
        .report-header { margin-bottom: 16px; text-align: center; }
        .report-header h1 { font-size: 22px; margin-bottom: 4px; }
        .report-filters { font-size: 11px; color: #555; margin-bottom: 16px; }
        .report-group-title { margin: 20px 0 10px; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin: 0 auto 18px auto; }
        th, td { padding: 6px 8px; border: 1px solid #ccc; text-align: left; font-size: 10px; }
        th { background-color: #f6f6f6; }
        .total-row td { font-weight: bold; background-color: #f6f6f6; }
        .empty-state { padding: 18px; background: #f7f7f7; border: 1px solid #ddd; text-align: center; }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <h1>{{ $title }}</h1>
            @php $columnLabels = $column_labels ?? []; @endphp
        @if (!empty($filters))
            <div class="report-filters">
                @foreach ($filters as $label => $value)
                    @if (!empty($value))
                        <div><strong>{{ ucfirst(str_replace('_', ' ', $label)) }}:</strong> {{ $value }}</div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    @php $totalKey = $total_key ?? null; @endphp
    @php $totalKeys = $total_keys ?? []; @endphp
    @if (!empty($groups))
        @foreach ($groups as $group)
            <div class="report-group-title">{{ $group['group_name'] }}</div>
            <table>
                <thead>
                    <tr>
                        @foreach ($columns as $column)
                            <th>{{ $column_labels[$column] ?? ucwords(str_replace('_', ' ', $column)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group['rows'] as $row)
                        <tr>
                            @foreach ($columns as $column)
                                <td>{{ $row[$column] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        @foreach ($columns as $column)
                            <td>
                                @if ($loop->first)
                                    TOTAL
                                @elseif (!empty($totalKeys) && in_array($column, $totalKeys))
                                    {{ $group['totals']['total_' . $column] ?? '' }}
                                @elseif ($totalKey && $column === $totalKey)
                                    {{ $group['totals']['total_' . $totalKey] ?? '' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @endforeach
    @elseif (!empty($rows) && !empty($columns))
        <table>
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column_labels[$column] ?? ucwords(str_replace('_', ' ', $column)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($columns as $column)
                            <td>{{ $row[$column] ?? '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">No rows found for this report.</div>
    @endif
    </div>
</body>
</html>
