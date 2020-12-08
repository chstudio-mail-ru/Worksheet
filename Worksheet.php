<?php

namespace app\models;

use app\components\BaseARecord;
use app\components\DateValidator;
use app\components\UploadFileBehavior;
use Yii;

/**
 * This is the model class for table "worksheet".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $birthday
 * @property integer $citizenship_id
 * @property string $citizenship_other
 * @property integer $source_id
 * @property string $source_fio
 * @property string $source_other
 * @property string $position
 * @property integer $salary
 * @property integer $birth_country_id
 * @property string $birth_country_other
 * @property integer $birth_city_id
 * @property string $birth_city_other
 * @property integer $registration_country_id
 * @property integer $registration_city_id
 * @property string $registration_country_other
 * @property string $registration_city_other
 * @property integer $registration_index
 * @property string $registration_street
 * @property string $registration_house
 * @property integer $registration_flat
 * @property integer $living_country_id
 * @property integer $living_city_id
 * @property string $living_country_other
 * @property string $living_city_other
 * @property integer $living_index
 * @property string $living_street
 * @property string $living_house
 * @property integer $living_flat
 * @property string $phone
 * @property string $emergency_phone
 * @property string $emergency_fio
 * @property integer $marital_status
 * @property string $marital_other
 * @property integer $child_num
 * @property string $driver_license
 * @property integer $computer_word
 * @property integer $computer_excel
 * @property integer $computer_outlook
 * @property integer $computer_1c
 * @property integer $computer_sap
 * @property string $computer_other
 * @property string $previous_like
 * @property string $motivation_other
 * @property string $demotivation_other
 * @property integer $documents_tk
 * @property integer $documents_inn
 * @property integer $documents_wb
 * @property integer $documents_snils
 * @property integer $documents_med
 * @property integer $sport_fb
 * @property integer $sport_vb
 * @property integer $sport_bb
 * @property integer $sport_pp
 * @property integer $sport_arm
 * @property integer $sport_run
 * @property string $sport_other
 * @property string $who_are_you_in_company
 * @property integer $familiar
 * @property integer $when_now
 * @property string $when_date
 * @property string $file
 * @property string $date_add
 * @property integer $active
 *
 * @property array WorkDemotivations
 * @property array WorkMotivations
 * //@property array WorkDocuments
 * @property array WorkEducations
 * @property array WorkJobs
 * @property array WorkRelatives
 * @property array WorkFamiliars
 * //@property array WorkSports
 * @property array WorkCheckboxes
 * @property array WorkRecommends
 */

class Worksheet extends BaseARecord
{
    public $living_same;
    public $living_country_id_hidden;
    public $living_city_id_hidden;
    public $relatives_status;
    public $relatives_fio;
    public $relatives_birthday;
    public $educations_type;
    public $educations_title;
    public $educations_speciality;
    public $educations_date;
    public $jobs_date_begin;
    public $jobs_date_end;
    public $jobs_company;
    public $jobs_status;
    public $jobs_country_id;
    public $jobs_country_other;
    public $jobs_city_id;
    public $jobs_city_other;
    public $familiars_type;
    public $familiars_status;
    public $familiars_fio;
    public $motivations_level;
    public $demotivations_level;
    public $checkboxes_id;
    public $recommends_fio;
    public $recommends_status;
    public $recommends_company;
    public $recommends_phone;
    public $agree;

    public $docDir = '/worksheet-resumes';
    /**
     * Поведения
     * @return array
     */
    /*public function behaviors()
    {
        return array_merge_recursive(parent::behaviors(), [
            'uploadFileBehavior' => [
                'class' => UploadFileBehavior::class,
                'attributes' => [
                    'file'
                ]
            ],
        ]);
    }*/

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'worksheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['motivation_other', 'demotivation_other'], 'string', 'max' => 512],
            [['source_fio', 'source_other', 'position', 'emergency_fio', 'computer_other', 'previous_like', 'sport_other', 'who_are_you_in_company'], 'string', 'max' => 100],
            [['first_name', 'last_name', 'middle_name', 'citizenship_other', 'birth_country_other', 'birth_city_other', 'registration_country_other', 'registration_city_other', 'registration_street', 'living_country_other', 'living_city_other', 'living_street', 'marital_other'], 'string', 'max' => 50],
            [['registration_house', 'living_house', 'phone', 'emergency_phone'], 'string', 'max' => 20],
            [['birthday'], 'date', 'format' => 'php:d.m.Y', 'message' => 'Неправильный формат'],
            [['when_date'], 'date', 'format' => 'php:d.m.Y', 'skipOnEmpty' => false, 'message' => 'Неправильный формат',
                'when' => function () {
                    $checked = false;
                    if (preg_match("/^\d\d\.\d\d\.\d\d\d\d$/", $this->when_date)) {
                        preg_match_all("/^(\d\d)\.(\d\d)\.(\d\d\d\d)$/", $this->when_date, $matches);
                        $day = $matches[1][0];
                        $month = $matches[2][0];
                        $year = $matches[3][0];
                        $checked = checkdate ( $month, $day , $year);
                    }
                    if ($this->when_now == 1 && !$checked) {
                        $this->addError('when_date', 'Неправильный формат');
                    }
                },
            ],
            [['citizenship_id', 'source_id', 'birth_country_id', 'birth_city_id', 'registration_country_id', 'registration_city_id', 'living_country_id', 'living_city_id', 'registration_index', 'registration_flat', 'living_index', 'living_flat', 'marital_status', 'computer_word', 'computer_excel', 'computer_excel', 'computer_outlook', 'computer_1c', 'computer_sap', 'child_num'], 'integer'],
            [['child_num'], 'in', 'range' => [0, 1, 2, 3, 4, 5], 'message' => 'Выберите количество детей'],
            [['marital_status'], 'required', 'message' => 'Выберите семейное положение'],
            [['marital_status'], 'in', 'range' => [0, 1, 2, 3]],
            ['marital_other', 'required',
                'when' => function () {
                    if ($this->marital_status == 3 && trim($this->marital_other) == "") {
                        $this->addError('marital_other', 'Укажите семейное положение');
                    }
                },
            ],
            [['salary'],  'match', 'pattern' => '/^[\d\s]+$/'],
            [['computer_word', 'computer_excel', 'computer_excel', 'computer_outlook', 'computer_1c', 'computer_sap', 'familiar'], 'in', 'range' => [0, 1, 2]],
            [['living_same', 'when_now', 'documents_tk', 'documents_inn', 'documents_wb', 'documents_snils', 'documents_med', 'sport_fb', 'sport_vb', 'sport_bb', 'sport_pp', 'sport_arm', 'sport_run'], 'in', 'range' => [0, 1]],

            [['first_name'], 'required', 'message' => 'Укажите имя'],
            [['last_name'], 'required', 'message' => 'Укажите фамилию'],

            //[['citizenship_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkCitizenship::class, 'targetAttribute' => ['citizenship_id' => 'id']],
            [['citizenship_id'], function ($attribute, $params) {
                if ($this->citizenship_id != -1) {
                    $existModel = WorkCitizenship::getById($this->citizenship_id);
                    if (!$existModel) {
                        $this->addError($attribute, 'Укажите гражданство');
                    }
                }
            }],
            ['citizenship_other', 'required',
                'when' => function () {
                    if ($this->citizenship_id == -1 && trim($this->citizenship_other) == "") {
                        $this->addError('citizenship_other', 'Укажите страну');
                    }
                },
            ],
            //[['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkSource::class, 'targetAttribute' => ['source_id' => 'id'], 'message' => 'Выберите источник'],
            [['source_id'], function ($attribute, $params) {
                if ($this->source_id != -1) {
                    $existModel = WorkSource::getById($this->source_id);
                    if (!$existModel) {
                        $this->addError($attribute, 'Выберите источник');
                    }
                }
            }],
            ['source_fio', 'required',
                'when' => function () {
                    if ($this->source_id == 6 && trim($this->source_fio) == "") {
                        $this->addError('source_fio', 'Укажите сотрудника');
                    }
                },
            ],
            ['source_other', 'required',
                'when' => function () {
                    if ($this->source_id == -1 && trim($this->source_other) == "") {
                        $this->addError('source_other', 'Укажите источник');
                    }
                },
            ],
            [['birth_country_id'], 'required', 'when' => function () {
                if ($this->birth_country_id == -1 && trim($this->birth_country_other) == "") {
                    $this->addError('birth_country_other', 'Укажите страну, где вы родились');
                } elseif (WorkCountry::getById($this->birth_country_id) === false) {
                    $this->addError('birth_country_id', 'Укажите страну, где вы родились');
                }
            }],
            [['birth_city_id'], 'required', 'when' => function () {
                if ($this->birth_city_id == -1 && trim($this->birth_city_other) == "") {
                    $this->addError('birth_city_other', 'Укажите населенный пункт, где вы родились');
                } elseif (WorkCity::getById($this->birth_city_id) === false) {
                    $this->addError('birth_city_id', 'Укажите населенный пункт, где вы родились');
                }
            }],
            [['registration_country_id'], 'required', 'when' => function () {
                if ($this->registration_country_id == -1 && trim($this->registration_country_other) == "") {
                    $this->addError('registration_country_other', 'Укажите страну, где вы прописаны');
                } elseif (WorkCountry::getById($this->registration_country_id) === false) {
                    $this->addError('registration_country_id', 'Укажите страну, где вы прописаны');
                }
            }],
            [['registration_city_id'], 'required', 'when' => function () {
                if ($this->registration_city_id == -1 && trim($this->registration_city_other) == "") {
                    $this->addError('registration_city_other', 'Укажите населенный пункт, где вы прописаны');
                } elseif (WorkCity::getById($this->registration_city_id) === false) {
                    $this->addError('registration_city_id', 'Укажите населенный пункт, где вы прописаны');
                }
            }],
            [['living_country_id'], 'required', 'when' => function () {
                if ($this->living_country_id == -1 && trim($this->living_country_other) == "") {
                    $this->addError('living_country_other', 'Укажите страну, где вы проживаете');
                } elseif (WorkCountry::getById($this->living_country_id) === false) {
                    $this->addError('living_country_id', 'Укажите страну, где вы проживаете');
                }
            }],
            [['living_city_id'], 'required', 'when' => function () {
                if ($this->living_city_id == -1 && trim($this->living_city_other) == "") {
                    $this->addError('living_city_other', 'Укажите населенный пункт, где вы проживаете');
                } elseif (WorkCity::getById($this->living_city_id) === false) {
                    $this->addError('living_city_id', 'Укажите населенный пункт, где вы проживаете');
                }
            }],
            [['living_country_id_hidden'], 'safe'],
            [['living_city_id_hidden'], 'safe'],
            [['checkboxes_id'], 'each', 'rule' => ['integer']],

            [['motivations_level', 'demotivations_level'], 'each', 'rule' => ['integer', 'min' => 1, 'max' => 8]],

            [['relatives_status', 'relatives_fio'], 'each', 'rule' => ['safe']],
            [['relatives_birthday'], 'each', 'rule' => [DateValidator::class]],
            [['educations_type', 'educations_title', 'educations_speciality', 'educations_date'], 'each', 'rule' => ['safe']],
            [['jobs_date_begin', 'jobs_date_end', 'jobs_company', 'jobs_status', 'jobs_country_id', 'jobs_country_other', 'jobs_city_id', 'jobs_city_other'], 'each', 'rule' => ['safe']],
            [['familiars_type', 'familiars_fio', 'familiars_status'], 'each', 'rule' => ['safe']],
            [['recommends_fio', 'recommends_status', 'recommends_company', 'recommends_phone'], 'each', 'rule' => ['safe']],

            ['driver_license', 'safe'],

            [['file'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'jpg', 'jpeg', 'gif', 'png', 'zip', 'rar'], 'wrongExtension'=>'Возможно приложить документ (архив) или изображение в формате {extensions}'],
            [['file'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => false, 'maxSize' => 1024*1024*2, 'tooBig' => 'Возможно приложить документ (архив) или изображение до 2Mb'],
            ['agree', 'compare', 'compareValue' => 1, 'message' => 'Отметьте согласие на обработку данных'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name'       => 'Имя',
            'last_name'        => 'Фамилия',
            'middle_name'      => 'Отчество',
            'birthday'         => 'Дата рождения',
            'citizenship_id'         => 'Гражданство',
            'citizenship_other'         => 'Другое гражданство',
            'source'         => 'Из какого источника узнали о нашей компании',
            'source_id'         => 'Источник',
            'source_fio'         => 'ФИО сотрудника',
            'source_other'         => 'Другой источник',
            'position'         => 'Желаемая должность',
            'salary'         => 'Желаемый уровень заработной платы',
            'birth_country_id'         => 'Страна рождения',
            'birth_city_id'         => 'Населенный пункт рождения',
            'birth_country_other'         => 'Укажите страну рождения',
            'birth_city_other'         => 'Укажите населенный пункт рождения',
            'registration_country_id'         => 'Страна регистрации',
            'registration_city_id'         => 'Населенный пункт регистрации',
            'registration_country_other'         => 'Укажите страну регистрации',
            'registration_city_other'         => 'Укажите населенный пункт регистрации',
            'registration_index'         => 'Индекс регистрации',
            'registration_street'         => 'Улица регистрации',
            'registration_house'         => 'Номер дома регистрации',
            'registration_flat'         => 'Квартира регистрации',
            'living_country_id'         => 'Страна проживания',
            'living_city_id'         => 'Населенный пункт проживания',
            'living_country_other'         => 'Укажите страну проживания',
            'living_city_other'         => 'Укажите населенный пункт проживания',
            'living_index'         => 'Индекс проживания',
            'living_street'         => 'Улица проживания',
            'living_house'         => 'Номер дома проживания',
            'living_flat'         => 'Квартира проживания',
            'phone'                 => 'Телефон',
            'emergency_phone'         => 'Телефон для экстренной связи',
            'emergency_fio'         => 'ФИО для экстренной связи',
            'marital_status'         => 'Семейное положение',   //0 - Холост/Не замужем, 1 - Женат/Замужем, 2 - Разведен/Разведена, 3 - Другое
            'marital_other'         => 'Другое',
            'child_num'         => 'Дети',
            'relatives_status'         => 'Степень родства',    //0 - Муж, 1- Жена, 2 - Дети, 3 - Родители
            'relatives_fio'         => 'ФИО родстенника',
            'relatives_birthday'         => 'Дата рождения родстенника',
            'educations_type'         => 'Тип образования',                 //0 - среднее, 1 - специальное, 2 - высшее
            'educations_title'         => 'Название учреждения',
            'educations_speciality'         => 'Специальность',
            'educations_date'         => 'Дата окончания',
            'jobs_date_begin'         => 'Дата устройства',
            'jobs_date_end'         => 'Дата увольнения',
            'jobs_company'         => 'Название организации',
            'jobs_status'         => 'Должность',
            'jobs_country_id'         => 'Страна места работы',
            'jobs_city_id'         => 'Населенный пункт места работы',
            'jobs_country_other'         => 'Укажите страну места работы',
            'jobs_city_other'         => 'Укажите населенный пункт места работы',
            'driver_license'         => 'Наличие прав',
            'computer_word'         => 'Word',
            'computer_excel'         => 'Excel',
            'computer_outlook'         => 'Outlook',
            'computer_1c'         => '1C',
            'computer_sap'         => 'SAP',
            'computer_other'         => 'Знание компьютера другое',
            //'documents_exists'         => 'Наличие документов',
            'documents_tk'         => 'Трудовая книжка',
            'documents_inn'         => 'ИНН',
            'documents_wb'         => 'Военный билет',
            'documents_snils'         => 'СНИЛС',
            'documents_med'         => 'Мед. книжка',
            'motivations_level'         => 'Что для вас будет являться мотивирующим фактором в работе',
            'motivation_other'          => 'Мотивация - свой вариант',
            'previous_like'         => 'Что вам нравилось в корпоративной культуре и организации труда предыдущих компаний, и что из этого хотели бы видеть у нас?',
            'demotivations_level'         => 'Что для вас будет являться демотивирующим фактором в работе',
            'demotivation_other'          => 'Демотивация - свой вариант',
            'sports_like'         => 'Увлекаетесь ли вы спортом',
            'sport_fb'         => 'Футбол',
            'sport_vb'         => 'Волейбол',
            'sport_run'         => 'Бег',
            'sport_bb'         => 'Баскетбол',
            'sport_arm'         => 'Тренажеры',
            'sport_pp'         => 'Теннис',
            'sport_other'         => 'Спорт - свой вариант',
            'who_are_you_in_company'         => 'Кем вы видите себя в компании через 3 года?',
            'familiar'         => 'Работают ли в нашей компании ваши родственники?',
            'familiar_type'         => 'Родственник или знакомый',            //0 - Родственник, 1 - Знакомый
            'familiar_status'         => 'Должность родственника',
            'familiar_fio'         => 'ФИО родственника',
            'checkboxes_id'                => '',
            'when_now'                => 'Приступить к работе',    //Когда Вы готовы приступить к работе
            'when_date'                => 'Указать дату',   //Когда Вы готовы приступить к работе
            'recommend_fio'         => 'ФИО рекомендателя',
            'recommend_status'         => 'Должность рекомендателя',
            'recommend_company'         => 'Компания рекомендателя',
            'recommend_phone'         => 'Телефон рекомендателя',
            'file'                           => '',
            'date_add'                           => 'Дата заполнения',
            'agree'                        => 'Согласие на обработку данных',
            'active'                        => 'Активность',
        ];
    }

    public function getCitizenship()
    {
        return $this->hasOne(WorkCitizenship::class, ['id' => 'citizenship_id'])
            ->alias('country');
    }

    public function getBirthcountry()
    {
        return $this->hasOne(WorkCountry::class, ['id' => 'birth_country_id'])
            ->alias('country');
    }

    public function getBirthcity()
    {
        return $this->hasOne(WorkCity::class, ['id' => 'birth_city_id'])
            ->alias('city');
    }

    public function getRegistrationcountry()
    {
        return $this->hasOne(WorkCountry::class, ['id' => 'registration_country_id'])
            ->alias('country');
    }

    public function getRegistrationcity()
    {
        return $this->hasOne(WorkCity::class, ['id' => 'registration_city_id'])
            ->alias('city');
    }

    public function getLivingcountry()
    {
        return $this->hasOne(WorkCountry::class, ['id' => 'living_country_id'])
            ->alias('country');
    }

    public function getLivingcity()
    {
        return $this->hasOne(WorkCity::class, ['id' => 'living_city_id'])
            ->alias('city');
    }

    public function getSource()
    {
        return $this->hasOne(WorkSource::class, ['id' => 'source_id'])
            ->alias('source');
    }

    public function getDemotivations()
    {
        return $this->hasMany(WorkDemotivations::class, ['id' => 'demotivation_id'])
            ->viaTable(WorksheetDemotivations::tableName(), ['worksheet_id' => 'id']);
    }

    public function getMotivations()
    {
        return $this->hasMany(WorkMotivations::class, ['id' => 'motivation_id'])
            ->viaTable(WorksheetMotivations::tableName(), ['worksheet_id' => 'id']);
    }

    /*public function getDocuments()
    {
        return $this->hasMany(WorkDocuments::class, ['id' => 'document_id'])
            ->viaTable(WorksheetDocuments::tableName(), ['worksheet_id' => 'id']);
    }*/

    public function getEducations()
    {
        return $this->hasMany(WorkEducations::class, ['id' => 'education_id'])
            ->viaTable(WorksheetEducations::tableName(), ['worksheet_id' => 'id']);
    }

    public function getJobs()
    {
        return $this->hasMany(WorkJobs::class, ['id' => 'job_id'])
            ->viaTable(WorksheetJobs::tableName(), ['worksheet_id' => 'id']);
    }

    public function getRelatives()
    {
        return $this->hasMany(WorkRelatives::class, ['id' => 'relative_id'])
            ->viaTable(WorksheetRelatives::tableName(), ['worksheet_id' => 'id']);
    }

    public function getFamiliars()
    {
        return $this->hasMany(WorkFamiliars::class, ['id' => 'familiar_id'])
            ->viaTable(WorksheetFamiliars::tableName(), ['worksheet_id' => 'id']);
    }

    /*public function getSports()
    {
        return $this->hasMany(WorkSports::class, ['id' => 'sport_id'])
            ->viaTable(WorksheetSports::tableName(), ['worksheet_id' => 'id']);
    }*/

    public function getCheckboxes()
    {
        return $this->hasMany(WorkCheckboxes::class, ['id' => 'checkbox_id'])
            ->viaTable(WorksheetCheckboxes::tableName(), ['worksheet_id' => 'id']);
    }

    public function getRecommends()
    {
        return $this->hasMany(WorkRecommends::class, ['id' => 'recommend_id'])
            ->viaTable(WorksheetRecommends::tableName(), ['worksheet_id' => 'id']);
    }

    public static function getAll()
    {
        return self::find()
            ->all();
    }

    public static function getActiveModels()
    {
        return self::find()
            ->where(['active' => '1'])
            ->orderBy(['date_add' => SORT_DESC])
            ->all();
    }

    public function uploadFile($path, $docfile)
    {
        if (isset($docfile->extension) && !is_null($docfile->extension)) {
            //$translate = Yii::$app->translate;
            //$docfile->name = $translate->translate($docfile->name);
            $file_basename = md5(Yii::$app->security->generateRandomString().time());
            $file_extension = $docfile->extension;
            $file_name = $file_basename.".".$file_extension;

            $docPath = Yii::$app->basePath . '/web' . Yii::$app->params['pathToDocs'] . $this->docDir . '/' . $path;
            if (!file_exists($docPath)) {
                mkdir($docPath);
            }

            /*if (file_exists($docPath . '/' . $docfile->name)) {
                preg_match_all("/^(.+?)\.(\w+?)$/", $docfile->name, $mathes);
                $name = $mathes[1][0];
                $ext = $mathes[2][0];
                $i = 1;
                while (file_exists($docPath . '/' . $name."-".$i.".".$ext)) {
                    $i++;
                }
                $docfile->name = $name."-".$i.".".$ext;
            }*/

            if ($docfile->saveAs($docPath . $file_name)) {
                return Yii::$app->params['pathToDocs'] . $this->docDir . '/' . $path . $file_name;
            }
        }
        return null;
    }
    /*public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }*/
}
