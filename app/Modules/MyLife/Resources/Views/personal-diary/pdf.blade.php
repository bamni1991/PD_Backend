<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Diary Export</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&display=swap');

        body {
            font-family: 'Merriweather', Georgia, 'Times New Roman', serif;
            line-height: 1.25;
            color: #111;
            margin: 0;
            padding: 1cm;
            background-color: #fff;
            font-size: 8px;
            column-count: 2;
            column-gap: 15px;
            column-rule: 1px solid #eee;
        }

        h1.main-title {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
            column-span: all;
        }

        .diary-entry {
            margin-bottom: 8px;
            break-inside: avoid;
            page-break-inside: avoid;
            border-bottom: 1px dotted #ccc;
            padding-bottom: 4px;
            display: inline-block;
            width: 100%;
        }

        .diary-header {
            margin-bottom: 2px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            border-bottom: none;
            background-color: #f4f4f4;
            padding: 2px 4px;
            border-radius: 2px;
        }

        .diary-title {
            font-size: 9px;
            font-weight: 800;
            color: #000;
            margin: 0;
            text-transform: uppercase;
        }

        .diary-date {
            font-size: 8px;
            color: #444;
            font-style: normal;
        }

        .diary-content {
            font-size: 8px;
            text-align: justify;
            white-space: pre-wrap;
            color: #222;
            margin-top: 2px;
            padding: 0 2px;
        }

        .print-btn-container {
            text-align: right;
            margin-bottom: 5px;
            column-span: all;
        }

        .print-btn {
            font-size: 10px;
            padding: 5px 10px;
            background: #444;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                font-size: 7px;
                /* Extremely small for print */
                column-count: 2;
            }

            .diary-entry {
                margin-bottom: 6px;
                padding-bottom: 2px;
            }

            .diary-header {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="no-print print-btn-container">
        <button onclick="window.print()" class="print-btn">
            Print / Save as PDF
        </button>
    </div>

    <h1 class="main-title">My Personal Diary</h1>

    @foreach ($diaries as $diary)
        <article class="diary-entry">
            <header class="diary-header">
                <div class="diary-title">{{ $diary->title }}</div>
                <div class="diary-date">
                    {{ \Carbon\Carbon::parse($diary->entry_date)->format('d M, Y') }}
                </div>
            </header>
            <div class="diary-content">{{ $diary->content }}</div>
        </article>
    @endforeach

    <script>
        // Optional: Auto-print on load if desired
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>
