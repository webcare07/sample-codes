<?php

// no direct access
defined('_JEXEC') or die;
$app	= JFactory::getApplication();
$user	= JFactory::getUser();
if( $user->id )
{
	$csuser = SCremationHelper::getSCUser($user->id);
	if($csuser->type == 'd')
		$link = JRoute::_('index.php?option=com_scremation&view=daccount&layout=dashboard', false);
	else
		$link = JRoute::_('index.php?option=com_scremation&view=caccount', false);
	$registerLinks = '<a href="'.$link.'">My Account</a>';
}
else
{
	$registerLinks = '<a href="'.JRoute::_('index.php?option=com_users&view=registration').'">Register</a> | <a href="'.JRoute::_('index.php?option=com_users&view=login').'">Login</a>';
}

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');

$id = (int)$this->item->id;

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_scremation/assets/css/scremation.css');
$document->addScript('components/com_scremation/assets/js/scremation.js');
jimport( 'joomla.form.rules.equals' );

$sharelink = substr(JURI::root(), 0, -1).JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$this->item->id);
$sharetitle = 'In Memory of '.$this->item->fullname.' :SimpleCremationOnline';
if(isset($this->item->image_m) && !empty($this->item->image_m))
	$shareimage = JURI::root().$this->item->image_m;
else $shareimage = '';
?>
<style>
.tm-container .tm-headerbar, .tm-container .tm-top-block { display:none !important; }
</style>
<div id="sctribute">
	<div id="sct-left" class="theme-<?php echo (int)$this->item->theme; ?>">
    	<h4 class="memory"><i>In Memory Of</i></h4>
        <h1 class="title"><?php echo $this->item->fullname; ?></h1>
        <h4 class="caption"><?php echo date("Y", strtotime($this->item->dob)); ?> - <?php echo date("Y", strtotime($this->item->dod)); ?></h4>
    </div>
    <div id="sct-right">
    	<ul class="top-menu">
        	<li><a href="<?php echo JURI::root();?>">Home</a></li>
            <li class="black-pad"><?php echo $registerLinks; ?></li>
        </ul>
        <div class="bradly-logo" style="clear:both;">
         <?php if(isset($this->item->ddata->clogo) && !empty($this->item->ddata->clogo)) { ?>
        	<img src="<?php echo JURI::root().$this->item->ddata->clogo; ?>" style="padding-top:20px" />
         <?php } else { ?>
        	<img src="<?php echo JURI::root();?>components/com_scremation/assets/images/logo.png" />
         <?php } ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div id="scremation" style="width:906px; margin-top: -7px; border-top: 3px solid #D7D9DA;">
	<div id="tribute-left">
    	<ul id="tribute-menu">
        	<li id="home" class="active theme-c-<?php echo (int)$this->item->theme; ?>">Home</li>
        	<li id="obituary" class="theme-c-<?php echo (int)$this->item->theme; ?>">Read Obituary</li>
        	<li id="arrangements" class="theme-c-<?php echo (int)$this->item->theme; ?>">Arrangements and<br />Guest Information</li>
        	<li id="guest-book" class="theme-c-<?php echo (int)$this->item->theme; ?>">Sign the Guest Book</li>
        	<li id="presonal-note" class="theme-c-<?php echo (int)$this->item->theme; ?>">Send a Personal Note</li>
        </ul>
        <div id="social-share">
            <span id='st_facebook_large'></span>
            <span id='st_twitter_large'></span>
            <span id='st_sharethis_large'></span>
        </div>
    </div>
	<div id="tribute-right" class="theme-bg-<?php echo (int)$this->item->theme; ?>">
    	<div id="home-body" class="body" style="display:block;">
        	<table width="100%" cellpadding="0" cellspacing="0">
				<tr><td width="25%" align="center">
	                <?php if(isset($this->item->image_m) && !empty($this->item->image_m)) { ?>
    	            	<img src="<?php echo JURI::root().$this->item->image_m; ?>" />
        			<?php } else { echo '&nbsp;'; } ?>            
                	</td>
                    <td align="right" style="padding-right:30px;">
                    	<h1 style="margin:0px;"><?php echo $this->item->fullname; ?></h1>
                        <h4 style="margin:0px;"><?php echo date("F j, Y", strtotime($this->item->dob)); ?> - <?php echo date("F j, Y", strtotime($this->item->dod)); ?></h4>
                    </td>
                 </tr>
                 <tr><td colspan="2"><br /><br /></td></tr>
                 <tr><td colspan="2">
                 	<p>In loving memory of <?php echo $this->item->fullname; ?>, born in <?php echo $this->item->dcity; ?>,  <?php echo ScremationHelper::getStateName($this->item->dstate); ?> and died on <?php echo date("M j, Y", strtotime($this->item->dod)); ?><br /><a href="javascript:;" onclick="javascript:jQuery('#obituary').click();">Read Obituary...</a></p>
                    </td></tr>
                 <tr><td colspan="2" style="padding-left:50px;">
                    <img src="<?php echo JURI::base(); ?>components/com_scremation/assets/images/box-4.png" style="position:absolute; margin-left:-50px; margin-top:5px;"/>
                 	<h3 style="margin:0px;"><i>See Arrangements & Guest Information</i></h3>
                    <p>For visitation, funeral and other arrangements and for suggestions on flowers, charitable contributions, travel arrangements...</p>
                    <p><a href="javascript:;" onclick="javascript:jQuery('#arrangements').click();">Read more...</a></p>
                    </td></tr>

                 <tr><td colspan="2" style="padding-left:50px;">
                    <img src="<?php echo JURI::base(); ?>components/com_scremation/assets/images/box-4.png" style="position:absolute; margin-left:-50px; margin-top:5px;"/>
                 	<h3 style="margin:0px;"><i>Sign the Guest Book</i></h3>
                    <p>We cherish your messages. Take a moment to write a note in our guestbook or read...</p>
                    <p><a href="javascript:;" onclick="javascript:jQuery('#guest-book').click();">Read more...</a></p>
                    </td></tr>

                 <tr><td colspan="2" style="padding-left:50px;">
                    <img src="<?php echo JURI::base(); ?>components/com_scremation/assets/images/box-4.png" style="position:absolute; margin-left:-50px; margin-top:5px;"/>
                 	<h3 style="margin:0px;"><i>Send a Personal Note</i></h3>
                    <p>Send a personal note to the family...</p>
                    <p><a href="javascript:;" onclick="javascript:jQuery('#presonal-note').click();">Read more...</a></p>
                    </td></tr>
            </table>
        </div>
    	<div id="obituary-body" class="body">
        	<h2>Obituary</h2>
            <p><?php echo nl2br($this->item->obituarytext); ?></p>
        </div>
    	<div id="arrangements-body" class="body">
        	<h2>Arrangements & Guest Information</h2>
            <?php if($this->item->is_visitation == 1) { ?>
            	<h3 style="margin:0px;">Visitation</h3>
                <div style="position: absolute; margin-top: -30px; margin-left: 300px;">Phone: <?php echo $this->item->venue_phone; ?></div>
                <p>
                	<b>Venue: <?php echo $this->item->venue_name; ?></b><br />
					<?php if($this->item->venue_1vstd == $this->item->venue_1vetd) { ?>
                    <b><?php echo date('M j, Y', strtotime($this->item->venue_1vstd)); ?> from <?php echo $this->item->venue_1vstt; ?> to <?php echo $this->item->venue_1vett; ?></b>
                    <?php  } else { ?>
                    <b>From <?php echo date('M j, Y', strtotime($this->item->venue_1vstd)); ?> <?php echo $this->item->venue_1vstt; ?> to <?php  echo date('M j, Y', strtotime($this->item->venue_1vetd)); echo ' '.$this->item->venue_1vett; ?></b>
                    <?php } ?>
                    <br />
                	<?php if($this->item->venue_2vstd == $this->item->venue_2vetd) { ?>
                    <b><?php echo date('M j, Y', strtotime($this->item->venue_2vstd)); ?> from <?php echo $this->item->venue_2vstt; ?> to <?php echo $this->item->venue_2vett; ?></b>
                    <?php  } else { ?>
                    <b>From <?php echo date('M j, Y', strtotime($this->item->venue_2vstd)); ?> <?php echo $this->item->venue_2vstt; ?> to <?php  echo date('M j, Y', strtotime($this->item->venue_2vetd)); echo ' '.$this->item->venue_2vett; ?></b>
                    <?php } ?>
					<?php if(!empty($this->item->venue_address1)) echo '<br />'.$this->item->venue_address1; ?>                    
					<?php if(!empty($this->item->venue_address2)) echo '<br />'.$this->item->venue_address2; ?>                    
					<?php if(!empty($this->item->venue_city)) echo '<br />'.$this->item->venue_city; ?>                    
					<?php if(!empty($this->item->venue_country)) echo '<br />'.ScremationHelper::getCountryName($this->item->venue_country); ?>                    
					<?php if(!empty($this->item->venue_state)) echo '<br />'.ScremationHelper::getStateName($this->item->venue_state); ?>                    
					<?php if(!empty($this->item->venue_zip)) echo '<br />'.$this->item->venue_zip; ?>                    
                </p>
                <hr style="border-top:2px solid #8F8F91;" />
            <?php } ?>

            <?php if($this->item->is_memorialservice == 1) { ?>
            	<h3 style="margin:0px;">Memorial Service</h3>
                <p>
                	<b>Venue: <?php echo $this->item->mvname; ?></b><br />
                    <b><?php echo $this->item->mtime; echo ' '; echo date('M j, Y', strtotime($this->item->mdate)); ?></b>
					<?php if(!empty($this->item->maddress1)) echo '<br />'.$this->item->maddress1; ?>                    
					<?php if(!empty($this->item->maddress2)) echo '<br />'.$this->item->maddress2; ?>                    
					<?php if(!empty($this->item->mcity)) echo '<br />'.$this->item->mcity; ?>                    
					<?php if(!empty($this->item->mcountry)) echo '<br />'.ScremationHelper::getCountryName($this->item->mcountry); ?>                    
					<?php if(!empty($this->item->mstate)) echo '<br />'.ScremationHelper::getStateName($this->item->mstate); ?>                    
					<?php if(!empty($this->item->mzip)) echo '<br />'.$this->item->mzip; ?>                    
                </p>
                <hr style="border-top:2px solid #8F8F91;" />
            <?php } ?>

            <?php if($this->item->is_commital == 1) { ?>
            	<h3 style="margin:0px;">Commital</h3>
                <p>
                	<b>Venue: <?php echo $this->item->mvname; ?></b><br />
                    <b><?php echo date('M j, Y', strtotime($this->item->cdate)); ?></b>
					<?php if(!empty($this->item->caddress1)) echo '<br />'.$this->item->caddress1; ?>                    
					<?php if(!empty($this->item->caddress2)) echo '<br />'.$this->item->caddress2; ?>                    
					<?php if(!empty($this->item->ccity)) echo '<br />'.$this->item->ccity; ?>                    
					<?php if(!empty($this->item->ccountry)) echo '<br />'.ScremationHelper::getCountryName($this->item->ccountry); ?>                    
					<?php if(!empty($this->item->cstate)) echo '<br />'.ScremationHelper::getStateName($this->item->cstate); ?>                    
					<?php if(!empty($this->item->czip)) echo '<br />'.$this->item->czip; ?>                    
                </p>
                <hr style="border-top:2px solid #8F8F91;" />
            <?php } ?>

            <?php if($this->item->is_charities == 1) { ?>
            	<h3 style="margin:0px;">Charities</h3>
                <p>
                    <?php echo nl2br($this->item->charitiestext); ?>
                </p>
                <hr style="border-top:2px solid #8F8F91;" />
            <?php } ?>
        </div>
    	<div id="guest-book-body" class="body">
        	<h2>Sign the Guest Book</h2>
            <p>We cherish your message. Take a moment to write a note in our Guest Book or read entries from other visitors.</p>
            <p style="text-align:center;"><input type="button" id="btn-guestbook" value=" Write in the Guest Book " class="btn btn-primary" onclick="jQuery('#guestbook').show();jQuery(this).hide();" /></p>
            <div id="guestbook" class="theme-bg-<?php echo (int)$this->item->theme; ?>" style="display:none; border: 2px solid #8F8F91;">
                <form action="<?php echo JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="tributegb-form" class="form-validate">
                <div class="control-group">
                    <h3 style="padding-left: 10px; margin-top: 10px;">Post Message to Guest Book</h3>
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('name'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('name'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('email'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('email'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('city'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('city'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('country'); ?>
                    </div>
                    <div class="controls">
                    	<select id="jform_country" name="jform[country]" onchange="javascript:loadStates(this.value, 0, 'state');">
						<?php 
						echo JHtml::_('select.options', SCremationHelper::getCountryOptions(), "value", "text", DEFAULT_COUNTRY_ID, true);?>
						</select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('state'); ?>
                    </div>
                    <div class="controls">
                    	<select id="jform_state" name="jform[state]">
                        	<option value="0"> - Select - </option>
						</select>
                        <img src="<?php echo JURI::base(); ?>administrator/components/com_scremation/assets/images/loader.gif" style="display:none;margin-top: -20px;" id="jform_state_loading" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('message'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('message'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;">
                        <?php echo $this->form->getLabel('code'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('code'); ?>
                        <p style="margin:0px 0px 10px 0px;">Please enter the code below.</p>
                        <p style="margin:0px 0px 10px 0px;"><img src="<?php echo JRoute::_('index.php?option=com_scremation&task=tribute.getsecuritycode&name=guestbookcode&t='.rand(11111, 99999)); ?>" width="100" height="40" id="guestbookcode" />&nbsp;&nbsp;<img src="<?php echo JURI::root(); ?>components/com_scremation/assets/images/reload.png" style="cursor:pointer;" onclick="jQuery('#guestbookcode').attr('src', '<?php echo JRoute::_('index.php?option=com_scremation&task=tribute.getsecuritycode&name=guestbookcode'); ?>&t='+Math.random());" class="hasTooltip" title="Reload Security Code"/></p>
                        <p style="margin:0px 0px 10px 0px;">This is a standard security check to protect your information.</p>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input type="submit" class="btn btn-primary" value=" Submit "/>
                        <input type="button" class="btn btn-danger" value=" Cancel " onclick="jQuery('#guestbook').hide();jQuery('#btn-guestbook').show();"/>
                    </div>
                </div><br />
                <input type="hidden" name="jform[tribute_id]" value="<?php echo (int)$this->item->id; ?>" />
                <input type="hidden" name="task" value="tribute.postguestbook" />
				<?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
            <div id="guestbookpost">
            	<?php $posts = ScremationHelperSite::getTributeGuestPosts((int)$this->item->id); ?>
                <?php if(count($posts)) { ?>
                <table width="100%" cellpadding="0" cellspacing="0">	
                <?php foreach($posts as $post) { ?>
                	<tr><td colspan="2"><hr style="border-top:2px solid #A5A5A8;" /></td></tr>
                	<tr>
                    	<td width="60%" valign="top"><h4 style="margin:0px;"><?php echo $post->name; ?></h4></td>
                        <td><b><?php echo date("F j, Y", strtotime($post->created)); ?></b><br />from <?php echo $post->city; ?>, <?php echo ScremationHelper::getStateName($post->state); ?></td>
                    </tr>
                	<tr>
                    	<td width="60%"><?php echo nl2br($post->message); ?></td>
                        <td>&nbsp;</td>
                    </tr>
                <?php } ?>
                </table>
                <?php } ?>
            </div>
        </div>
    	<div id="presonal-note-body" class="body">
        	<h2>Send a Personal Condolence</h2>
            <p>This message will be sent privately to the family.</p>
            <div id="sendpersonalnote"  class="theme-bg-<?php echo (int)$this->item->theme; ?>"  style="border: 2px solid #8F8F91;">
                <form action="<?php echo JRoute::_('index.php?option=com_scremation&view=tribute&id='.(int)$this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm1" id="tributesp-form" class="form-validate">
                <div class="control-group">
                    <div class="control-label" style="width:20%;padding-left:30px;">
                        <?php echo $this->form->getLabel('name'); ?>
                    </div>
                    <div class="controls" style="width: 75%;">
                        <?php echo $this->form->getInput('name'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;padding-left:30px;">
                        <?php echo $this->form->getLabel('email'); ?>
                    </div>
                    <div class="controls" style="width: 75%;">
                        <?php echo $this->form->getInput('email'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;padding-left:30px;">
                        <?php echo $this->form->getLabel('message'); ?>
                    </div>
                    <div class="controls" style="width: 75%;">
                        <?php echo $this->form->getInput('message'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label" style="width:20%;padding-left:30px;">
                        <?php echo $this->form->getLabel('code'); ?>
                    </div>
                    <div class="controls" style="width: 75%;">
                        <?php echo $this->form->getInput('code'); ?>
                        <p style="margin:0px 0px 10px 0px;">Please enter the code below.</p>
                        <p style="margin:0px 0px 10px 0px;"><img src="<?php echo JRoute::_('index.php?option=com_scremation&task=tribute.getsecuritycode&name=sendpersonalcode&t='.rand(11111, 99999)); ?>" width="100" height="40" id="sendpersonalcode" />&nbsp;&nbsp;<img src="<?php echo JURI::root(); ?>components/com_scremation/assets/images/reload.png" style="cursor:pointer;" onclick="jQuery('#sendpersonalcode').attr('src', '<?php echo JRoute::_('index.php?option=com_scremation&task=tribute.getsecuritycode&name=sendpersonalcode'); ?>&t='+Math.random());" class="hasTooltip" title="Reload Security Code"/></p>
			<p style="margin:0px 0px 10px 0px;">This is a standard security check to protect your information.</p>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input type="submit" class="btn btn-primary" value=" Submit "/>
                    </div>
                </div><br />
                <input type="hidden" name="jform[tribute_id]" value="<?php echo (int)$this->item->id; ?>" />
                <input type="hidden" name="task" value="tribute.sendpersonalnote" />
				<?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
        </div>
    </div>
    <div class="clear"></div>

</div>
<style>
#jform_message { width: 90%; height: 100px; margin-bottom:10px; }
.chzn-container { margin-bottom: 10px; }
</style>
<script>
jQuery(document).ready(function(){
	jQuery('#tribute-menu li').click(function(){
		jQuery('#tribute-menu li').removeClass('active');
		var tid = jQuery(this).attr('id');
		jQuery(this).addClass('active');
		jQuery('#tribute-right .body').hide();
		jQuery('#tribute-right #'+tid+'-body').show();
	});
	jQuery('#jform_country').change();
});
function loadStates(cid, sid, objid)
{
    jQuery("#jform_"+objid+"_loading").show();
    jQuery.ajax({
        type: "GET",
        url: "<?php echo JURI::base(); ?>index.php?option=com_scremation&view=dregister&task=dregister.getSates&cid="+cid+"&sid="+sid,
        dataType: "html",
        success: function(html){
            jQuery("#jform_"+objid).html(html);
            jQuery("#jform_"+objid+"_loading").hide();
            jQuery("#jform_"+objid).trigger("liszt:updated");//v0.9.8
            jQuery("#jform_"+objid).trigger("chosen:updated");//v1.0.0
            jQuery("#jform_"+objid).trigger("chosen:updated.chosen");//v1.0.0
        }
    });
}
</script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-7af559d8-5774-f4bd-c45-603b78c9925c", doNotHash: true, doNotCopy: false, hashAddressBar: false});
stWidget.addEntry({
                 "service":"facebook",
                 "element":document.getElementById('st_facebook_large'),
                 "url":"<?php echo $sharelink; ?>",
                 "title":"<?php echo $sharetitle; ?>",
                 "type":"large",
                 "text":"" ,
                 "image":"<?php echo $shareimage; ?>",
                 "summary":""
});
stWidget.addEntry({
                 "service":"twitter",
                 "element":document.getElementById('st_twitter_large'),
                 "url":"<?php echo $sharelink; ?>",
                 "title":"<?php echo $sharetitle; ?>",
                 "type":"large",
                 "text":"" ,
                 "image":"<?php echo $shareimage; ?>",
                 "summary":""
});
stWidget.addEntry({
                 "service":"sharethis",
                 "element":document.getElementById('st_sharethis_large'),
                 "url":"<?php echo $sharelink; ?>",
                 "title":"<?php echo $sharetitle; ?>",
                 "type":"large",
                 "text":"" ,
                 "image":"<?php echo $shareimage; ?>",
                 "summary":""
});
</script>
