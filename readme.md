# Plugin OctoberCMS Pemberian Arahan

Digunakan untuk melakukan pemberian arahan untuk personil user yang sebelumnya sudah ditambahkan sebagai administrator.

## Menambahkan Pada Daftar Trigger

Supaya dapat diload pada daftar arahan yang belum ditambahkan, maka Plugin harus melakukan implementasi terhadap `Event::listen('yfktn.berikanarahan.layananbelumdiarahkan')`.

Sebagai contoh: Plugin `PengajuAturan` memiliki model `Yfktn\PengajuAturan\Models\Pengajuan` yang akan menghandle tampilan untuk trigger yang akan dijadikan referensi pemberian arahan. Maka dapat dibuat di boot:

```php
public function boot()
    {
        // pastikan untuk pengarahan ini dimasukkan dalam proses yang harus diarahkan.
        Event::listen('yfktn.berikanarahan.layananbelumdiarahkan', function() {
            $sql = <<<SQLNYA
SELECT 
    sm.id, 
    CONCAT('Pengajuan Aturan ', perihal) AS label,
    'Yfktn\\PengajuAturan\\Models\\Pengajuan' AS model
FROM yfktn_pengajuaturan_pengajuan as sm 
LEFT JOIN yfktn_berikanarahan_ as ba ON ba.berdasarkan_id = sm.id 
    AND ba.berdasarkan_type = 'Yfktn\\PengajuAturan\\Models\\Pengajuan' WHERE ba.id IS NULL
SQLNYA;
            $data = Db::select(str_replace("\\", "\\\\", $sql));
            if(!$data) {
                return [];
            }
            $a = [];
            foreach($data as $d) {
                $a[$d->id . '|' . $d->model] = $d->label;
            }
            return $a;
        });
    }
```

Pada prinsipnya, nilai yang harus dikembalikan adalah array assoc dengan format:
- di index array adalah pola `<id>`|<`string namespace model>`
- di bagian value adalah label yang mau ditampilkan

### menambahan event pencarian trigger

Agar trigger dapat difilter atau melakukan pencarian, maka Lakukan implementasi `Event::listen('yfktn.berikanarahan.layananpencariantrigger')`, tempatkan di boot plugin yang menggunakan.

```php
        Event::listen('yfktn.berikanarahan.layananpencariantrigger', function($condition, $value) {
            if($condition === 'equals') {
                $sql = <<<SQLNYA
                    SELECT ba.id FROM yfktn_suratmasuk_ sm 
                    JOIN yfktn_berikanarahan_ ba ON ba.berdasarkan_id = sm.id
                    WHERE sm.nomor = :value
                SQLNYA;
            } else {
                $sql = <<<SQLNYA
                    SELECT ba.id FROM yfktn_suratmasuk_ sm 
                    JOIN yfktn_berikanarahan_ ba ON ba.berdasarkan_id = sm.id
                    WHERE sm.dari LIKE '%{:value}%' 
                        OR sm.nomor LIKE '%{:value}%' 
                        OR sm.hal LIKE '%{:value}%'
                SQLNYA;
            }
            return DB::select($sql, [':value' => $value]);
        });
```

### Implementasikan interface InterfaceTampilanSingkatTrigger

Pada model yang dijadikan sebagai referensi, jangan lupa untuk melakukan implementasi terhadap fungsi yang dibuat saat melakukan implementasi `\Yfktn\BerikanArahan\Classes\InterfaceTampilanSingkatTrigger`.