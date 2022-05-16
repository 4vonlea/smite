<?php
/**
 * @var $token
 * @var $name
 */
?>
<p>Dear <?=$name;?></p>
<p>
Thank you for your participation in this event. You've received this email because it was used for registering at <b>"<?=Settings_m::getSetting('site_title');?>"</b>
</p>
<p>
If it is you, We mandatory ask you to please follow this link to confirm your intention:
</p>

<a href="<?=base_url('member/register/confirm_email?token='.$token);?>">Click here to verify and confirm your email</a><br/><br/>