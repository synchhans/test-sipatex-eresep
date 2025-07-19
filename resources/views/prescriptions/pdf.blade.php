<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resep - {{ $resep->nomor_resep }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 10px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #555; }
        .prescription-item { margin-bottom: 20px; page-break-inside: avoid; }
        .item-line { margin-bottom: 5px; font-size: 16px; line-height: 1.4; }
        .item-line strong { font-size: 18px; }
        .item-signa { margin-left: 30px; }
        .racikan-components { margin-left: 35px; font-size: 14px; color: #444; }
        .racikan-components p { margin: 2px 0; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>RESEP DIGITAL</h1>
            <p><strong>No. Resep:</strong> {{ $resep->nomor_resep }}</p>
            <p><strong>Tanggal:</strong> {{ $resep->created_at->format('d F Y') }}</p>
        </div>
        
        @foreach($resep->items as $item)
            <div class="prescription-item">
                @if($item->jenis === 'non_racikan')
                    <div class="item-line"><strong>R/</strong>   {{ $item->obat->obatalkes_nama }}   No. {{ $item->jumlah }}</div>
                    <div class="item-line item-signa">S.   {{ $item->signa->signa_nama }}</div>
                @else
                    <div class="item-line"><strong>R/</strong>   {{ $item->nama_racikan }}   No. {{ $item->jumlah }}</div>
                     <div class="racikan-components">
                        <p>m.f. pulv. dtd</p>
                        @foreach($item->racikanItems as $racikanItem)
                            <p>- {{ $racikanItem->obat->obatalkes_nama }}   (qty: {{ $racikanItem->jumlah }})</p>
                        @endforeach
                    </div>
                    <div class="item-line item-signa">S.   {{ $item->signa->signa_nama }}</div>
                @endif
            </div>
            @if(!$loop->last)
                <hr style="border: 0; border-top: 1px dashed #ccc; margin: 15px 50px;">
            @endif
        @endforeach

    </div>
    <div class="footer">
        Semoga lekas sembuh.
    </div>
</body>
</html>