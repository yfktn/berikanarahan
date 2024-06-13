<?php namespace Yfktn\BerikanArahan\Models;

use Backend\Facades\BackendAuth;
use Model;
use ApplicationException;

/**
 * Model
 */
class Pesan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_berikanarahan_pesan';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'pesan' => 'required'
    ];

    public $belongsTo = [
        'arahan' => ['Yfktn\BerikanArahan\Models\BerikanArahan', 'key' => 'arahan_id'],
        'personil' => ['Backend\Models\User', 'key' => 'personil_id']
    ];

    public $attachMany = [
        'daftarDokumenLampiran' => ['System\Models\File', 'public' => false]
    ];

    public function beforeCreate()
    {
        // pastikan sebelum ini dibuat baru, tambahkan kode personil
        // ini merupakan user backend yang login
        $this->personil_id = BackendAuth::getUser()->id;
    }

    public function beforeDelete()
    {
        if(BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
            return true;
        }
        if($this->personil_id != BackendAuth::getUser()->id) {
            throw new ApplicationException('Bukan pemilik pesan ini!');
            return false;
        }
    }

    public function beforeUpdate()
    {
        if($this->personil_id != BackendAuth::getUser()->id) {
            throw new ApplicationException('Bukan pemilik pesan ini!');
            return false;
        }
        
    }

    public function getLabelNamaAttribute()
    {
        $user = $this->personil;
        $label = implode(' ', [
            $user->first_name,
            $user->last_name
        ]);
        return empty(trim($label)) ? 
            '(@' . $user->login .')': 
            $label;
    }

    public function filterFields($fields, $context = null)
    {
        // untuk melakukan update pada tampilan di form supaya bisa mendisable beberapa 
        // field maka di bagian BerikanArahan atau siapa saja yang menggunakan ini
        // harus melakukan implementasi terhadap Event backend.form.extendFields
        // coba lihat di BerikanArahan::__construct untuk contohnya.
        // if(BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
        //     return true;
        // }
        // \Log::info('Disini lagi...' . $this->personil_id . ' - ' . BackendAuth::getUser()->id);

        // if($this->personil_id != BackendAuth::getUser()->id) {
        //     \Log::debug('adakah?' . $fields->pesan->value);
        //     $fields->pesan->visible = true;
        //     $fields->daftarDokumenLampiran->readOnly = true;
        // }
        
    }
}
