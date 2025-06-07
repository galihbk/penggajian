<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Hadir</th>
            <th>Lembur</th>
            <th>Honor Harian</th>
            <th>Honor Lembur</th>
            <th>Potongan</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['jabatan'] }}</td>
                <td>{{ $item['hadir'] }}</td>
                <td>{{ $item['lembur'] }}</td>
                <td>{{ number_format($item['honor_harian'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['honor_lembur'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['potongan'], 0, ',', '.') }}</td>
                <td>{{ number_format($item['total'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
