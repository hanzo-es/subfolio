<?php  

class Access {
  var $is_restricted = false;

  var $allowed_groups = array();
  var $allowed_users = array();
  var $denied_users = array();
  var $denied_groups = array();

  var $current_allowed_groups = array();
  var $current_allowed_users = array();

  public function __construct() {
    $this->session = Session::instance();
  }

	public static function instance() {
    static $instance;
    // Load the Authlite instance
    empty($instance) and $instance = new Access();
    return $instance;
	}
  
	public function load_access($rootFolder, $checking_child=false) {
    $source_directory = Kohana::config('filebrowser.directory');
    $access_file      = Kohana::config('filebrowser.access_file');

    $folders = explode("/", $rootFolder);
    $file_list = array();
    
    if (!$checking_child) {
    $current_file = $access_file;
    if (file_exists($current_file)) {
      $file_list[] = $current_file;
    } else {
      $current_file = $access_file.".txt";
      if (file_exists($current_file)) {
        $file_list[] = $current_file;
      }
    }
    }


    if (sizeof($folders) > 0) {
      $root_path = "";
      $folder_path = "";
      $folders = array_reverse($folders);
      foreach ($folders as $folder) {

        if ($root_path == "") {
          if ($checking_child) {
            $current_file = $folder."/".$access_file;
            if (file_exists($current_file)) {
              $file_list[] = $current_file;
            } else {
              $current_file = $folder."/".$access_file.".txt";
              if (file_exists($current_file)) {
                $file_list[] = $current_file;
              }              

            }
          }
        }

        if ($folder <> "") {
          $file = $root_path ."" . $access_file;
          if (file_exists($file)) {
            $file_list[] = $file;
          } else {
            $file = $root_path ."" . $access_file.".txt";
            if (file_exists($file)) {
              $file_list[] = $file;
            } 
          }
          $root_path .= "../";
        }

      }
      
      // Need to check the root of the site
      $file = $root_path.$access_file;
      if (file_exists($file)) {
        $file_list[] = $file;
      } else {
        $file = $root_path.$access_file.".txt";
        if (file_exists($file)) {
          $file_list[] = $file;
        }
      }
    }

    if (sizeof($file_list) > 0) {
      $this->is_restricted = true;

      $file_list = array_reverse($file_list);
      foreach($file_list as $id => $path) {
        $current = false;

        if ($current_file == $path) {
          $current = true;
        }
        $restricted = $this->load_access_file($path, $current);
        if ($restricted) {
          $this->is_restricted = true;
        }
      }
    }
  }

	public function is_restricted() {
    return $this->is_restricted;
  }
	
	public function check_access($user) {
    $groups = array();
    $have_access = false;
    
    if ($user && $user->admin) {
      return true;
    }
    
    if ($this->is_restricted) {
      $have_access = false;
      if ($user != null) {
    	  $groups = $user->get_groups();
    	  
        if (in_array($user->name, $this->allowed_users)) {
            $have_access = true;
        } else {
            // check groups
            
            $by_groups = array_intersect($groups, $this->allowed_groups);
            
            if (sizeof($by_groups) > 0) {
                $have_access = true;
            }
        }
  
        if ($have_access) {
          // check to see if user denied
          if (in_array($user->name, $this->denied_users)) {
              $have_access = false;
          } else {
            // check to see if user group
            $by_groups = array_intersect($groups, $this->denied_groups);
            
            if (sizeof($by_groups) > 1) {
              $by_groups = array_diff($by_groups, $this->denied_groups);
              if (sizeof($by_groups) < 1) {
                $have_access = false;
              }
            }
          }
        }
  
        if ($have_access == false) {
          if (in_array($user->name, $this->current_allowed_users)) {
            $have_access = true;
          } else {
            // check groups
            $by_groups = array_intersect($groups, $this->current_allowed_groups);
            if (sizeof($by_groups) > 0) {
                $have_access = true;
            }
          }
        }
      }
    } else {
      $have_access = true;
    }
    
    return $have_access;
	}
	
	public function load_access_file($file, $current=false) {
	  $have_data = false;
    $array = Spyc::YAMLLoad($file);
    
    if (isset($array['allow_users'])) {
      foreach($array['allow_users'] as $user) {
        $this->allow_user($user);
      }
  	  $have_data = true;
    }

    if (isset($array['allow_groups'])) {
      foreach($array['allow_groups'] as $group) {
        $this->allow_group($group);
      }
  	  $have_data = true;
    }

    if (isset($array['deny_users'])) {
      foreach($array['deny_users'] as $user) {
        $this->deny_user($user);
      }
  	  $have_data = true;
    }

    if (isset($array['deny_groups'])) {
      foreach($array['deny_groups'] as $group) {
        $this->deny_group($group);
      }
  	  $have_data = true;
    }
    
    if ($current) {
      if (isset($array['current_folder'])) {
    	  $have_data = true;

        if (isset($array['current_folder']['allow_users'])) {
          foreach($array['current_folder']['allow_users'] as $user) {
            $this->current_allow_user($user, $current);
          }
        }
        
        if (isset($array['current_folder']['allow_groups'])) {
          foreach($array['current_folder']['allow_groups'] as $group) {
            $this->current_allow_group($group, $current);
          }
        }
    
        /*
        foreach($array['current_folder']['deny_users'] as $user) {
          $this->deny_user($user, $current);
        }
    
        foreach($array['current_folder']['deny_groups'] as $group) {
          $this->deny_group($group, $current);
        }
        */
      }
    }
	  return $have_data;
	}


  function current_allow_user($user) {
    $this->current_allowed_users[$user] = $user;  
  }

  function current_allow_group($group) {
    $this->current_allowed_groups[$group] = $group;  
  }

  function allow_user($user) {
    if (in_array($user, $this->denied_users)) {
        unset($this->denied_users[$user]);
    }
    $this->allowed_users[$user] = $user;
  }

  function allow_group($group) {
    if (in_array($group, $this->denied_groups)) {
      unset($this->denied_groups[$group]);
    }
    $this->allowed_groups[$group] = $group;
  }

  function deny_user($user) {
    if (in_array($user, $this->allowed_users)) {
      unset($this->allowed_users[$user]);
    }
    $this->denied_users[$user] = $user;
  }

  function deny_group($group) {
    if (in_array($group, $this->allowed_groups)) {
      unset($this->allowed_groups[$group]);
    }
    $this->denied_groups[$group] = $group;
  }
	
}