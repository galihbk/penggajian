<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 70px;
        }

        .company-info {
            text-align: center;
            margin-top: 10px;
        }

        .slip-title {
            text-align: center;
            margin: 20px 0;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
        }

        .employee-info,
        .salary-table,
        .presence-info {
            margin-bottom: 20px;
        }

        .employee-info table,
        .salary-table table,
        .presence-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .employee-info td,
        .presence-info td {
            padding: 4px;
        }

        .salary-table th,
        .salary-table td {
            padding: 8px;
            border: 1px solid #000;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .signature-info {
            margin-left: 70%;
            text-align: center;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
        }

        .signature div:nth-child(3) {
            margin-bottom: 60px
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ url('') }}/assets/images/logo-icon.png" width="80" alt="">
        <div class="company-info">
            <strong>CV. MUGI JAYA</strong><br>
            Jl. Elsa Karang Wangkal, Pekuncen 53164
        </div>
    </div>

    <div class="slip-title">SLIP GAJI</div>

    <div class="employee-info">
        <table style="width: 100%;">
            <tr>
                <!-- KIRI -->
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>: {{ $data['nama'] }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>: {{ $data['jabatan'] }}</td>
                        </tr>
                        <tr>
                            <td>Id Karyawan</td>
                            <td>: {{ $data['id'] ?? '-' }}</td>
                        </tr>
                    </table>
                </td>

                <!-- KANAN -->
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr>
                            <td>No HP</td>
                            <td>: {{ $data['no_hp'] }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>: {{ $data['email'] }}</td>
                        </tr>
                        <tr>
                            <td>Bulan Gaji</td>
                            <td>: {{ $data['bulan'] }} {{ $data['tahun'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="salary-table">
        <table>
            <thead>
                <tr>
                    <th>PENDAPATAN</th>
                    <th class="text-right">NOMINAL</th>
                    <th>POTONGAN</th>
                    <th class="text-right">NOMINAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Honor Harian</td>
                    <td class="text-right">Rp {{ number_format($data['honor_harian'] * $data['hadir'], 0, ',', '.') }}
                    </td>
                    <td>
                        Potongan
                    </td>
                    <td class="text-right">
                        Rp {{ number_format($data['potongan'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>Honor Lembur</td>
                    <td class="text-right">Rp {{ number_format($data['honor_lembur'] * $data['lembur'], 0, ',', '.') }}
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                    </td>
                </tr>
                <tr>
                    <td><strong>Total Pendapatan</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($data['honor_harian'] * $data['hadir'] + $data['honor_lembur'] * $data['lembur'], 0, ',', '.') }}</strong>
                    </td>
                    <td><strong>Total Potongan</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($data['potongan'], 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Diterima</strong></td>
                    <td colspan="2" class="text-right"><strong>Rp
                            {{ number_format($data['total'], 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="presence-info">
        <table>
            <tr>
                <td>Kehadiran</td>
                <td>: {{ $data['hadir'] }} Hari</td>
            </tr>
            <tr>
                <td>Kehadiran Lembur</td>
                <td>: {{ $data['lembur'] }} Kali</td>
            </tr>
        </table>
    </div>

    <div class="signature">
        <div class="signature-info">
            <div>{{ $data['kota'] ?? 'Banyumas' }}, {{ now()->format('d M Y') }}</div>
            <div>Mengetahui</div>
            <div>Admin Keuangan</div>
            <div><strong>{{ $data['admin'] ?? 'Ayu' }}</strong></div>
        </div>
    </div>

</body>

</html>
