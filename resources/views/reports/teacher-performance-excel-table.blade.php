<table>
    <tr>
        <th colspan="7">รายงาน Performance อาจารย์</th>
    </tr>
    <tr>
        <td colspan="7">
            ช่วงเวลา {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}
            @if ($branch)
                | สาขา {{ $branch }}
            @endif
        </td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <tr>
        <th>อาจารย์</th>
        <th>รหัส</th>
        <th>สาขา</th>
        <th>ชั่วโมงสอน</th>
        <th>จำนวนคลาส</th>
        <th>จำนวนนักเรียน</th>
        <th>จำนวนการลา</th>
    </tr>
    @foreach ($rows as $row)
        <tr>
            <td>{{ $row['teacher']->full_name }}</td>
            <td>{{ $row['teacher']->teacher_code }}</td>
            <td>{{ $row['teacher']->branch ?: '-' }}</td>
            <td>{{ $row['hours'] }}</td>
            <td>{{ $row['class_count'] }}</td>
            <td>{{ $row['student_count'] }}</td>
            <td>{{ $row['leave_count'] }}</td>
        </tr>
    @endforeach
</table>
