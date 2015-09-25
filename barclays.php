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

<?php

	class plgHikashopPaymentBarclays extends hikashopPaymentPlugin {
	
		var $multiple = true;
		var $name = 'barclays';
		var $payment_url;
		
		function onAfterOrderConfirm(&$order, &$methods, $method_id) {
		
			openlog('epdq', LOG_PID, LOG_USER);
		
			parent::onAfterOrderConfirm($order, $methods, $method_id);
			
			if (empty($this->payment_params->pspid)) {
			
				$this->app->enqueueMessage('You have to configure a PSPID for the Barclays payment plugin first. Check your plugin\'s parameters on the backend first.');
				
				return false;
			
			} elseif (empty($this->payment_params->password)) {
			
				$this->app->enqueueMessage('You have to configure a password for the Barclays payment plugin first. Check your plugin\'s parameters on the backend first.');
				
				return false;
			
			} else {
			
				$amount = (int)(round($order->cart->full_total->prices[0]->price_value_with_tax, 2)*100);
				
				
				$vars = array(
				
					'PSPID' => $this->payment_params->pspid,
					'ORDERID' => $order->order_id,
					'AMOUNT' => $amount,
					'CURRENCY' => $this->currency->currency_code,					
			 	);
			 	
			 	if ($this->payment_params->debug) {
			 	
			 		$this->payment_url = "https://mdepayments.epdq.co.uk/ncol/test/orderstandard.asp";
			 	
			 	} else {
			 	
			 		$this->payment_url = "https://payments.epdq.co.uk/ncol/prod/orderstandard.asp";
			 	
			 	}
			 	
			 	$vars['HOMEURL'] = HIKASHOP_LIVE;
			 	
			 	$vars['ACCEPTURL'] = HIKASHOP_LIVE . 'index.php?option=com_hikashop&ctrl=checkout&task=after_end&order_id=' . $order->order_id;
			 	$vars['DECLINEURL'] = HIKASHOP_LIVE.'index.php?option=com_hikashop&ctrl=order&task=cancel_order&order_id=' . $order->order_id;
			 	$vars['CANCELURL'] = HIKASHOP_LIVE.'index.php?option=com_hikashop&ctrl=order&task=cancel_order&order_id=' . $order->order_id;
			 	$vars['EXCEPTIONURL'] = HIKASHOP_LIVE.'index.php?option=com_hikashop&ctrl=order&task=cancel_order&order_id=' . $order->order_id;
			 	
			 	if (!empty($this->payment_params->accept_url)) {
			 	
			 		$vars['ACCEPTURL'] = $this->payment_params->accept_url;
			 	
			 	}
			 	
			 	if (!empty($this->payment_params->cancel_url)) {
			 	
			 		$vars['CANCELURL'] = $this->payment_params->cancel_url;
			 	
			 	}
			 	
			 	if (!empty($this->payment_params->exception_url)) {
			 	
			 		$vars['EXCEPTIONURL'] = $this->payment_params->exception_url;
			 	
			 	}
				
				$vars['SHASIGN'] = $this->barclays_signature($this->payment_params->password, $vars);
				$this->vars = $vars;
				
				return $this->showPage('end');
			
			}
		
		}
		
		function getPaymentDefaultValues(&$element) {
			
			$element->payment_name='Barclays ePay';
			$element->payment_description='You can pay using your credit or debit card.';
			$element->payment_images='VISA,Maestro,MasterCard';
		
		}
		
		function onPaymentConfiguration(&$element) {
		
			parent::onPaymentConfiguration($element);
		
		}
		
		function onPaymentNotification(&$statuses) {
		
			$vars = array();
			$data = array();
			$filter = JFilterInput::getInstance();
			
			openlog('epdq', LOG_PID, LOG_USER);
			
			foreach($_REQUEST as $key => $value) {
			
				$key = $filter->clean($key);
				
				if(preg_match('#^[0-9a-z_-]{1,30}$#i', $key) && !preg_match('#^cmd$#i', $key)) {
				
					$value = JRequest::getString($key);
					$vars[$key] = $value;
					$data[] = $key . '=' . urlencode($value);
				
				}
			
			}
			
			$dbOrder = $this->getOrder((int)$vars['orderID']);
			
			$this->loadPaymentParams($dbOrder);
			
			if (empty($this->payment_params)) {
			
				syslog(LOG_DEBUG, "No payment params.");
				return false;
			
			}
			
			if (empty($dbOrder)) {
			
				syslog(LOG_DEBUG, "Could not load an order for " . $vars['orderID']);
				return false;
			
			}
			
			$order_id = $dbOrder->order_id;
			
			$url = HIKASHOP_LIVE.'administrator/index.php?option=com_hikashop&ctrl=order&task=edit&order_id=' . $order_id;
			$order_text = "\r\n" . JText::sprintf('NOTIFICATION_OF_ORDER_ON_WEBSITE', $dbOrder->order_number, HIKASHOP_LIVE);
			$order_text .= "\r\n" . str_replace('<br/>', "\r\n", JText::sprintf('ACCESS_ORDER_WITH_LINK', $url));
			
			syslog(LOG_DEBUG, var_dump($_REQUEST));
			
			foreach($vars as $key => $var) {
			
				syslog(LOG_DEBUG, $key . " => " . $var);
			
			}
			
			switch ((int)$vars['STATUS']) {
			
				case 51:
				case 91:
					$order_status = $this->payment_params->pending_status;
				case 9:
				case 5:
					$order_status = $this->payment_params->verified_status;
					break;
				default:
					$order_status = $this->payment_params->invalid_status;
					break;
				
			}
			
			syslog(LOG_DEBUG, $vars['STATUS'] .  ' : ' . $order_status);
			
			$history = new stdClass();
			$history->notified = 0;
			$history->amount = $vars['amount'].$vars['currency'];
			$history->data = ob_get_clean();
			
			$config =& hikashop_config();
			
			if ($config->get('order_confirmed_status', $this->payment_params->verified_status) == $order_status) {
				
				$history->notified = 1;
			
			}
			
			syslog(LOG_DEBUG, "Completed Order");
			closelog();
			
			$this->modifyOrder($order_id, $order_status, $history, $email);
			
			JFactory::getApplication()->close();
			
			return true;
	
		
		}
		
		function barclays_signature($password, $parameters, $debug=false, $decode=false) {
		
			ksort($parameters);
			$clear_string = '';

			$encryption;
			
			switch ((int) $this->payment_params->encryption) {
			
				case 0:
				
					$encryption = "sha1";
					break;
					
				case 1:
				
					$encryption = "sha256";
					break;
					
				case 2:
				
					$encryption = "sha512";
					break;
			
			}
			
			foreach ($parameters as $key => $value) {
			
				$clear_string .= $key . '=' . $value . $password;
			
			}
			
			$hash = hash($encryption, $clear_string);

			
			return $hash;
		
		}
	
	}

?>
