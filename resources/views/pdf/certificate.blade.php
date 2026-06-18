<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Certificado de Trámite</title>
    <style>
        @page {
            size: letter landscape;
            margin: 0.5cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            position: relative;
            min-height: 100vh;
        }

        .certificate {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 10px solid #1e40af;
            margin: 25px;
            padding: 8px;
        }

        .border-inner {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 4px solid #e6ba09;
            margin: 10px;
            padding: 30px 45px;
        }

        /* Esquina superior derecha: QR */
        .qr-corner {
            position: absolute;
            top: 5px;
            right: 5px;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .qr-corner img {
            width: 60px;
            height: 60px;
        }

        .qr-corner p {
            font-size: 6px;
            color: #94a3b8;
            margin: 2px 0 0;
        }

        /* Esquina inferior derecha: fecha/hora */
        .datetime-corner {
            position: absolute;
            bottom: 8px;
            right: 8px;
            background: #f1f5f9;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 8px;
            color: #475569;
            text-align: right;
            font-family: monospace;
        }

        .header {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .logo {
            font-size: 30px;
            font-weight: bold;
            color: #1e40af;
        }

        .subtitle {
            font-size: 15px;
            color: #64748b;
            margin-top: 3px;
        }

        .title {
            text-align: center;
            margin: 10px 0 15px 0;
            padding-top: 30px;
        }

        .title h1 {
            font-size: 27px;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        /* Párrafo corrido */
        .certificate-text {
            font-size: 18px;
            line-height: 1.5;
            text-align: justify;
            margin: 15px 0 20px 0;
            /* padding-top: 75px;  */
            padding: 35px 0;
        }

        .student-highlight {
            font-weight: bold;
            font-style: italic;
            color: #1e293b;
        }

        .program-highlight {
            font-weight: bold;
            color: #1e40af;
        }

        .grades-box {
            background: #f0fdf4;
            padding: 8px 15px;
            text-align: center;
            border: 1px solid #bbf7d0;
            margin: 15px auto;
            width: 50%;
            border-radius: 8px;
        }

        .grades-box .label {
            font-size: 15px;
            color: #64748b;
        }

        .grades-box .value {
            font-size: 30px;
            font-weight: bold;
            color: #16a34a;
        }

        .signatures {
            margin-top: 35px;
            text-align: center;
        }

        .signature-item {
            display: inline-block;
            width: 30%;
            text-align: center;
            margin: 0 1%;
            vertical-align: bottom;
        }

        .signature-img {
            max-width: 110px;
            max-height: 45px;
            margin-bottom: 6px;
        }

        .signature-line {
            border-top: 1px solid #1e293b;
            width: 70%;
            margin: 0 auto 8px auto;
            padding-top: 6px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 18px;
            color: #1e293b;
        }

        .signature-position {
            font-size: 16px;
            color: #64748b;
        }

        .signature-date {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .footer {
            /* margin-top: 20px; */
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    @php
        $type = ucfirst(__(str_replace('_', ' ', $procedure->certificate_type)));
        $paragraph = "Certifica que, según consta en el registro N° {$procedure->id}-{$procedure->created_at->format(
    'Y',
)}, el/la estudiante ";
        $paragraph .= "<span class='student-highlight'>{$procedure->student_name}</span>, ";
        $paragraph .= "RUN <span class='student-highlight'>{$procedure->student_identification}</span>, ";
        $paragraph .= "cursó el programa <span class='program-highlight'>{$type}</span>, ";
        $paragraph .= "durante <span class='program-highlight'>{$procedure->study_period} período(s)</span>";
        $signaturesData = [];
        foreach ($procedure->signatures as $sig) {
            $sigData = [
                'signer_name' => $sig->signer_name,
                'signer_position' => $sig->signer_position,
                'signed_at' => $sig->signed_at,
                'image_base64' => null,
            ];
            if ($sig->signature_image_path) {
                $imagePath = storage_path('app/public/' . $sig->signature_image_path);
                if (file_exists($imagePath)) {
                    $imageContent = file_get_contents($imagePath);
                    $sigData['image_base64'] = 'data:image/png;base64,' . base64_encode($imageContent);
                }
            }
            $signaturesData[] = $sigData;
        }
    @endphp
    <div class="certificate">
        <div class="border-inner">
            <div class="qr-corner">
                <img style="width: 100px; height:100px"
                    src={{ 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode(route('certificate.verify', $procedure->id)) . '&amp;size=50x50' }}>
                <p>Verificar</p>
            </div>
            <div class="header">
                <div class="logo">{{ strtoupper(config('app.name', 'Escuela de Idiomas')) }}</div>
                <div class="subtitle">Institución de Educación Superior</div>
            </div>
            <div class="title">
                <h1>{{ strtoupper(__(str_replace('_', ' ', $procedure->certificate_type))) }}</h1>
            </div>
            <div class="certificate-text">
                {!! $paragraph !!}
            </div>
            @if ($procedure->final_grades_average)
                <div class="grades-box">
                    <div class="label">PROMEDIO FINAL (escala 1.0 - 5.0)</div>
                    <div class="value">{{ $procedure->final_grades_average }}</div>
                </div>
            @endif

            <div class="signatures">
                @foreach ($signaturesData as $signature)
                    <div class="signature-item">
                        @if ($signature['image_base64'])
                            <img src="{{ $signature['image_base64'] }}" class="signature-img">
                        @else
                            <div class="signature-line"></div>
                        @endif
                        <div class="signature-name">{{ $signature['signer_name'] }}</div>
                        <div class="signature-position">{{ $signature['signer_position'] }}</div>
                        <div class="signature-date">{{ $signature['signed_at']->format('d/m/Y') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="datetime-corner">
                <div>Fecha de emisión:
                    {{ $procedure->generated_at ? $procedure->generated_at->format('d-m-Y | h:i A') : now()->format('d-m-Y | h:i A') }}
                </div>
                <div style="font-size: 8px; font-family: monospace; margin-top: 3px; word-break: break-all;">
                    Hash:
                    {{ substr($procedure->document_hash, 0, 16) }}...{{ substr($procedure->document_hash, -16) }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
