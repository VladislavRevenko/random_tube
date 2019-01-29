<?php

namespace common\models;

use backend\models\Form;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'match', 'pattern' => '/^[a-z-_]+$/', 'message' => 'Символьный код может содержать только a-z'],
            [['template'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'code' => 'Код',
            'template' => 'Шаблоны',
        ];
    }

    public function getForm()
    {
        return $this->hasOne(Form::className(), ['id' => 'template']);
    }
}

