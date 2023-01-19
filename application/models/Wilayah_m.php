<?php
class Wilayah_m extends MY_Model
{
    protected $table = "wilayah";
    protected $primaryKey = "kode";


    const LENGTH_KODE_PROPINSI = 2;
    const LENGTH_KODE_KABUPATEN = 5;
    const LENGTH_KODE_KECAMATAN = 8;
    const LENGTH_KODE_DESA = 13;

    public function getKabupatenKota($propinsi_kode = null){
        $builder = $this->find()->select('kode as key,nama as label')->where("LENGTH(kode)",self::LENGTH_KODE_KABUPATEN);
        if($propinsi_kode){
            $builder->like("kode",$propinsi_kode.".","after");
        }
        return $builder->get();
    }
}