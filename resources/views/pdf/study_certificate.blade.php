<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Constancia de Estudios</title>
    <style>
        @page {
            size: letter;
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
            padding: 25px 35px;
            overflow-y: auto;
        }

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
            margin-bottom: 15px;
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
            margin: 15px 0;
        }

        .title h1 {
            font-size: 27px;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }

        .student-info {
            text-align: center;
            margin: 25px 0;
        }

        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            font-style: italic;
        }

        .student-detail {
            font-size: 14px;
            color: #475569;
            margin-top: 8px;
        }

        .certificate-text {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
            margin: 25px 0;
            padding: 0 15px;
        }

        .program-highlight {
            font-weight: bold;
            color: #1e40af;
        }

        .periods-box {
            background: #f8fafc;
            padding: 15px 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
            margin: 20px auto;
            width: 70%;
            border-radius: 8px;
        }

        .periods-box .label {
            font-size: 12px;
            color: #64748b;
        }

        .periods-box .value {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
        }

        .signatures {
            margin-top: 30px;
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
            font-size: 12px;
            color: #1e293b;
        }

        .signature-position {
            font-size: 10px;
            color: #64748b;
        }

        .signature-date {
            font-size: 8px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    @php
        $studentName = $procedure->student_name;
        $studentRut = $procedure->student_identification;
        $program = ucfirst(__(str_replace('_', ' ', $procedure->program)));
        $periods = $procedure->study_period;

        $verificationUrl = route('certificate.verify', $procedure->id);

        $paragraph = 'Se deja constancia que el/la estudiante ';
        $paragraph .= "<span class='student-highlight' style='font-weight: bold; font-style: italic;'>{$studentName}</span>, ";
        $paragraph .= "RUN <span class='student-highlight' style='font-weight: bold; font-style: italic;'>{$studentRut}</span>, ";
        $paragraph .= "ha cursado y aprobado el programa de <span class='program-highlight'>{$program}</span>, ";
        $paragraph .= "durante <span class='program-highlight'>{$periods} períodos académicos</span> en esta institución.";
    @endphp

    <div class="certificate">
        <div class="border-inner">
            <div class="qr-corner">
                <img style="width: 80px; height:80px"
                    src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($verificationUrl) }}&size=80x80">
                <p>Verificar</p>
            </div>

            <div class="header">
                <div class="logo">{{ strtoupper(config('app.name', 'Escuela de Idiomas')) }}</div>
                <div class="subtitle">Institución de Educación Superior</div>
            </div>

            <div class="title">
                <h1>CONSTANCIA DE ESTUDIOS</h1>
            </div>

            <div class="student-info">
                <div class="student-name">{{ $studentName }}</div>
                <div class="student-detail">RUT: {{ $studentRut }}</div>
            </div>

            <div class="certificate-text">
                {!! $paragraph !!}
            </div>

            <div class="periods-box">
                <div class="value">{{ $procedure->study_period }}</div>
                <div class="label">períodos cursados y aprobados</div>
            </div>

            <div class="signatures">
                @foreach ($procedure->signatures as $signature)
                    <div class="signature-item">
                        @php
                            $imageBase64 = null;
                            if ($signature->signature_image_path) {
                                $imagePath = storage_path('app/public/' . $signature->signature_image_path);
                                if (file_exists($imagePath)) {
                                    $imageBase64 =
                                        'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
                                }
                            }
                        @endphp

                        @if ($imageBase64)
                            <img src="{{ $imageBase64 }}" class="signature-img">
                        @else
                            <div class="signature-line"></div>
                        @endif
                        <div class="signature-name">{{ $signature->signer_name }}</div>
                        <div class="signature-position">{{ $signature->signer_position }}</div>
                        <div class="signature-date">{{ $signature->signed_at->format('d/m/Y') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="datetime-corner">
                <div>Fecha de emisión:
                    {{ $procedure->generated_at ? $procedure->generated_at->format('d-m-Y | h:i A') : now()->format('d-m-Y | h:i A') }}
                </div>
                @if ($procedure->document_hash)
                    <div style="font-size: 7px; font-family: monospace; margin-top: 3px; word-break: break-all;">
                        Hash:
                        {{ substr($procedure->document_hash, 0, 16) }}...{{ substr($procedure->document_hash, -16) }}
                    </div>
                @endif
            </div>

            <div class="footer">
                Documento oficial - Constancia de estudios válida con las firmas autorizadas
            </div>
        </div>
    </div>
</body>

</html>
