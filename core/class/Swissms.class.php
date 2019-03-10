<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
//require_once dirname(__FILE__) . '/../../3rdparty/php-Swissms/class.Swissms.php';

class Swissms extends eqLogic {
    
}

class SwissmsCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */
//curl -X POST https://api.swisscom.com/messaging/sms -H 'SCS-Version: 2' -H 'client_id: {YourClientID}' -H 'Content-Type: application/json' -H 'Accept: application/json'  -d '{"to": "{YourPhoneNumber}", "text": "YourMessageText."}'
	public function sendSMS($sender,$mobile_number,$msg,$apiKey){ 
			$sender = 'moi';
			$curl = curl_init("https://api.swisscom.com/messaging/sms");
			$header = array( "client_id: " .$apiKey, "SCS-Version: 2", "Accept: application/json", "Content-Type: application/json" );
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			$curl_post_data = array("from" => "Swisscom","to"=>$mobile_number,"text"=>$msg );
			
			// Encode the post data in JSON. 
			$json_post_data = json_encode($curl_post_data); 
			
			// Add the encoded data to the curl request. 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json_post_data); 
			
			// Makes curl_exec() return a string. 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
			
			// We are sending a POST request. 
			curl_setopt($curl, CURLOPT_POST, true); 
			
			// Similar to cmd-line curl's -k option during development 
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
			
			// Ignore host verification for development 
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
			
			// Must be present to get request headers 
			curl_setopt($curl, CURLINFO_HEADER_OUT, FALSE);
			
			// Make the actual call to the Swisscom server to send the SMS token 
			$curl_response = curl_exec($curl); 
			
			// Get the response back from the call. 
			$curl_info = curl_getinfo($curl); 
			
			// Check for any errors and show error on screen if there is an issue 
			$http_response_code = $curl_info['http_code']; 
			
			 if(curl_error($curl) || $http_response_code != 201) { 
					$curl_response = print_r($curl_response,true); 
					$alert_error = 'Error ' . $http_response_code . ' ' . curl_error($curl) . ' API server response: ' . $curl_response;
					log::add('Swisssms','error','erreur :' . $alert_error);
			} else { 
				log::add('Swisssms','debug','envoie: ' . htmlspecialchars($mobile_number));

			} 
			curl_close($curl); 
	}

    /*     * *********************Methode d'instance************************* */

    public function preSave() {
        if ($this->getConfiguration('receiver') == '' || $this->getConfiguration('api_key') == '' || $this->getConfiguration('sender') == '') {
            throw new Exception('Les options ne peuvent etre vides');
        }
    }

    public function execute($_options = null) {
    	
        if ($_options === null) {
            throw new Exception(__('Les options de la fonction ne peuvent etre null', __FILE__));
        }
        if ($_options['slider'] == '' ) {
            throw new Exception(__('Le message  ne peut être vide', __FILE__));
        }
        
        
		self::sendSMS($this->getConfiguration('sender'), $this->getConfiguration('receiver'), $_options['slider'], $this->getConfiguration('api_key'));
		
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>