<?php

/**
 * Brightcove User Uploads 1.0.0 (25 FEBRUARY 2011)
 *
 * REFERENCES:
 *	 Website: http://opensource.brightcove.com
 *	 Source: http://github.com/brightcoveos
 *
 * AUTHORS:
 *	 Matthew Congrove <mcongrove@brightcove.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the “Software”),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, alter, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to
 * whom the Software is furnished to do so, subject to the following conditions:
 *   
 * 1. The permission granted herein does not extend to commercial use of
 * the Software by entities primarily engaged in providing online video and
 * related services.
 *  
 * 2. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT ANY WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, SUITABILITY, TITLE,
 * NONINFRINGEMENT, OR THAT THE SOFTWARE WILL BE ERROR FREE. IN NO EVENT
 * SHALL THE AUTHORS, CONTRIBUTORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY WHATSOEVER, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH
 * THE SOFTWARE OR THE USE, INABILITY TO USE, OR OTHER DEALINGS IN THE SOFTWARE.
 *  
 * 3. NONE OF THE AUTHORS, CONTRIBUTORS, NOR BRIGHTCOVE SHALL BE RESPONSIBLE
 * IN ANY MANNER FOR USE OF THE SOFTWARE.  THE SOFTWARE IS PROVIDED FOR YOUR
 * CONVENIENCE AND ANY USE IS SOLELY AT YOUR OWN RISK.  NO MAINTENANCE AND/OR
 * SUPPORT OF ANY KIND IS PROVIDED FOR THE SOFTWARE.
 */

class BCUserUploads {
	private $api = NULL;
	
	public function __construct($api, $upload_options = NULL)
	{
		$this->api = $api;
		
		$data = array();
		$video = NULL;
		
		foreach($_POST as $key => $value)
		{
			if(substr($key, 0, 3) == 'bc-')
			{
				$field = str_replace('bc-', '', $key);
				
				if(substr($field, 0, 12) == 'customField-')
				{
					$field = str_replace('customField-', '', $field);
					$data['customFields'][$field] = $value;
				} else {
					$data[$field] = $value;
				}
			}
		}
		
		if(isset($data['tags']))
                {
                        $data['tags'] = $this->handleTags($data['tags']);
                        $data['tags'][] = 'User-Uploaded';
                }
		
		if(isset($_FILES['bc-video']))
		{
			if($_FILES['bc-video']['error'] !== 0)
			{
				// gulp
			}

			$location = dirname($_FILES['bc-video']['tmp_name']) . '/' . $_FILES['bc-video']['name'];
			rename($_FILES['bc-video']['tmp_name'], $location);

			$video = $location;
		}
		
		$options = array(
			'encode_to' => 'MP4',
			'create_multiple_renditions' => 'true'
		);
		
		if(isset($upload_options))
		{
			$options = array_merge($options, $upload_options);
		}
		
		$this->api->createMedia('video', $video, $data, $options);
	}
	
	private function handleTags($tags)
	{
		
		if (!is_array($tags)) {
			$tags = explode(',', trim($tags));
		}
		
		$pattern = '/[\t\n\r]+/';
		
		foreach($tags as $key => $value)
		{
			preg_match($pattern, $value, $matches);
			
			if($matches)
			{
				unset($tags[$key]);
			}
		}
		
		return $tags;
	}
}

?>