<?php defined('_JEXEC') or die('Restricted Access'); ?>

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


<tr>
	<td class="key">
		<label for="data[payment][payment_params][pspid]"><?php
			echo "PSPID";
		?></label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][pspid]" value="<?php echo $this->escape(@$this->element->payment_params->pspid); ?>" />
	</td>
</tr>

<tr>
	<td class="key">
		<label for="data[payment][payment_params][password]"><?php
			echo "Password";
		?></label>
	</td>
	<td>
		<input type="password" name="data[payment][payment_params][password]" value="<?php echo $this->escape(@$this->element->payment_params->password); ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][accept_url]"><?php
			echo "Accept URL";
		?></label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][accept_url]" value="<?php echo $this->escape(@$this->element->payment_params->accept_url); ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][cancel_url]"><?php
			echo "Cancel URL";
		?></label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][cancel_url]" value="<?php echo $this->escape(@$this->element->payment_params->cancel_url); ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][exception_url]"><?php
			echo "Exception URL";
		?></label>
	</td>
	<td>
		<input type="text" name="data[payment][payment_params][exception_url]" value="<?php echo $this->escape(@$this->element->payment_params->exception_url); ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][debug]"><?php
			echo JText::_('DEBUG');
		?></label>
	</td>
	<td><?php
		echo JHTML::_('hikaselect.booleanlist', "data[payment][payment_params][debug]" , '', @$this->element->payment_params->debug);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][encryption]"><?php
			echo "Encryption";
		?></label>
	</td>
	<td><?php
		$arr = array(
			JHTML::_('select.option', '0', 'SHA-1' ),
			JHTML::_('select.option', '1', 'SHA-256' ),
			JHTML::_('select.option', '2', 'SHA-512' ),
		);
		echo JHTML::_('hikaselect.radiolist',  $arr, "data[payment][payment_params][encryption]", '', 'value', 'text', @$this->element->payment_params->encryption);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][invalid_status]"><?php
			echo JText::_('INVALID_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][invalid_status]", @$this->element->payment_params->invalid_status);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][pending_status]"><?php
			echo JText::_('PENDING_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][pending_status]", @$this->element->payment_params->pending_status);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][requested_status]"><?php
			echo "Payment Requested Status";
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][requested_status]", @$this->element->payment_params->requested_status);
	?></td>
</tr>
<tr>
	<td class="key">
		<label for="data[payment][payment_params][verified_status]"><?php
			echo JText::_('VERIFIED_STATUS');
		?></label>
	</td>
	<td><?php
		echo $this->data['order_statuses']->display("data[payment][payment_params][verified_status]", @$this->element->payment_params->verified_status);
	?></td>
</tr>
