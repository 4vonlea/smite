<?php
/**
 * @var $token
 * @var $name
 */
?>
<p>Hi, <?=$name;?></p>
<p>
You've received this email because your email address was used for registering at <b>"<?=Settings_m::getSetting('site_title');?>"</b>
</p>
<p>
Please follow this link to confirm your intention:
</p>

<a href="<?=base_url('member/register/confirm_email?token='.$token);?>">Confirm Request</a><br/><br/>

Yours truly,<br/>
<?=Settings_m::getSetting('site_title');?> Admin<br/>
<?=base_url();?>
