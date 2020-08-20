<?php


class Settings_m extends MY_Model
{
    protected $primaryKey = 'name';
    protected $table = "settings";

    const EVENT_CATEGORY = 'event_category';
    const STATUS_COMMITTEE = 'status_committee';
    const MANUAL_PAYMENT = 'manual_payment';
    const ENABLE_PAYMENT = 'enable_payment';
    const ESPAY = 'espay';


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
    
    public static function getEspay(){
        $val = self::getSetting(self::ESPAY);
        if($val && $val != ""){
            $return = json_decode($val,true);
            if(count($return) > 0)
                return $return;
        }
        return [
            'apiKey'=>"",
            'merchantCode'=>"",
            'signature'=>"",
            "apiLink"=>"",
            "jsKitUrl"=>"",
        ];
    }

    public static function getEnablePayment(){
        $val = self::getSetting(self::ENABLE_PAYMENT);
        if($val && $val != ""){
            $return = json_decode($val,true);
            if(count($return) > 0)
                return $return;
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
            return true;
        }else {
            $setting = Settings_m::findOne(['name' => $name]);
            if ($setting == null) {
                $setting = new Settings_m();
                $setting->name = $name;
            }
            $setting->value = (is_array($value) ? json_encode($value) : $value);
            return $setting->save();
        }
        return true;
    }
}
