<?php

/**
 * Create the entire query string for your API call without the checksum parameter.
 * -Example for create meeting API call: "name=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444"
 * Prepend the call name to your string
 * -Example for above query string:
 *  call name is "create"
 *  String becomes: "createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444"
 * Now, append the security salt to your string
 * -Example for above query stringnd_:
 *  security salt is "639259d4-9dd8-4b25-bf01-95f9567eaf4b"
 *  String becomes: "createname=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444639259d4-9dd8-4b25-bf01-95f9567eaf4b"
 * Now, find the SHA-1 sum for that string (implementation varies based on programming language).
 * -the SHA-1 sum for the above string is: "1fcbb0c4fc1f039f73aa6d697d2db9ba7f803f17"
 * Add a checksum parameter to your query string that contains this checksum.
 * -Above example becomes: "name=Test+Meeting&meetingID=abc123&attendeePW=111222&moderatorPW=333444&checksum=1fcbb0c4fc1f039f73aa6d697d2db9ba7f803f17"
 * Full API documentation can be seen on http://code.google.com/p/bigbluebutton/wiki/API#API_Security_Model
 */

Class Komunikasi_Model_BBBAPI{

    private $salt;
    private $serverUrl;
    private $callAndParams = array();

    function __construct($salt, $serverUrl)
    {
        $this->salt = $salt;
        $this->serverUrl = $serverUrl;
        $this->callAndParams =
            array(
                'create'        => array(
                    'call'     => 'create',
                    'required' => array('name', 'meetingID'),
                    'optional' => array('attendeePW', 'moderatorPW', 'welcome', 'dialNumber',
                        'voiceBridge', 'webVoice', 'logoutURL', 'maxParticipants',
                        'record', 'duration', 'meta_presenter', 'meta_label'),
                ),
                'join'              => array(
                    'call'      => 'join',
                    'required'  => array('fullName', 'meetingID', 'password'),
                    'optional'  => array('createTime', 'userID', 'webVoiceConf'),
                ),
                'check_meeting'     => array(
                    'call'      => 'isMeetingRunning',
                    'required'  => array('meetingID'),
                    'optional'  => array(),
                ),
                'end_meeting'       => array(
                    'call'      => 'end',
                    'required'  => array('meetingID', 'password'),
                    'optional'  => array(),
                ),
                'info_meeting'      => array(
                    'call'      => 'getMeetingInfo',
                    'required'  => array('meetingID', 'password'),
                    'optional'  => array(),
                ),
                'list_meeting'      => array(
                    'call'      => 'getMeetings',
                    'required'  => array(),
                    'optional'  => array(),
                ),
                'get_records'       => array(
                    'call'      => 'getRecordings',
                    'required'  => array('meetingID'),
                    'optional'  => array(),
                ),
                'publish_records'   => array(
                    'call' => 'publishRecordings',
                    'required'  => array('recordID', 'publish'),
                    'optional'  => array(),
                ),
                'delete_records'    => array(
                    'call' => 'deleteRecordings',
                    'required'  => array('recordID'),
                    'optional'  => array(),
                ),
            );
    }

    //utility function for developers to obtain list of possible call string, will return text containing the information
    function listOfPossibleCall()
    {
        foreach($this->callAndParams as $key => $value){
            $string = '<b>call string</b> : ' . $key . ' <b>BBB Call type</b> : ' . $value['call'];
            //get list of required params
            if(array_key_exists('required' , $value)){
                $string .= '<br/><b>Required Params</b> : ';
                $requiredString = '';
                foreach($value['required'] as $reqParam)
                {
                    $requiredString .= ' ' . $reqParam;
                }
                $string .= $requiredString;
            }
            if(array_key_exists('optional' , $value)){
                $string .= '<br/><b>Optional Params</b> : ';
                $optionalString = '';
                foreach($value['optional'] as $optParam)
                {
                    $optionalString .= ' ' . $optParam;
                }
                $string .= $optionalString;
            }
            echo $string . '<br/><br/>';
        }
    }

    function doAction($type, array $paramsAndValue = array())
    {
		$params = array();
        $response = '';
        //save every param key to stack to compare required parameters
        foreach($paramsAndValue as $key => $val)
        {
            array_push($params, $key);
        }

        if(count($params)){
            //remove unnecessary params if params is sent but not required or optional to ensure that the checksum returned is clean
            if(!array_key_exists('required', $this->callAndParams[$type])
                AND !array_key_exists('optional', $this->callAndParams[$type])){
                //if both required and optional parameters is not set then only consider the salt
                print_r($this->generateUrlWithChecksum($type, array()));exit;
                $response = $this->getResponse($this->generateUrlWithChecksum($type, array()));
            }else{ //if required or optional parameters is set for the call type
                //check required parameters, if one of the required parameter is missing then return false
                if(array_key_exists('required', $this->callAndParams[$type])){
                    foreach($this->callAndParams[$type]['required'] as $reqParam)
                    {
						if(!in_array($reqParam, $params))
                        {
							return false;
                        }else{
                            if(!$paramsAndValue[$reqParam]){
								return false;
                            }
                        }
                    }
                }//end of check required parameters

                //filtering unregistered params
                foreach($paramsAndValue as $key => $val)
                {
                    if(!in_array($key, $this->callAndParams[$type]['required']) AND
                        !in_array($key, $this->callAndParams[$type]['optional'])){
                        unset($paramsAndValue[$key]);
                    }
                }
                $response = $this->getResponse($this->generateUrlWithChecksum($type, $paramsAndValue));
            }//end of if required and optional parameter is set for the call type
        }else{
            //if no param is required for the request
            if(!array_key_exists('required', $this->callAndParams[$type]) OR
                !count($this->callAndParams[$type]['required'])){
                $response = $this->getResponse($this->generateUrlWithChecksum($type, array()));
            }
        }
        return $this->xml2array($response, 1);
    }

    function generateUrlWithChecksum($type, array $params)
    {
        $type = $this->callAndParams[$type]['call'];
        $checkSumStr = $type;
        $urlString = $type . '?';
        $lastIndex = count($params);
        $ctr = 1;
        if(count($params))
        {
            foreach($params as $key => $val)
            {
                $checkSumStr .= $key . '=' . rawurlencode($val);
                $urlString .= $key . '=' . rawurlencode($val);
                if($ctr !== $lastIndex)
                {
                    $checkSumStr .= '&';
                    $urlString .= '&';
                }
                $ctr++;
            }
        }
        return $this->serverUrl . $urlString . '&checksum=' . sha1($checkSumStr . $this->salt);
    }

    //todo: change into better and cleaner function for http request
    function getResponse($url)
    {
        return file_get_contents($url);
    }

    /**
     * xml2array() will convert the given XML text to an array in the XML structure.
     * Link: http://www.bin-co.com/php/scripts/xml2array/
     * Arguments : $contents - The XML text
     *             $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
     *             $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
     * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
     * Examples: $array =  xml2array(file_get_contents('feed.xml'));
     *           $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
     */
    function xml2array($contents, $get_attributes=1, $priority = 'tag') {

		if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Reference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }
        return($xml_array);
    }

    /**
     * Get list of rooms in a more cleaner array format than using do Action directly
     * @return array (collection of meeting)
     */
    public function getRoomList()
    {
        $meetingList = array();
        $response = $this->doAction('list_meeting');
        //if request is valid
        if($response['response']['returncode'] == 'SUCCESS'){
            //if meeting exists
			//if only 1 meeting is created
			if(is_array($response['response']['meetings']['meeting'])){
				if(array_key_exists('meetingID', $response['response']['meetings']['meeting'])){
					if(count($response['response']['meetings']['meeting'])){
						array_push($meetingList, $response['response']['meetings']['meeting']);
					}
				}else{
					if(count($response['response']['meetings'])){
						//put meeting list into a stack
						foreach($response['response']['meetings']['meeting'] as $meeting)
						{
							array_push($meetingList, $meeting);
						}
					}
				}
			}
        }
        return $meetingList;
    }

    /**
     * Create a room with more simple way than directly using doAction method
     * @return array
     * (room creation status - true, false, duplicate, this method also returning the attendee and/or moderator password)
     */
    public function createRoom($option)
    {
		$response = $this->doAction('create', $option);
		$returnArr =  array();
        //if creation is failed
        if($response['response']['returncode'] == 'FAILED'){
            if($response['response']['messageKey'] == 'idNotUnique'){
                //room with that id already exists
                $returnArr = array('status' => 'duplicate');
            }else{
                //request failed
                $returnArr = array('status' => 'false');
            }
        }else{//request is valid and proceed
            $returnArr = array('status' => 'true',
                'attendeePW' => $response['response']['attendeePW'],
                'moderatorPW' => $response['response']['moderatorPW']);
        }
        return $returnArr;
    }

    /**
     * A method to check whether a meeting is running or not (has 1 or more attendee)
     * @param $option
     * @return true/false
     */
    public function checkMeeting($option)
    {
        $response = $this->doAction('check_meeting', $option);
		if($response['response']['returncode'] == 'SUCCESS'){
            return true;
        }else{
            return false;
        }
    }

    /**
     * A method to end a meeting
     * @param $option
     * @return true/false (indicate if meeting is successfully ended)
     */
    public function endMeeting($option)
    {
//        $response = $this->doAction('end_meeting', $option);
//    	return $response;

        $response = $this->doAction('end_meeting', $option);
        if($response['response']['returncode'] == 'FAILED')
        {
            return false;
        }else{
            return true;
        }
	}

    public function getJoinMeetingUrl($option)
    {
        $response = $this->generateUrlWithChecksum('join', $option);
    	return $response;
	}

	public function checkValidJoin($option)
	{
		$url = $this->generateUrlWithChecksum('join', $option);
		$response = $this->xml2array(file_get_contents($url));
		if($response['response']['returncode'] == 'FAILED')
		{
			return false;
		}else{
			return true;
		}
	}

}