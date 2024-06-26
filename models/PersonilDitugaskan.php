<?php namespace Yfktn\BerikanArahan\Models;

use Backend\Facades\BackendAuth;
use Model;
use Db;
use Illuminate\Support\Facades\Log;
use Yfktn\BerikanArahan\Classes\FonntePemberianArahan;
use Yfktn\ToolsKu\Classes\Traits\RevisionableWithCreate;
use Yfktn\ToolsKu\Classes\Traits\RevisionTriggerNotification;

/**
 * Model
 */
class PersonilDitugaskan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use RevisionableWithCreate;
    use RevisionTriggerNotification;

    protected $revisionTriggerFields = [
        'personil_id' => [FonntePemberianArahan::class, 'personilDipilih'],
        'createdHandlerAction' => [FonntePemberianArahan::class, 'personilDipilih'],
        // 'deletedHandlerAction' => [FonntePemberianArahan::class, 'personilDikeluarkan'],
    ];
    
    protected $revisionable = ['personil_id'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_berikanarahan_personil';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'personil_id' => 'required',
        'arahan_id' => 'required'
    ];

    public $belongsTo = [
        'arahan' => ['Yfktn\BerikanArahan\Models\BerikanArahan', 'key' => 'arahan_id'],
        'personil' => ['Backend\Models\User', 'key' => 'personil_id']
    ];

    public $morphMany = [
        'revision_history' => [\System\Models\Revision::class, 'name' => 'revisionable']
    ];

    public function getRevisionableUser()
    {
        return BackendAuth::getUser()->id;
    }

    /**
     * Jangan tampilkan yang sudah dipilih!
     * @param mixed $fieldName 
     * @param mixed $value 
     * @param mixed $formData 
     * @return mixed 
     */
    public function loadUserIdNya($fieldName, $value, $formData)
    {
        // nilai ini didapatkan dari data yang dimasukkan melalui ajax, pada
        // setting waktu update form utama / parentnya.
        $arahan_id = post('arahan_id');
        // Log::debug($formData->arahan_id . '-' . $formData->personil_id . '-' . $arahan_id);
        return Db::table('backend_users')
            ->whereNotExists(function($query) use($arahan_id){
                // gunakan selectRaw
                $query->selectRaw('personil_id')
                    ->from('yfktn_berikanarahan_personil')
                        ->whereRaw('yfktn_berikanarahan_personil.personil_id = backend_users.id')
                        ->where('yfktn_berikanarahan_personil.arahan_id', $arahan_id);
            })
            ->selectRaw('backend_users.id, concat(backend_users.login, " (", backend_users.email, ")") as logininfo')
            ->pluck('logininfo', 'backend_users.id');
    }
}
