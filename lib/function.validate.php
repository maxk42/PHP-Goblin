<?php


function validate($type, $val) {
	global $states;										// Used to verify user's address' state.
	switch($type) {
		case 'first_name':
		case 'last_name':
		case 'name':
		case 'city':
			return (bool) preg_match('/^[a-zA-Z][a-zA-Z \'\-]*$/', trim($val));
		
		case 'address_1':
			if(strlen($val) < 6)							// '1 X St'
				return FALSE;
			if(!preg_match('/^[0-9]/', trim($val)))					// Only need to check the first digit, because a + would be an equivalent test in this case, since a single digit is acceptable.
				return FALSE;
				// Now drop-through to the next case...
		case 'address_2':
			// Few restrictions here.  Make sure it's nothing but printable characters.
			$len = strlen($val);
			for($i = 0; $i < $len; $i++)
				if(ord($val[$i]) < 32 || ord($val[$i]) > 126)
					return FALSE;
			return TRUE;
		
		case 'state':
			return in_array(strtoupper($val), array_keys($states));
		
		case 'postal_code':
		case 'zip':
			return preg_match('/^[0-9]{5}$/', $val);					// Check for five digits in a row.  Nothing more.
		
		default:
			return FALSE;
	}
	
	return FALSE;
}


?>