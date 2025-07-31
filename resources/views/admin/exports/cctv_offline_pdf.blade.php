<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap CCTV Offline</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .info {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h2>Rekap CCTV Offline</h2>
    <p><strong>Periode:</strong> {{ ucfirst($range) }}</p>

    <table>
        <thead>
            <tr>
                <th>Sekolah</th>
                <th>Titik</th>
                <th>Last Seen</th>
                <th>Offline Sejak</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapOffline as $row)
                <tr>
                    <td>{{ $row->namaSekolah }}</td>
                    <td>{{ $row->namaTitik }}</td>
                    <td>{{ $row->last_seen ?? '-' }}</td>
                    <td>{{ $row->offline_since }}</td>
                    <td>{{ $row->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info">
        <p><strong>Total CCTV Offline:</strong> {{ $rekapOffline->count() }}</p>

        @php
            $frequents = $rekapOffline->groupBy('namaSekolah')->map->count();
            $mostFrequentSchool = $frequents->sortDesc()->keys()->first();
        @endphp

        <p><strong>Sekolah Paling Sering Offline:</strong> {{ $mostFrequentSchool ?? '-' }}</p>
        <p><strong>Dicetak Pada:</strong> {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>

</html>
