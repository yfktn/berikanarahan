<?php
return [
    // sumber trigger adalah daftar pilihan dari berbagai table. Tambahkan sebuah query yang mengambil masing-masing
    // id, label dan juga model-nya. Model ini menggunakan titik sebagai pengganti karakter \ untuk mengurangi bug
    // karena nilai khusus untuk karakter \ di PHP.
    'sumber_trigger' => [
        "SELECT sm.id, CONCAT('Surat Masuk Dari ', dari, ' Nomor ', nomor, ' Hal ', hal) AS label, 'Yfktn\\SuratMasuk\\Models\\SuratMasuk' AS model from yfktn_suratmasuk_ as sm LEFT JOIN yfktn_berikanarahan_ as ba ON  ba.berdasarkan_id = sm.id AND ba.berdasarkan_type = 'Yfktn\\SuratMasuk\\Models\\SuratMasuk' WHERE ba.id IS NULL",
    ],
    'status' => [
        'LAGI_PROSES' => 'Lagi Proses',
        'STATUS_SELESAI' => 'Proses Selesai',
        'STATUS_DITUTUP' => 'Dihentikan',
        'DIKEMBALIKAN'   => 'Dikembalikan',
        'TERIMA_PROSES_LANJUT' => 'Diterima & Proses Lebih Lanjut',
    ]
];