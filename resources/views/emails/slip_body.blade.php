<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji {{ $data['nama'] }}</title>
</head>

<body>
    <p>Yth. {{ $data['nama'] }},</p>

    <p>Berikut ini kami lampirkan slip gaji Anda untuk bulan {{ $data['bulan'] }} {{ $data['tahun'] }}.</p>

    <p>Terima kasih,<br>HRD</p>
</body>

</html>
