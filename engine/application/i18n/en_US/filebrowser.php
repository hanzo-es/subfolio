<?php 

$lang = array
(
	'indexof'               => 'index of',
	'logout'                => 'logout',
	'sendpage'              => 'send page',
	'collapseheader'        => 'collapse header',
	'expandheader'          => 'expand header',

	'filename'              => 'filename',
	'size'                  => 'size',
	'date'                  => 'date',
	'kind'                  => 'kind',
	'comment'               => 'comment',

  'accessdenied'          => 'Access Denied',
  'loginasadifferentuser' => 'Log in as a different user',
  'youdonthaveaccesstothisdirectory' => 'You don\'t have access to this directory.',

  'notfound'              => 'The item that you are looking for was not found.',
  'check_url_go_back'     => 'Please check the url or go to the %s for a file listing.',

  'enter_user_password'   => 'Enter your username and password:',
  'remember_my_login'     => 'Remember my login',

  'submit'                => 'Submit',
  'invalid_user_password'   => 'Invalid username or password.',
  
  'error'                 => 'Error',
);

// Override these settings from the content of a language Yaml file

$language_file = Kohana::config('filebrowser.language_yaml_file');

if ($language_file <> '') {
  if (file_exists($language_file)) {
    $array = Spyc::YAMLLoad($language_file);
    foreach ($array as $name => $value) {
      $lang[$name] = $value;
    }
  }
}

?>