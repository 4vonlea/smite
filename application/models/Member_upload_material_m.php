<?php

class Member_upload_material_m extends MY_Model
{
    protected $table = "member_upload_material";
    protected $primaryKey = "id";
    const TYPE_LINK = 1;
    const TYPE_FILE = 2;
}