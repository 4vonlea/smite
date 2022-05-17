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
<p>If it is you, We mandatory ask you to please follow this link below to confirm your intention:</p>

<a href="<?=base_url('member/register/confirm_email?token='.$token);?>">Click here to verify and confirm your email</a><br/><br/>
<p>Please be concerned that this link only valid for 1x24 hours. If you have passed the time limit, please contact the committee.</p>
<p>Thank you. Best Regards</p>
<p>Registration Committee</p>