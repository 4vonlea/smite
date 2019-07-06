<?php


class Settings_m extends MY_Model
{
    protected $primaryKey = 'name';
    protected $table = "settings";
    const EVENT_CATEGORY = 'event_category';


    /**
     * @param bool $jsonString
     * @return array|string
     */
    public static function eventCategory($jsonString = true){
        $setting = Settings_m::findOne(['name'=>self::EVENT_CATEGORY]);
        if($jsonString){
            if($setting)
                return $setting->value;
            return '[]';
        }else {
            if ($setting)
                return (json_decode($setting->value,true));
        }
        return [];
    }

    /**
     * @param $name
     * @param $value
     */
    public static function saveSetting($name,$value){
        $setting = Settings_m::findOne(['name'=>$name]);
        if($setting == null) {
            $setting = new Settings_m();
            $setting->name = $name;
        }

        $setting->value = ($value ?json_encode($value):'{}');
        $setting->save();
    }
}