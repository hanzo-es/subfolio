<?php
/**
 *
 */
class Subfolio {
  public static $filebrowser;
  public static $auth;
  public static $template;
  public static $filekind;

  public static function set_filebrowser($_filebrowser) {
    Subfolio::$filebrowser = $_filebrowser;
  }
  public static function set_auth($_auth) {
    Subfolio::$auth = $_auth;
  }
  public static function set_template($_template) {
    Subfolio::$template = $_template;
  }
  public static function set_filekind($_filekind) {
    Subfolio::$filekind = $_filekind;
  }

  public static function link_to($text, $url) {
    return html::anchor($url, $text);
  }

  public static function mail_to($text, $email, $subject, $body) {
    return "<a href='mailto:$email?subject=$subject&body=$body'>$text</a>";
  }

  public static function current_url()
  {
    return "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];;
  }

  public static function get_setting($name)
  {
    return Kohana::config('filebrowser.' . $name);
  }

  public static function current_file($data)
  {
    if ($data == "width") {
      $file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$template->content->file->name);
      if (isset($file_kind['kind'])) {
        $kind = $file_kind['kind'];
      } else {
        $kind = "";
      }

      if ($kind == "img") {
        list($width, $height, $type, $attr) = @getimagesize(Subfolio::$filebrowser->fullfolderpath."/".Subfolio::$template->content->file->name);
        return $width;
      } else {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'width') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'width') : '640';
      }
    }

    if ($data == "height") {
      $file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$template->content->file->name);
      if (isset($file_kind['kind'])) {
        $kind = $file_kind['kind'];
      } else {
        $kind = "";
      }

      if ($kind == "img") {
        list($width, $height, $type, $attr) = @getimagesize(Subfolio::$filebrowser->fullfolderpath."/".Subfolio::$template->content->file->name);
        return $height;
      } else {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'height') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'height') : '480';
      }
    }

    if ($data == "icon") {
      if (Subfolio::$filebrowser->file <> '') {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->file);
    	} else {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->folder);
    	}


      $icon_file = "";
      $icon_file = Subfolio::$filekind->get_icon_by_file($file_kind);

      $icon_set  = view::get_option('icon_set_list',  "list");
      $icon_set_grid  = view::get_option('icon_set_grid',  "grid");

      $icon = $icon_set."_".$icon_file;
      $icon_grid = $icon_set_grid."_".$icon_file;

      return $icon_grid;
    }

    if ($data == "icon_name") {
      if (Subfolio::$filebrowser->file <> '') {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->file);
    	} else {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->folder);
    	}
    	$icon_name = "";
    	$icon_name = Subfolio::$filekind->get_icon_by_file($file_kind);
      return $icon_name;
    }

    if ($data == "tag") {
    	$new = false;
    	$updated = false;

      if (isset(Subfolio::$template->content->file->stats['mtime'])) {
      	$new_updated_start = Subfolio::$filebrowser->get_updated_since_time();
        if (Subfolio::$template->content->file->stats['mtime'] > $new_updated_start) {
            $updated = true;
        }
        if ($new) {
          return "new";
        } else if ($updated) {
          return "updated";
        } else {
          return "";
        }
      } else {
        return "";
      }
    }

    if ($data == "url") {
      return Subfolio::$filebrowser->get_file_url();
    }

    if ($data == "link") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_file_url();
      } else {
        	return "/directory/".Subfolio::$template->content->folder."/index.html";
      }
    }

    if ($data == "target") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'target') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'target') : 'blank';
      } else {
        return Subfolio::$filebrowser->get_folder_property('target') ? Subfolio::$filebrowser->get_folder_property('target') : 'blank';
      }
    }

    if ($data == "filename") {
      if (Subfolio::$filebrowser->file <> '') {
        return htmlentities(Subfolio::$template->content->file->name);
    	} else {
        $base = basename(Subfolio::$filebrowser->folder);
  	  	$ff = new FileFolder($base, $base, 'folder', "folder", array());
        return format::filename($ff->get_display_name());
      }
    }

    if ($data == "lastmodified") {
      if (Subfolio::$filebrowser->file <> '') {
        if (isset(Subfolio::$template->content->file->stats['mtime'])) {
          return format::filedate(Subfolio::$template->content->file->stats['mtime'], "M d, Y – H:i");
        } else {
          return "–";
        }
      }  else {
        return "–";
      }
    }

    if ($data == "size") {
      if (isset(Subfolio::$template->content->file->stats['size'])) {
        return format::filesize(Subfolio::$template->content->file->stats['size']) ? format::filesize(Subfolio::$template->content->file->stats['size']) : "—";
      } else {
        return "–";
      }
    }

    if ($data == "rawsize") {
      if (isset(Subfolio::$template->content->file->stats['size'])) {
        return Subfolio::$template->content->file->stats['size'];
      } else {
        return 0;
      }
    }

    if ($data == "comment") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'comment') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'comment') : '—';
      } else {
        return Subfolio::$filebrowser->get_folder_property('comment') ? Subfolio::$filebrowser->get_folder_property('comment') : '—';
      }
    }

    if ($data == "autoplay") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'autoplay') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'autoplay') : '';
      } else {
        return "";
      }
    }

    if ($data == "kind") {
      if (Subfolio::$filebrowser->file <> '') {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->file);
    	} else {
      	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->folder);
    	}
    	return isset($file_kind['display']) ? $file_kind['display'] : '—';
  	}

    if ($data == "extension") {
      $file_extension = Subfolio::$filekind->get_extension_by_file(Subfolio::$template->content->file->name);
      if(strlen($file_extension) > 6) $file_extension = substr($file_extension, 0, 3);
      return isset($file_extension) ? $file_extension : '';
    }

    if ($data == "feedurl") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'feedurl') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'feedurl') : '';
    	} else {
      	return '';
    	}
  	}

    if ($data == "count") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'count') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'count') : '';
    	} else {
      	return '';
    	}
  	}

    if ($data == "cache") {
      if (Subfolio::$filebrowser->file <> '') {
        return Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'cache') ? Subfolio::$filebrowser->get_item_property(Subfolio::$filebrowser->file, 'cache') : 3600;
    	} else {
      	return 3600;
    	}
  	}

    if ($data == "instructions") {
    	$file_kind = Subfolio::$filekind->get_kind_by_file(Subfolio::$filebrowser->file);
    	return isset($file_kind['instructions']) ? $file_kind['instructions'] : '';
  	}

    if ($data == "body") {
      return format::get_rendered_text(file_get_contents(Subfolio::$filebrowser->fullfilepath));
    }

    if ($data == "preview") {
        $ext = Subfolio::$filekind->get_extension_by_file(Subfolio::$template->content->file->name);
        if ($ext != "psd") {
            return "";
        }
        $name = preg_replace("/\.psd$/i","-preview.jpg", Subfolio::$template->content->file->name);
        if (!file_exists(Subfolio::$filebrowser->fullfolderpath."/-thumbnails/".$name)) {
            $tmpfname = tempnam("/tmp", "subfolio");
            $im = new Imagick(Subfolio::$template->content->file->name);
            $im->flattenImages();
            $im->setImageFormat('jpg');
            $im->writeImage($tmpfname);
            $im->destroy();

            $image = new Image($tmpfname);
            $image->save(Subfolio::$filebrowser->fullfolderpath."/-thumbnails/".$name);
        }
        return "/directory/".format::urlencode_parts(Subfolio::$filebrowser->folder)."/-thumbnails/".Filebrowser::double_encode_specialcharacters(urlencode($name));
    }

    return "&nbsp;";
  }

}

class SubfolioTheme extends Subfolio {

  // ------------------------------------------------------
  // TEMPLATE RELATED FUNCTIONS
  // ------------------------------------------------------

  public static function get_mobile_viewport()
  {
    return (SubfolioTheme::is_iphone());
  }

  public static function is_iphone()
  {
    if (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
      return true;
    } else {
      return false;
    }
  }

  public static function get_page_title() {
    return isset(Subfolio::$template->page_title) ? htmlentities(Subfolio::$template->page_title) : "";
  }

  public static function get_site_title() {
    return isset(Subfolio::$template->site_title) ? Subfolio::$template->site_title : "";
  }

  public static function get_site_copyright() {
    $show = true;
    $show = (BOOLEAN) view::get_option('display_copyright', true);
    if ($show) {
      return Kohana::config('filebrowser.site_copyright', NULL);
    } else {
      return NULL;
    }
  }

  public static function get_site_meta_description() {
    return Kohana::config('filebrowser.site_meta_description', null);
  }

  public static function get_site_favicon_url() {
    return view::get_option('site_favicon_url');
  }

  public static function get_color_palette_name() {
    return view::get_option('color_palette');
  }

  public static function get_site_name() {
    $site_name = Kohana::config('filebrowser.site_name');;
    $site_name_display = $site_name;
    $logo = Kohana::config('filebrowser.site_logo_url');
    $logo = view::get_option('site_logo_url', $logo);
    if ($logo <> "") {
    	$width = Kohana::config('filebrowser.site_logo_width');
    	$width = view::get_option('site_logo_width', $width);

  		$height = Kohana::config('filebrowser.site_logo_height');
    	$height = view::get_option('site_logo_height', $height);

  		if ($width <> '') { $width = " width='$width' "; }
  		if ($height <> '') { $height = " height='$height' "; }
      $site_name_display = "<img $width $height src='$logo' alt='$site_name' />";
    }

    return $site_name_display;
  }

  public static function get_view_url() {
    return view::get_view_url();
  }

  public static function get_listing_mode(){
  	$listing_mode = Kohana::config('filebrowser.listing_mode');
		if (!SubfolioTheme::get_mobile_viewport()) {
			$listing_mode = view::get_option('listing_mode', $listing_mode);
	  	$listing_mode = Subfolio::$filebrowser->get_folder_property('listing_mode', $listing_mode);
		}
  	return $listing_mode;
  }

  public static function get_notice($name=null)
  {
    if ($name == null) {
      $name = 'flash';
    }
    return Session::instance()->get($name);
  }

  public static function get_breadcrumb() {
    $replace_dash_space = view::get_option('replace_dash_space', true);
    $replace_underscore_space = view::get_option('replace_underscore_space', true);
    $display_file_extensions = view::get_option('display_file_extensions', true);

    $breadcrumbs = array();

    $ff = Subfolio::$filebrowser->get_path();
    $parts = explode( "/", $ff);
    $count = 1;
    if ($ff <> "" && sizeof($parts) > 0) {
      $path = "/";
      foreach ($parts as $key => $value) {
        $crumb = array();
        $crumb['name'] = htmlentities(FileFolder::fix_display_name($value, $replace_dash_space, $replace_underscore_space, $display_file_extensions));
        $evalue = Filebrowser::double_encode_specialcharacters(urlencode($value));
        if ($count == sizeof($parts)) {
          $crumb['url'] = '';
        } else {
          $crumb['url'] = $path.$evalue;
        }
        $path .= $evalue."/";
        $breadcrumbs[] = $crumb;
        $count ++;
      }
    }

    return $breadcrumbs;
  }

  public static function subfolio_link()
  {
		return "<a href='http://www.subfolio.com' id='footer-home' target='_blank'>Subfolio</a>";
  }

  public static function get_collapse_header_button($wrap=""){
    $showHideLabel = "".SubfolioLanguage::get_text('collapseheader');
    $link = "<a id='showHideSwitch' class='collapseheader__link' data-behavior='switch_header' href='#'>".$showHideLabel."</a>";
    if ($wrap <> '') {
      $link = "<$wrap class='collapseheader'>".$link."</$wrap>";
    }
    return $link;
  }

  public static function get_tiny_url($name, $wrap=""){
    $link = "<a href=\"javascript:void(location.href='http://tinyurl.com/create.php?url='+encodeURIComponent(location.href))\">$name</a>";
    if ($wrap <> '') {
      $link = "<$wrap>".$link."</$wrap>";
    }
    return $link;
  }

  public static function get_locked_icon_url($mode='list') {
    return self::get_theme_icon($mode, "lock");
  }

  public static function get_unlocked_icon_url($mode='list') {
    return self::get_theme_icon($mode, "unlocked");
  }

  public static function get_updated_icon_url($mode='list') {
    return self::get_theme_icon($mode, "updated");
  }

  public static function get_new_icon_url($mode='list') {
    return self::get_theme_icon($mode, "new");
  }

  // ------------------------------------------------------
  // THEME RELATED FUNCTIONS
  // ------------------------------------------------------

  public static function get_option($option_name, $default_value=null)
  {
    return view::get_option($option_name, $default_value);
  }

  // ------------------------------------------------------
  // COLOR RELATED FUNCTIONS
  // ------------------------------------------------------
  public function get_color($color_name, $default_value=NULL) {
    return view::get_color($color_name, $default_value);
  }

  private function get_theme_icon($mode, $icon_file)
  {
    if ($mode == "grid") {
      $icon_set  = view::get_option('icon_set_grid',  "grid");
    } else {
      $icon_set  = view::get_option('icon_set_list',  "list");
    }

    $icon = view::get_view_url()."/images/icons/".$icon_set."/".$icon_file.".png";
    return $icon;
  }

}

class SubfolioUser extends Subfolio {
  // ------------------------------------------------------
  // USER RELATED FUNCTIONS
  // ------------------------------------------------------
  public static function is_logged_in()
  {
    return Subfolio::$auth->logged_in();
  }

  public static function is_admin()
  {
    if (Subfolio::$auth->logged_in()) {
      return Subfolio::$auth->get_user()->admin;
    } else {
      return NULL;
    }
  }

  public static function current_user_name() {
    if (Subfolio::$auth->logged_in()) {
      return Subfolio::$auth->get_user()->name;
    } else {
      return NULL;
    }
  }

  public static function current_user_fullname() {
    if (Subfolio::$auth->logged_in()) {
      return Subfolio::$auth->get_user()->fullname;
    } else {
      return NULL;
    }
  }
}

class SubfolioLanguage extends Subfolio {
  // ------------------------------------------------------
  // LANGUAGE RELATED FUNCTIONS
  // ------------------------------------------------------
  public function get_text($name, $args = array())
  {
    return Kohana::lang("filebrowser.".$name, $args);
  }
}

class SubfolioFiles extends Subfolio {
  // ------------------------------------------------------
  // FILE/FOLDER RELATED FUNCTIONS
  // ------------------------------------------------------
  public function have_inline_images($type)
  {
    $have = false;
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-t-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-m-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-b-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    }

    return $have;
  }

  public function inline_images($type)
  {
    $list = array();
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-t-", true);
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-m-", true);
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("img", "-b-", true);
    }

    foreach ($inline as $item) {
      list($width, $height, $type, $attr) = @getimagesize(Subfolio::$filebrowser->fullfolderpath."/".$item->name);
      $list_item = array();
      $list_item['url'] = "/directory".Subfolio::$filebrowser->get_link($item->name);
      $list_item['width'] = $width;
      $list_item['height'] = $height;

      $list[] = $list_item;
    }
    return $list;
  }

  public function have_inline_texts($type)
  {
    $have = false;
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-t-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-m-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-b-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    }

    return $have;
  }

  public function inline_texts($type)
  {
    $list = array();
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-t-", true);
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-m-", true);
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("txt", "-b-", true);
    }

    foreach ($inline as $item) {
      $list[] = array('body' =>format::get_rendered_text(file_get_contents($item->name)));
    }
    return $list;
  }

  public function have_inline_rss($type)
  {
    $have = false;
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-t-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-m-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-b-", true);
      if (sizeof($inline) > 0) {
        $have = true;
      }
    }

    return $have;
  }

  public function inline_rss($type)
  {
    $list = array();
    if ($type == "top") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-t-", true);
    } else if ($type == "middle") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-m-", true);
    } else if ($type == "bottom") {
      $inline = Subfolio::$filebrowser->get_file_list("rss", "-b-", true);
    }

    foreach ($inline as $item) {
    	$rss = Spyc::YAMLLoad($item->name);
      $list_item = array();
      if (isset($rss['feedurl'])) {
        $list_item['feedurl'] = $rss['feedurl'];
        $list_item['filename'] = $item->name;

        if (isset($rss['count'])) {
          $list_item['count'] = (integer) $rss['count'];
        } else {
          $list_item['count'] = 10;
        }
        if (isset($rss['cache'])) {
          $list_item['cache'] = (integer) $rss['cache'];
        } else {
          $list_item['cache'] = 3600;
        }
      }

      $list[] = $list_item;
    }
    return $list;
  }


  public function have_features()
  {
    $file_features = Subfolio::$filebrowser->get_file_list("ftr", null, true);
    return (sizeof($file_features) > 0);
  }

  public function features()
  {
    $list = array();
    $file_features = Subfolio::$filebrowser->get_file_list("ftr", null, true);
    foreach ($file_features as $file_feature) {
      $item = array();

	    $feature = Spyc::YAMLLoad($file_feature->name);
	    $feature_link = "";
	    if (isset($feature['link'])) {
	      $feature_link = $feature['link'];
	    } else if (isset($feature['folder'])) {
        if (Subfolio::$filebrowser->get_folder() == '') {
  	      $feature_link = "/".$feature['folder'];
        } else {
  	      $feature_link = "/".Subfolio::$filebrowser->get_folder()."/".$feature['folder'];
	      }
	    } else if (isset($feature['file'])) {
        if (Subfolio::$filebrowser->get_folder() == '') {
  	      $feature_link = "/".$feature['file'];
        } else {
  	      $feature_link = "/".Subfolio::$filebrowser->get_folder()."/".$feature['file'];
	      }
	    }

      $item['link'] = $feature_link;
      if (isset($feature['image'])) {
        $item['image_file'] = "/directory/".Subfolio::$filebrowser->get_folder()."/".$feature['image'];
  			list($width, $height, $type, $attr) = @getimagesize(Subfolio::$filebrowser->fullfolderpath."/".$feature['image']);
  			$item['image_width'] = $width;
        $item['image_height'] = $height;
      } else {
        $item['image_width'] = isset($feature['width']) ? $feature['width'] : NULL;
        $item['image_height'] = isset($feature['height']) ? $feature['height'] : NULL;
      }
      $item['width'] = isset($feature['width']) ? $feature['width'] : NULL;
      $item['height'] = isset($feature['height']) ? $feature['height'] : NULL;
      $item['title'] = isset($feature['title']) ? $feature['title'] : "";
      $item['target'] = isset($feature['target']) ? $feature['target'] : NULL;
      $item['description'] = isset($feature['description']) ? $feature['description'] : "";

      $list[] = $item;
    }
    return $list;
  }

  public function have_gallery_images()
  {
    $files = Subfolio::$filebrowser->get_file_list("img");
    $items = array_merge($files, Subfolio::$filebrowser->get_file_list("psd"));
    if (sizeof($items) > 0) {
      return true;
    }
    return false;
  }

  public function gallery_images($listing_mode='list')
  {
    $display_filenames = view::get_option('display_file_names_in_gallery', true);

    $replace_dash_space = view::get_option('replace_dash_space', true);
    $replace_underscore_space = view::get_option('replace_underscore_space', true);
    $display_file_extensions = view::get_option('display_file_extensions', true);

    $files = Subfolio::$filebrowser->get_file_list("img");
    $files = array_merge($files, Subfolio::$filebrowser->get_file_list("psd"));

    $gallery = array();
    foreach ($files as $file) {
  		if ($file->needs_thumbnail()) {
  		  $image_source = $file->get_thumbnail_url($listing_mode);
  		} else {
    		$image_source = $file->get_url();
    	}

      list ($width, $height) = $file->get_gallery_width_height();

      if ($image_source <> '') {
        $image = array();
        $image['width'] = $width;
        $image['height'] = $height;
				$image['container_width'] = $width;
  			if ($file->has_custom_thumbnail()) {

  			  $image['class'] = "gallery_thumbnail custom";
  			  $image['link'] = Subfolio::$filebrowser->get_link(Filebrowser::double_encode_specialcharacters($file->name));
  			  $image['filename'] = $file->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions);
  			  $image['url'] = $image_source;
  			  $image['container_height'] = $height;

  			// Custom thumbnails -----------------------------------------------------------------------------
      	} else {
  			// Genrerated or not thumbnails -----------------------------------------------------------------------------
  			  $image['class'] = "gallery_thumbnail";
  			  $image['link'] = Subfolio::$filebrowser->get_link(Filebrowser::double_encode_specialcharacters($file->name));
  			  $image['filename'] = $file->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions);
  			  $image['url'] = $image_source;
					$image['container_height'] = SubfolioTheme::get_option('thumbnail_height');
  			}
  			$gallery[] = $image;
			}
		}
    return $gallery;
  }

  public function is_empty_folder()
  {
    $is_empty = !((boolean) Subfolio::$filebrowser->file_or_folder_count(TRUE));
    return $is_empty;
  }

  public function have_files()
  {
    $folders = Subfolio::$filebrowser->get_folder_list();
    $folders = Subfolio::$filebrowser->sort($folders);

    $files  = Subfolio::$filebrowser->get_file_list();
    $files  = Subfolio::$filebrowser->sort($files);

    $haveFiles = false;
    if (sizeof($folders) > 0) {
      if (!$this->auth->get_user()) { // user is not logged in so must display
        $haveFiles = true;
      } else {
        foreach ($folders as $folder) {
        	if ($folder->have_access($this->auth->get_user())) {
            $have_access = true;
        	} else {
            $have_access = false;
        	}

          if ($have_access && !Subfolio::$filebrowser->is_feature($folder->name)) {
            $haveFiles = true;
            break;
          }
        }
      }
    }

    if (!$haveFiles) {
      foreach ($files as $file) {

        if (!Subfolio::$filebrowser->is_feature($file->name)) {
        $file_kind = Subfolio::$filekind->get_kind_by_file($file->name);

        if (isset($file_kind['kind'])) {
          $kind = $file_kind['kind'];
        } else {
          $kind = "";
        }

        if ($kind == "img") {
          if ($file->needs_thumbnail()) {
            if ($file->get_thumbnail_url() == '') {
              $haveFiles = true;
              break;
            }
          }
        } else {
          $haveFiles = true;
          break;
        }
      }
      }
    }

    return $haveFiles;
  }

  public function files()
  {
    $listing_mode = SubfolioTheme::get_listing_mode();
    $replace_dash_space = view::get_option('replace_dash_space', true);
    $replace_underscore_space = view::get_option('replace_underscore_space', true);
    $display_file_extensions = view::get_option('display_file_extensions', true);

    $folders = Subfolio::$filebrowser->get_folder_list();
    $folders = Subfolio::$filebrowser->sort($folders);
    $files  = Subfolio::$filebrowser->get_file_list();
    $files  = Subfolio::$filebrowser->sort($files);

    $new_updated_start = Subfolio::$filebrowser->get_updated_since_time();
    $list = array();
    foreach ($folders as $folder) {
      $restricted = false;
      $have_access = false;
      $new = false;
      $updated = false;

      if (!Subfolio::$filebrowser->is_feature($folder->name)) {

        $target = "";
        $folder_kind = Subfolio::$filekind->get_kind_by_file($folder->name);

        $kind = isset($folder_kind['kind']) ? $folder_kind['kind'] : '';
        $icon_file = "dir";

        $kind_display = isset($folder_kind['display']) ? $folder_kind['display'] : '';
        $url = "";
        $display = $folder->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions);

	        if ($folder->contains_access_file()) {
	         	$restricted = true;
          	if ($folder->have_access($this->auth->get_user())) {
            	$have_access = true;
          	} else {
            	$have_access = false;
          	}
	        } else {
          	if ($folder->have_access($this->auth->get_user())) {
            	$have_access = true;
          	} else {
            	$have_access = false;
  	         	$restricted = true;
          	}
	        }

					if (!$restricted || $have_access) {
			      if (false && $folder->stats['ctime'] > $new_updated_start) {
	            	$new = true;
	          } else if ($folder->stats['mtime'] > $new_updated_start) {
	        		$updated = true;
	          }
         	}

        if ($kind == "site") {
        	$folder_kind = $this->filekind->get_kind_by_extension("site");
        } else if ($kind == "slide") {
          $folder_kind = $this->filekind->get_kind_by_extension("slide");
        } else {
        	$folder_kind = $this->filekind->get_kind_by_extension("dir");
        }

        $icon_file = $this->filekind->get_icon_by_file($folder_kind);
				$listing_mode = $this->filebrowser->get_folder_property('listing_mode', $listing_mode);

				// to be confirmed
				if (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
					$listing_mode = 'grid';
				}

        $icon_set  = view::get_option('icon_set_list',  "list");
        $icon_set_grid  = view::get_option('icon_set_grid',  "grid");

        $icon = $icon_set."_".$icon_file;
				$icon_grid = $icon_set_grid."_".$icon_file;

    	  switch ($kind) {
    	    /***** NOT LINKING TO SITE, LINK TO PERMALINK PAGE INSTEAD
    			case "site" :
    			  		$url = "/directory".Subfolio::$filebrowser->get_link($folder->name)."/index.html";
    			  		$target = "_blank";
    		        $display = format::filename($folder->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions), false);
    		        break;
          */

          case "slide" :

            $slide_files  = Subfolio::$filebrowser->get_file_list(null, $folder->name."/");
            $slide_files  = Subfolio::$filebrowser->sort($slide_files);

            if (sizeof($slide_files) == 0) {
              $url          = Subfolio::$filebrowser->get_link($file->name);
            } else {
              $url          = Subfolio::$filebrowser->get_link($slide_files[0]->name);
            }
            $display      = format::filename($folder->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions), false);
            break;

    			case "pages" :
      			  	$url = "/directory".Subfolio::$filebrowser->get_link($folder->name);
      			  	break;

    			case "numbers" :
      			  	$url = "/directory".Subfolio::$filebrowser->get_link($folder->name);
      			  	break;

    			case "key" :
    						$url = "/directory".Subfolio::$filebrowser->get_link($folder->name);
    						break;

    			default:
              	$url = "".Subfolio::$filebrowser->get_link($folder->name);
            		break;
    		}

        $item = array();
        $item['target'] = $target;
        $item['url'] = $url;
				$item['icon_name'] = $icon_file;
        $item['icon'] = $icon;
				$item['icon_grid'] = $icon_grid;
        $item['filename'] = $display;
        $item['size'] = "&mdash";
        $item['date'] = format::filedate($folder->stats['mtime']);
        $item['kind'] = $kind_display;
        $item['comment'] = format::get_rendered_text(Subfolio::$filebrowser->get_item_property($folder->name, 'comment'));
        $item['restricted'] = $restricted;
        $item['have_access'] = $have_access;
        $item['new'] = $new;
        $item['updated'] = $updated;
        $list[] = $item;
      }
    }

    foreach ($files as $file) {
      $restricted = false;
      $have_access = false;
      $new = false;
      $updated = false;

      if (!$file->has_thumbnail()) {
          $file_kind = Subfolio::$filekind->get_kind_by_file($file->name);

          if (isset($file_kind['kind'])) {
            $kind = $file_kind['kind'];
          } else {
            $kind = "";
          }

          if ($kind == "img" && !$file->needs_thumbnail()) {
            // don't show listing for image smaller than thumbnail;
            continue;
          }
          $kind_display = isset($file_kind['display']) ? $file_kind['display'] : '';

          $icon_file = "";
          $new = false;
          $updated = false;

          if (false && $file->stats['ctime'] > $new_updated_start) {
              $new = true;
          } else if ($file->stats['mtime'] > $new_updated_start) {
              $updated = true;
          }

          $icon_file = Subfolio::$filekind->get_icon_by_file($file_kind);
    	  	$listing_mode = Subfolio::$filebrowser->get_folder_property('listing_mode', $listing_mode);

       	  // to be confirmed
      	  if (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
      		  $listing_mode = 'grid';
      	  }

          $icon_set  = view::get_option('icon_set_list',  "list");
          $icon_set_grid  = view::get_option('icon_set_grid',  "grid");

          $icon = $icon_set."_".$icon_file;
          $icon_grid = $icon_set_grid."_".$icon_file;

          $target = "";
          $url = "";
          $display = "";

          if (SubfolioTheme::get_mobile_viewport()) {
            if ($kind == "pop") {
              $kind = "link";
            }
          }

    		  switch ($kind) {

      			case "pop" :
    	        $width    = Subfolio::$filebrowser->get_item_property($file->name, 'width')    ? Subfolio::$filebrowser->get_item_property($file->name, 'width') : 800;
    	        $height   = Subfolio::$filebrowser->get_item_property($file->name, 'height')   ? Subfolio::$filebrowser->get_item_property($file->name, 'height') : 600;
    	        $url      = Subfolio::$filebrowser->get_item_property($file->name, 'url')      ? Subfolio::$filebrowser->get_item_property($file->name, 'url') : 'http://www.subfolio.com';
    	        $name     = Subfolio::$filebrowser->get_item_property($file->name, 'name')     ? Subfolio::$filebrowser->get_item_property($file->name, 'name') : 'POPUP';
    	        $style    = Subfolio::$filebrowser->get_item_property($file->name, 'style')    ? Subfolio::$filebrowser->get_item_property($file->name, 'style') : 'POPSCROLL';

    	        $url = "javascript:A17.Helpers.pop('$url','$name',$width,$height,'$style');";
    				  $display = format::filename($file->get_display_name($replace_dash_space, $replace_underscore_space, TRUE), false);
    	        break;

      			case "link" :
    	        $url = Subfolio::$filebrowser->get_item_property($file->name, 'url')    ? Subfolio::$filebrowser->get_item_property($file->name, 'url') : '';
    	        $target = Subfolio::$filebrowser->get_item_property($file->name, 'target')    ? Subfolio::$filebrowser->get_item_property($file->name, 'target') : '_blank';
      			  $display = format::filename($file->get_display_name($replace_dash_space, $replace_underscore_space, TRUE), false);
      			  break;

      			default:
      			  $url = Subfolio::$filebrowser->get_link($file->name);
      			  $display = $file->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions);
              break;
    	    }


        $item = array();
        $item['target'] = $target;
        $item['url'] = $url;
				$item['icon_name'] = $icon_file;
        $item['icon'] = $icon;
				$item['icon_grid'] = $icon_grid;
        $item['filename'] = $display;
        $item['size'] = format::filesize($file->stats['size']);
        $item['date'] = format::filedate($file->stats['mtime']);
        $item['kind'] = $kind_display;
        $item['comment'] = format::get_rendered_text(Subfolio::$filebrowser->get_item_property($file->name, 'comment'));
        $item['restricted'] = $restricted;
        $item['have_access'] = $have_access;
        $item['new'] = $new;
        $item['updated'] = $updated;
        $list[] = $item;

        }

    }

    return $list;
  }

  public function have_files_and_folders()
  {
    return self::have_files();
  }

  public function files_and_folders()
  {
    $hide_locked = view::get_option('hide_locked_folders', false);
    $listing_mode = SubfolioTheme::get_listing_mode();
    $replace_dash_space = view::get_option('replace_dash_space', true);
    $replace_underscore_space = view::get_option('replace_underscore_space', true);
    $display_file_extensions = view::get_option('display_file_extensions', true);

    $folders = Subfolio::$filebrowser->get_folder_list();
    $folders = Subfolio::$filebrowser->sort($folders);
    $files  = Subfolio::$filebrowser->get_file_list();
    $files = array_filter($files, function ($val) {
        return $val->kind != "psd";
    });
    $files  = Subfolio::$filebrowser->sort($files);

    $items = array_merge($folders, $files);
    $items = Subfolio::$filebrowser->sort($items);

    $new_updated_start = Subfolio::$filebrowser->get_updated_since_time();
    $list = array();

    $current_user = $this->auth->get_user();

    foreach ($items as $file) {
      $restricted = false;
      $have_access = false;
      $new = false;
      $updated = false;
      $empty = false;

      if (!Subfolio::$filebrowser->is_feature($file->name)) {
        if (!$file->has_thumbnail()) {

          if ($file->is_file()) {
            $empty = false;
          } else {
            $empty = !((boolean) Subfolio::$filebrowser->file_or_folder_count(true, $file->name));
          }

          $file_kind = Subfolio::$filekind->get_kind_by_file($file->name);

          if (isset($file_kind['kind'])) {
            $kind = $file_kind['kind'];
          } else {
            if(!$file->is_file()) {
              $file_kind = Subfolio::$filekind->get_kind_by_file(".dir");
            }
            $kind = "";
          }

          $include = false;
          if (!$current_user) {
            $include = true;
          }


          if ($file->contains_access_file()) {
            $restricted = true;
            if ($file->have_access($current_user)) {
              $include = true;
              $have_access = true;
            } else {
              $have_access = false;
            }
          } else {
            if ($file->have_access($current_user)) {
              $include = true;
              $have_access = true;
            } else {
              $have_access = false;
              $restricted = true;
            }
          }

          if (!$hide_locked) {
            $include = true;
          }

          if ($include) {
            if ($kind == "img" && !$file->needs_thumbnail()) {
              // don't show listing for image smaller than thumbnail;
              continue;
            }

            $kind_display = isset($file_kind['display']) ? $file_kind['display'] : '';

            $icon_file = "";
            $new = false;
            $updated = false;

            if (false && $file->stats['ctime'] > $new_updated_start) {
                $new = true;
            } else if ($file->stats['mtime'] > $new_updated_start) {
                $updated = true;
            }

            $icon_file = Subfolio::$filekind->get_icon_by_file($file_kind);
      	  	$listing_mode = Subfolio::$filebrowser->get_folder_property('listing_mode', $listing_mode);

            if ($icon_file == "gen" && $file->type == "folder") {
              $icon_file = "dir";
            }

         	  // to be confirmed
        	  if (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
        		  $listing_mode = 'grid';
        	  }

            $icon_set  = view::get_option('icon_set_list',  "list");
            $icon_set_grid  = view::get_option('icon_set_grid',  "grid");

            $icon = $icon_set."_".$icon_file;
            $icon_grid = $icon_set_grid."_".$icon_file;

            $target = "";
            $url = "";
            $display = "";

      		  switch ($kind) {

              case "slide" :

                $slide_files  = Subfolio::$filebrowser->get_file_list(null, $file->name."/");
                $slide_files  = Subfolio::$filebrowser->sort($slide_files);

                if (sizeof($slide_files) == 0) {
                  $url          = Subfolio::$filebrowser->get_link($file->name);
                } else {
                  $url          = Subfolio::$filebrowser->get_link($slide_files[0]->name);
                }
                $display      = format::filename($file->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions), false);
                break;

        			case "pop" :
      	        $width    = Subfolio::$filebrowser->get_item_property($file->name, 'width')    ? Subfolio::$filebrowser->get_item_property($file->name, 'width') : 800;
      	        $height   = Subfolio::$filebrowser->get_item_property($file->name, 'height')   ? Subfolio::$filebrowser->get_item_property($file->name, 'height') : 600;
      	        $url      = Subfolio::$filebrowser->get_item_property($file->name, 'url')      ? Subfolio::$filebrowser->get_item_property($file->name, 'url') : 'http://www.subfolio.com';
      	        $name     = Subfolio::$filebrowser->get_item_property($file->name, 'name')     ? Subfolio::$filebrowser->get_item_property($file->name, 'name') : 'POPUP';
      	        $style    = Subfolio::$filebrowser->get_item_property($file->name, 'style')    ? Subfolio::$filebrowser->get_item_property($file->name, 'style') : 'POPSCROLL';

      	        $url = "javascript:A17.Helpers.pop('$url','$name',$width,$height,'$style');";
      				  $display = format::filename($file->get_display_name($replace_dash_space, $replace_underscore_space, TRUE), false);
      	        break;

        			case "link" :
        	        $url = Subfolio::$filebrowser->get_item_property($file->name, 'url')    ? Subfolio::$filebrowser->get_item_property($file->name, 'url') : '';
        	        $target = Subfolio::$filebrowser->get_item_property($file->name, 'target')    ? Subfolio::$filebrowser->get_item_property($file->name, 'target') : '_blank';
          			  $display = format::filename($file->get_display_name($replace_dash_space, $replace_underscore_space, TRUE), false);

                  if ($url == '') {
                    $url= "http://".substr($file->name, 0, strrpos($file->name, "."));
                  }

          			  break;


          			default:
          			  $url = Subfolio::$filebrowser->get_link($file->name);
          			  $display = $file->get_display_name($replace_dash_space, $replace_underscore_space, $display_file_extensions);
                  break;
      	    }

            $item = array();
            $item['empty'] = $empty;
            $item['target'] = $target;
            $item['url'] = $url;
            $item['icon'] = $icon;
    				$item['icon_grid'] = $icon_grid;
            $item['filename'] = $display;
            $item['size'] = format::filesize($file->stats['size']);
            $item['date'] = format::filedate($file->stats['mtime']);
            $item['kind'] = $kind_display;
            $item['comment'] = format::get_rendered_text(Subfolio::$filebrowser->get_item_property($file->name, 'comment'));
            $item['restricted'] = $restricted;
            $item['have_access'] = $have_access;
            $item['new'] = $new;
            $item['updated'] = $updated;
            $list[] = $item;
          }
        }
      }
    }

    return $list;
  }

  public function have_related()
  {
      $list = Subfolio::$filebrowser->get_file_list("cut", null, true);
      return (sizeof($list) > 0);
  }

  public function related()
  {
    $related = array();

    $listing_mode = Kohana::config('filebrowser.listing_mode');
    $listing_mode = view::get_option('listing_mode', $listing_mode);

    $list = Subfolio::$filebrowser->get_file_list("cut", null, true);

    $restricted = false;
    foreach ($list as $item) {
      $link = Subfolio::$filebrowser->get_item_property($item->name, 'url');
      if ($link == "") {
        $link = Subfolio::$filebrowser->get_item_property($item->name, 'directory');

        $parent = Subfolio::$filebrowser->get_folder();
        $folder_name = $link;

        if (substr($folder_name, 0, 1)  == "/") {
          $pos = strrpos($link, "/");
          $parent = substr($link, 1, strlen($link) - ($pos-1));
          $folder_name = substr($link, $pos+1, strlen($link) - ($pos-1));
        }

        $folder = new FileFolder($folder_name, $parent, 'folder', '', NULL);
        if ($folder->contains_access_file()) {
         	$restricted = true;
        	if ($folder->have_access($this->auth->get_user())) {
          	$have_access = true;
        	} else {
          	$have_access = false;
        	}
        } else {
        	if ($folder->have_access($this->auth->get_user())) {
           	$restricted = false;
          	$have_access = true;
        	} else {
          	$have_access = false;
	         	$restricted = true;
        	}
        }
      }
      $name = Subfolio::$filebrowser->get_item_property($item->name, 'name');
      $file_kind = Subfolio::$filekind->get_kind_by_file($item->name);

      $icon_file = "";
      $icon_file = Subfolio::$filekind->get_icon_by_file($file_kind);

      $icon_set  = view::get_option('icon_set_list',  "list");
      $icon_set_grid  = view::get_option('icon_set_grid',  "grid");

      $icon = $icon_set."_".$icon_file;
      $icon_grid = $icon_set_grid."_".$icon_file;

      $rel = array();
      $rel['link'] = $link;
      $rel['filename'] = $name;

      $rel['icon'] = $icon;
			$rel['icon_grid'] = $icon_grid;

      if ($restricted) {
        $rel['restricted'] = true;
        $rel['have_access'] = $have_access;
      } else {
        $rel['restricted'] = false;
      }

      $related[] = $rel;
    }

    return $related;
  }

  public function is_root() {
    $ff = Subfolio::$filebrowser->get_path();
    return ($ff == "");
  }

  public function parent_link($name) {
    $ff = Subfolio::$filebrowser->get_path();
    if ($ff <> '') {
    	$parent_link = urlencode(dirname($ff));
      $parent_link = str_replace('%2F', '/', $parent_link);
      // ## HACK FOR SLIDE
      if (substr($parent_link, -6) == ".slide") {
        $parent_link = substr($parent_link, 0, stripos($parent_link, '/'));
      }
      return html::anchor($parent_link, $name, array('id' => 'parent'));
    }
    return NULL;
  }

  public function previous_link_or_span($name, $directory_name, $link_id, $class) {
    $ff = Subfolio::$filebrowser->get_path();
    if ($ff <> '') {
  		if(Subfolio::$filebrowser->is_file()) {
  	    $file = Subfolio::$filebrowser->get_file();

        $items = Subfolio::$filebrowser->get_parent_file_folder_list(null, false);
        $items = Subfolio::$filebrowser->sort($items);
        $items = Subfolio::$filebrowser->prev_next_sort($items);

  			$prev = Subfolio::$filebrowser->get_prev($items, $file->name);
  			if ($prev <> "") {
        	$link = Filebrowser::double_encode_specialcharacters(urlencode($prev->name));
          $link = str_replace('%2F', '/', $link);
  				return "<a id='$link_id' href='$link'>$name</a>";
  			} else {
  				return "<span id='$link_id' class='".$class."'>".$name."</span>";
  			}
  	  } else {
  	    $folder  = basename(Subfolio::$filebrowser->get_folder());

        $items = Subfolio::$filebrowser->get_parent_file_folder_list(null, true);
        $items = Subfolio::$filebrowser->sort($items);
        $items = Subfolio::$filebrowser->prev_next_sort($items);
  			$prev    = Subfolio::$filebrowser->get_prev($items, $folder);

  			if ($prev <> "") {
        	$link = Filebrowser::double_encode_specialcharacters(urlencode($prev->name));
          $link = str_replace('%2F', '/', $link);

  				return "<a id='$link_id' href='$link'>$directory_name</a>";
  			} else {
  				return "<span id='$link_id' class='".$class."'>".$directory_name."</span>";
  			}
  	  }
    }
  }

  public function next_link_or_span($name, $directory_name, $link_id, $class) {
    $ff = Subfolio::$filebrowser->get_path();
    if ($ff <> '') {
  		if(Subfolio::$filebrowser->is_file()) {
  	    $file = Subfolio::$filebrowser->get_file();

        $items = Subfolio::$filebrowser->get_parent_file_folder_list(null, false);
        $items = Subfolio::$filebrowser->sort($items);
        $items = Subfolio::$filebrowser->prev_next_sort($items);

  			$next = Subfolio::$filebrowser->get_next($items, $file->name);
  			if ($next <> "") {
        	$link = Filebrowser::double_encode_specialcharacters(urlencode($next->name));
          $link = str_replace('%2F', '/', $link);
  				return "<a id='$link_id' href='$link'>$name</a>";
  			} else {
  				return "<span id='$link_id' class='".$class."'>".$name."</span>";
  			}
  	  } else {
  	    $folder  = basename(Subfolio::$filebrowser->get_folder());

        $items = Subfolio::$filebrowser->get_parent_file_folder_list(null, true);
        $items = Subfolio::$filebrowser->sort($items);
        $items = Subfolio::$filebrowser->prev_next_sort($items);
  			$next = Subfolio::$filebrowser->get_next($items, $folder);

  			if ($next <> "") {
        	$link = Filebrowser::double_encode_specialcharacters(urlencode($next->name));
          $link = str_replace('%2F', '/', $link);

  				return "<a id='$link_id' href='$link'>$directory_name</a>";
  			} else {
  				return "<span id='$link_id' class='".$class."'>".$directory_name."</span>";
  			}
  	  }
	  }
  }

  public function updated_since_link_or_span($type)
  {
    $ls = "";
    $updated_since = Subfolio::$filebrowser->get_updated_since();

    if ($type == "lastweek") {
      if ($updated_since == "lastweek") {
        $ls = "<span>".SubfolioLanguage::get_text('last_week')."</span>";
      } else {
        $ls = "<a href=\"?updated_since=lastweek\">".SubfolioLanguage::get_text('last_week')."</a>";
      }
    }

    if ($type == "lastmonth") {
      if ($updated_since == "lastmonth") {
        $ls = "<span>".SubfolioLanguage::get_text('last_month')."</span>";
      } else {
        $ls = "<a href=\"?updated_since=lastmonth\">".SubfolioLanguage::get_text('last_month')."</a>";
      }
    }

    if ($type == "lastvisit") {
      if ($updated_since == "lastvisit") {
        $ls = "<span>".SubfolioLanguage::get_text('my_last_visit')."</span>";
      } else {
        $ls = "<a href=\"?updated_since=lastvisit\">".SubfolioLanguage::get_text('my_last_visit')."</a>";
      }
    }

    return $ls;
  }

  public static function fetch_rss($url, $quantity=10, $cache_name=NULL, $cache=3600) {
    $items = array();
    $cache_file_name = NULL;
    $cache_stats = NULL;
    $use_cache = TRUE;

    if ($cache_name != NULL && $cache != NULL) {
      $cache_file_name = "-".$cache_name.".cache";

      if (file_exists($cache_file_name)) {
        $cache_stats = stat($cache_file_name);
        if (mktime() > ($cache + $cache_stats['mtime'])) {
          $use_cache = FALSE;
        } else {
        }
      } else {
        $use_cache = FALSE;
      }
    } else {
      $use_cache = FALSE;
    }

    if ($use_cache == FALSE) {
      $items = feed::parse($url, $quantity);
      $sitems = array();
      if ($cache_file_name != NULL)  {

        // can't cache simplexml directly must be converted to string
        foreach($items as $array) {
          $narray = array();
          foreach ($array as $key => $value) {
            $narray[$key] = (string) $value;
          }
          $sitems[] = $narray;
        }

        file_put_contents($cache_file_name,  serialize($sitems));
      }
    } else {
      $data = file_get_contents($cache_file_name);
      $items = unserialize($data);
    }

    return $items;
  }

}
?>
