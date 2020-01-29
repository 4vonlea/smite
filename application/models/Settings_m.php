<?php


class Settings_m extends MY_Model
{
    protected $primaryKey = 'name';
    protected $table = "settings";

    const EVENT_CATEGORY = 'event_category';
    const STATUS_COMMITTEE = 'status_committee';
    const MANUAL_PAYMENT = 'manual_payment';


	/**
	 * @param bool $jsonString
	 * @return array|string
	 */
	public static function manualPayment($jsonString = true){
		$setting = Settings_m::findOne(['name'=>self::MANUAL_PAYMENT]);
		if($jsonString){
			if($setting && $setting->value != "")
				return $setting->value;
			return '[]';
		}else {
			if ($setting)
				return (json_decode($setting->value,true));
		}
		return [];
	}

    /**
     * @param bool $jsonString
     * @return array|string
     */
    public static function eventCategory($jsonString = true){
        $setting = Settings_m::findOne(['name'=>self::EVENT_CATEGORY]);
        if($jsonString){
            if($setting && $setting->value != "")
                return $setting->value;
            return '[]';
        }else {
            if ($setting)
                return (json_decode($setting->value,true));
        }
        return [];
    }

	/**
	 * @param bool $jsonString
	 * @return array|string
	 */
	public static function statusCommitte($jsonString = true){
		$setting = Settings_m::findOne(['name'=>self::STATUS_COMMITTEE]);
		if($jsonString){
			if($setting && $setting->value != "")
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
