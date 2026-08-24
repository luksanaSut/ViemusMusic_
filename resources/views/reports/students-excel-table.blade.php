<table>
    <tr>
        <th colspan="2">รายงานนักเรียน</th>
    </tr>
    <tr>
        <td>ช่วงเวลา</td>
        <td>{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</td>
    </tr>
    <tr><td></td><td></td></tr>

    <tr>
        <th>สรุป</th>
        <th>จำนวน</th>
    </tr>
    <tr>
        <td>จำนวนนักเรียนทั้งหมด</td>
        <td>{{ $summary['total'] }}</td>
    </tr>
    <tr>
        <td>นักเรียนใหม่ในช่วงที่เลือก</td>
        <td>{{ $summary['new'] }}</td>
    </tr>
    <tr><td></td><td></td></tr>

    <tr>
        <th>แยกตามคอร์ส</th>
        <th>จำนวนนักเรียน</th>
    </tr>
    @foreach ($byCourse as $row)
        <tr>
            <td>{{ $row->label }}</td>
            <td>{{ $row->total }}</td>
        </tr>
    @endforeach
    <tr><td></td><td></td></tr>

    <tr>
        <th>แยกตามเครื่องดนตรี</th>
        <th>จำนวนนักเรียน</th>
    </tr>
    @foreach ($byInstrument as $row)
        <tr>
            <td>{{ $row->label }}</td>
            <td>{{ $row->total }}</td>
        </tr>
    @endforeach
    <tr><td></td><td></td></tr>

    <tr>
        <th>แยกตามสาขา</th>
        <th>จำนวนนักเรียน</th>
    </tr>
    @foreach ($byBranch as $row)
        <tr>
            <td>{{ $row['label'] }}</td>
            <td>{{ $row['total'] }}</td>
        </tr>
    @endforeach
</table>
