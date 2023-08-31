<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popup extends Model
{
   use SoftDeletes;
   protected $fillable = ['duration', 'img', 'button1_text', 'button1_action', 'button2_text', 'button2_action', 'created_by', 'updated_by', 'deleted_by'];

   public static function getPopupData($type)
   {
      $popupManagement = Popup::where('type', $type)->first();
      $popupDatas = [
         'duration' => isset($popupManagement->duration) ? $popupManagement->duration : '',
         'img' => isset($popupManagement->img) ?\App\Helpers\SiteHelper::getObjectUrl($popupManagement->img) : '',
         'button1Text' => isset($popupManagement->button1_text) ? $popupManagement->button1_text : '',
         'button1Action' => isset($popupManagement->button1_action) ? $popupManagement->button1_action : '',
         'button2Text' => isset($popupManagement->button2_text) ? $popupManagement->button2_text : '',
         'button2Action' => isset($popupManagement->button2_action) ? $popupManagement->button2_action : '',
      ];

        return $popupDatas;
     }

}
