<?php

namespace common\models;


/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Categories extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'match', 'pattern' => '/^[a-z-_]+$/', 'message' => 'Символьный код может содержать только a-z'],
            [['template_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'code' => 'Код',
            'template_id' => 'Шаблон',
        ];
    }

    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }
}

