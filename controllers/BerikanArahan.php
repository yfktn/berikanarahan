<?php namespace Yfktn\BerikanArahan\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Exception;
use Illuminate\Support\Facades\Log;

class BerikanArahan extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';


    public $requiredPermissions = [
        'yfktn.berikan_arahan.manajer',
        'yfktn.berikan_arahan.penunjukan_personil'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Yfktn.BerikanArahan', 'main-menu-beriarah');
    }

    /**
     * Siapkan user yang membuat ini!
     * @param mixed $model 
     * @return void 
     */
    public function formBeforeCreate($model)
    {
        $model->pengarah_id = $this->user->id;
    }

    /**
     * Tampilkan informasi yang menjadi sumber referensi adanya arahan.
     * Pastikan bahwa model yang dijadikan sebagai sumber referensi melakukan
     * implementasi terhadap InterfaceTampilanSingkatTrigger!
     * @return mixed 
     * @throws Exception 
     */
    public function onTampilkanInformasiTrigger()
    {
        $errorStr = "";
        try {
            $idnya = post('idnya');
            $modelnya = post('modelnya');
            $modelnyaClass = str_replace('.', "\\", $modelnya);
            $modelObject = new $modelnyaClass;
            $this->vars['record'] = $modelObject->find($idnya);
        } catch(Exception $e) {
            $errorStr = $e->getMessage();
            $this->vars['errorStr'] = $errorStr;
            Log::error($errorStr . "\n" . $e->getTraceAsString());
        }
        return
            isset($errorStr[0]) ?
                $this->makePartial('informasitriggererror'):
                $this->makePartial('informasitrigger');
    }

    /**
     * Jangan enabled kan beberapa form tapi bisa untuk form yang lain!
     * Sesuaikan dengan siapa yang login!
     * @param mixed $form 
     * @param mixed $fields 
     * @return void 
     */
    public function formExtendFields($form, $fields)
    {
        if(!$this->user->hasAccess('yfktn.berikan_arahan.manajer')) {
            $fields['berdasarkan_str']->disabled = true;
            $fields['arahan']->disabled = true;
            $fields['deadline']->disabled = true;
        }
    }
}
