<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Kompetisi</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            text-align: center;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 85%;
            margin: 0 auto;
            border: 15px solid #2c3e50;
            padding: 50px;
            margin-top: 30px;
            background-color: #ecf0f1;
        }
        .header {
            margin-bottom: 40px;
        }
        .title {
            font-size: 55px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 24px;
            color: #7f8c8d;
            margin-top: 10px;
        }
        .recipient {
            margin: 40px 0;
        }
        .name {
            font-size: 45px;
            font-weight: bold;
            color: #e74c3c;
            border-bottom: 2px solid #e74c3c;
            display: inline-block;
            padding-bottom: 10px;
            min-width: 400px;
        }
        .body-text {
            font-size: 20px;
            color: #34495e;
            margin: 30px 0;
        }
        .competition-name {
            font-size: 35px;
            font-weight: bold;
            color: #2980b9;
            margin: 20px 0;
        }
        .footer {
            margin-top: 60px;
            display: table;
            width: 100%;
        }
        .date, .signature-area {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
        }
        .date {
            text-align: left;
            padding-left: 50px;
            font-size: 18px;
            color: #7f8c8d;
        }
        .signature-area {
            text-align: right;
            padding-right: 50px;
        }
        .signature-line {
            border-top: 1px solid #2c3e50;
            width: 250px;
            display: inline-block;
            margin-top: 50px;
            padding-top: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title">Sertifikat Penghargaan</div>
            <div class="subtitle">Diberikan secara bangga kepada</div>
        </div>
        
        <div class="recipient">
            <div class="name">{{ $userName }}</div>
        </div>
        
        <div class="body-text">
            Atas partisipasinya sebagai peserta dalam kompetisi:
        </div>
        
        <div class="competition-name">
            {{ $competitionName }}
        </div>
        
        <div class="footer">
            <div class="date">
                Diberikan pada tanggal: <br>
                <strong>{{ date('d F Y') }}</strong>
            </div>
            <div class="signature-area">
                <div class="signature-line">
                    Ketua Panitia CompeteHub
                </div>
            </div>
        </div>
    </div>
</body>
</html>
