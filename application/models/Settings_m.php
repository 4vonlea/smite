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

    public static function getSetting($name){
        $setting = Settings_m::findOne(['name'=>$name]);
        if($setting)
            return $setting->value;
        return "";
    }

    /**
     * @param array|string $name
     * @param string|array $value
     */
    public static function saveSetting($name,$value = ''){
        if(is_array($name)){
            foreach($name as $n=>$v){
                self::saveSetting($n,$v);
            }
        }else {
            $setting = Settings_m::findOne(['name' => $name]);
            if ($setting == null) {
                $setting = new Settings_m();
                $setting->name = $name;
            }
            $setting->value = (is_array($value) ? json_encode($value) : $value);
            $setting->save();
        }
    }
}