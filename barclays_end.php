<?php

/**
 * @Author James L. Lendrem
 * @Company Smooth Lines
 * @Email J.L.Lendrem@smoothlines.net
 * @Website Smoothlines.net
 *
 *
 * @Copyright Copyright (C) 2014 Smooth Lines. All rights reserved.
 * @Licence GNU General Public License version 2 or later; see LICENSE.txt
 *

**/

?>

<?php defined('_JEXEC') or die('Restricted Access'); ?>

<?php defined('_JEXEC') or die('Restricted access');
?><div class="barclays_end">
	<span class="barclays_end_message">
		<?php echo JText::sprintf('PLEASE_WAIT_BEFORE_REDIRECTION_TO_X',$this->payment_name).'<br/>'. JText::_('CLICK_ON_BUTTON_IF_NOT_REDIRECTED');?>
	</span>
	<span class="barclays_end_spinner">
		<img src="<?php echo HIKASHOP_IMAGES.'spinner.gif';?>" />
	</span>
	<br/>
	<form id="barclays_form" name="barclays_form" action="<?php echo $this->payment_url; ?>" method="post">
		<div id="barclays_end_image" class="barclays_end_image">
			<input id="barclays_button" type="submit" class="btn btn-primary" value="<?php echo JText::_('PAY_NOW');?>" name="" alt="<?php echo JText::_('PAY_NOW');?>" />
		</div>
		<?php
			foreach( $this->vars as $name => $value ) {
				echo '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars((string)$value).'" />';
			}
			$doc =& JFactory::getDocument();
			$doc->addScriptDeclaration("window.addEvent('domready', function() {document.getElementById('barclays_form').submit();});");
			JRequest::setVar('noform',1);
		?>
	</form>
</div>
