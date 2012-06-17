eManga - Online manga reader
==================

Author: Álvaro Carneiro [d3sign.night@gmail.com](mailto:d3sign.night@gmail.com)

eManga is a easy online manga reader. It doesn't needs a database.

### Before using it

You will need to download a manga and uploading it to `mangas/`

This repository doesn't have any manga to prevent problems :)

###License

eManga is under the MIT License

###Instructions

Open `src/config.php` and edit it

```php

<?php

return array(
	
	// Web configuration
	'web'		=>	array(
		
		// The title of your web to put it into <title> tag
		'title'		=>	'eManga - Online manga reader',
		
		// The name of your web
		'name'		=>	'eManga',
		
		// The url of the web, if it is null it will be auto-generated
		'url'			=>	null,
		
		// The folder where the application is in
		'folder'		=>	'emanga',
		
		// The charset
		'charset'	=>	'UTF-8'
	),
	
	// The configuration to the mangas
	'mangas'		=>	array(
		
		// The path where the mangas are in
		'path'						=>	'mangas',
		
		// Array with the allowed extensions that must have the manga image
		'allowed_extensions'			=>	array('jpg', 'jpeg', 'png', 'bmp', 'gif'),
		
		// The maximum width that could have the manga. In case that the manga was higher then resize it
		'max_width'					=>	null
		
	)
	
);

```

After you upload to the server you will need to change some text in the .htaccess file:

` RewriteCond $1 !^(index\.php|media|mangas) `

` RewriteRule ^(.*)$ /emanga/index.php/$1 [L] `

In `RewriteCond` you will put the name of the path where you have the media files and the mangas

In `RewriteRule` before /index.php/ you need to put the name of the directory/directories that you have the application in.

### Donate

Feel free to donate. it's hard to be a 16 years old unpaid PHP programmer :(

[Donate](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=L5ZA6BHERQHZG&lc=UY&item_name=PHP&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted)

