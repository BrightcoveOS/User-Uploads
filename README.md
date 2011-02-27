About
=====

This project allows you to easily create a user-generated content (UGC)
uploader.

How It Works
============

All form fields POST'ed to a page that inclues the User Uploads library
will automatically be detected and parsed. Form fields should be named in
the following convention:

	// The 'name' field of a video
	bc-video
	
	// The 'short description' field of a video
	bc-shortDescription
	
	// A custom field named 'category'
	bc-customFields-category
	
	// The video file
	bc-video

The naming schemes follow
[Brightcove video DTO conventions](http://support.brightcove.com/en/docs/media-api-objects-reference#Video).
The following fields are accepted at this time:

 * [string] name
 * [string] referenceId
 * [string] shortDescription
 * [string] longDescription
 * [string] itemState
 * [date] startDate
 * [date] endDate
 * [string] linkURL
 * [string] linkText
 * [array] tags
 * [array] customFields
 * [string] economics

Requirements
============

You must download the Brightcove
[PHP MAPI Wrapper](https://github.com/BrightcoveOS/PHP-MAPI-Wrapper)
for use in conjunction with this library.

PHP version 5.2 or greater, or you must have the JavaScript Object Notation
(JSON) PECL package. For more information on the JSON PECL package, please
visit the [PHP JSON](http://www.php.net/json) package website.

Installation Notes
==================

You must follow standard PHP file upload standards (include a form encoding
type and a MAX\_FILE\_SIZE).

You must also ensure that your PHP settings related to forms and file
upload sizes are set appropriately. For example:

	# .htaccess file settings
	php_value	memory_limit			256M
	php_value	max_execution_time		300
	php_value	max_input_time			300
	php_value	max_input_size			200M
	php_value	post_max_size			200M
	php_value	upload_max_filesize		200M

Front-End Sample (upload.html)
==============================

	<form action="handle_upload.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="MAX_FILE_SIZE" value="209715200" />
	<input type="hidden" name="bc-tags" value="user-upload,ugc,video" />
	<table>
		<tr>
			<td>
				Name
			</td>
			<td>
				<input type="text" name="bc-name" />
			</td>
		</tr>
		<tr>
			<td>
				Short Description
			</td>
			<td>
				<textarea name="bc-shortDescription"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				Project Name
			</td>
			<td>
				<input type="text" name="bc-customField-projectName" />
			</td>
		</tr>
		<tr>
			<td>
				Video File
			</td>
			<td>
				<input type="file" name="bc-video" />
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				<input type="submit" value="Upload" />
			</td>
		</tr>
	</table>
	</form>

Back-End Sample (handle_upload.php)
===================================

	<?php
	
		// Include the PHP MAPI Wrapper
		require('bc-mapi.php');
		
		// Include the User Uploads library
		require('bc-user-uploads.php');
		
		// Instantiate the PHP MAPI Wrapper
		$bc = new BCMAPI(
			'READ_TOKEN',
			'WRITE_TOKEN'
		);
		
		// You may pass 'create_video' parameters (e.g. 'H264NoProcessing')
		// as the second instantiation parameter. This is OPTIONAL.
		$options = array(
			'preserve_source_rendition' => 'true'
		);
		
		// Instantiate the User Uploads library
		// You must pass the PHP MAPI Wrapper object
		new BCUserUploads($bc, $options);
	
	?>