<?php
/**
 * @var $token
 * @var $name
 */
?>
<p>Dear, <?=$name;?></p>
<p>
You've received this email because it was used for registering at <b>"<?=Settings_m::getSetting('site_title');?>"</b>
</p>
<p>
Please follow this link to confirm your intention:
</p>

<a href="<?=base_url('member/register/confirm_email?token='.$token);?>">Confirm Request</a><br/><br/>