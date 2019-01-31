<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;

class Template extends \yii\db\ActiveRecord
{

    public $layout_twig;
    public $index_twig;
    public $error_twig;
    public $add_twig;
    public $categories_twig;
    public $style_css;
    public $twig_files = array (
        'layout_twig' => 'layout.twig',
        'index_twig' => 'index.twig',
        'error_twig' => 'error.twig',
        'add_twig' => 'add.twig',
        'categories_twig' => 'categories.twig',
        'style_css' => 'style.css',
    );

    public static function tableName()
   {
        return 'template';
   }

   public function rules()
   {
      return [
         [['code', 'name'], 'string'],
         [['twig_content', 'layout_twig', 'index_twig', 'error_twig', 'add_twig', 'categories_twig', 'style_css'], 'safe'],
      ];
   }

   public function attributeLabels()
   {
      return [
         'code' => 'Символьный код',
         'name' => 'Название',
         'layout_twig' => 'layout_twig',
         'index_twig' => 'index_twig',
         'error_twig' => 'error_twig',
         'add_twig' => 'add_twig',
         'categories_twig' => 'categories_twig',
         'style_css' => 'style_css',
      ];
    }

   public function beforeSave($insert)
   {
   $attr = $this->attributes;
   $code = $attr['code'];
   $dir_path = Yii::getAlias('@app/../frontend/views/page-templates/' . $code . '/');
   if (Template::find()->where(['code' => $code])->exists()) {
       echo ('Такое имя уже существует!');
       return false;
   }
       if (file_exists($dir_path)) {
           echo ('Такая папка уже существует!');
           return false;
       } else {
           $db_code = Template::find()->where(['code' => $this->code]);
           if ($code != $db_code) {
//               $code = $db_code;
//                rename ($dir_path, );
           }
//           $db_code = (Template::find()->where(['code' => $code]));
//           if ($db_code == $code) {
//
//       }
               FileHelper::createDirectory($dir_path, $mode = 0755, true);
               foreach ($this->twig_files as $file_code => $file_name) {
                   if (!empty($this->{$file_code})) {
                      file_put_contents($dir_path . $file_name, $this->{$file_code});
                   }
               }
           }
//       }


      return parent::beforeSave($insert); // TODO: Change the autogenerated stub
   }
}