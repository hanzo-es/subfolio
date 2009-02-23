<?php 
$inline = $this->filebrowser->get_file_list("txt", "-t-intro", true);
if (sizeof($inline) > 0) { 
  $this->filebrowser->set_displayed_content(true);
  foreach($inline as $file) {
    ?>
    <div id="top_inline_text" class="clearfix">
    <?php
      readfile($file->name);
    ?>
    </div>
    <?php
  }
} ?>

<?php if ($this->filebrowser->get_folder_property('text-intro') <> '') { 
  $this->filebrowser->set_displayed_content(true);
?>
<div class="top_inline_text">
  <p><?php echo $this->filebrowser->get_folder_property('text-intro'); ?></p>
</div>
<?php } ?>

<?php $features = $this->filebrowser->get_folder_property('features');
if (sizeof($features) > 0) { ?>
<div id="features">
  <ul>
  <?php foreach ($features as $feature) { ?>
    <li>
      <div class="image">
        <a href="<?php echo $feature['link'] ?>"><img src="/directory/<?php echo $this->filebrowser->get_folder() ?>/<?php echo $feature['image'] ?>"></a>
      </div>
      <div class="info">
        <a href="<?php echo $feature['link'] ?>"><h2><?php echo $feature['title'] ?></h2></a>
        <p><?php echo $feature['description'] ?></p>
      </div>
      <div class="clear"></div>
    </li>
  <?php } ?>
  </ul>
  <div class="clear"></div>
</div>
<?php } ?>