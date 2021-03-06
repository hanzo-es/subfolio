<?php if (SubfolioFiles::have_related()) : ?>

  <div id="related">
    <p><?php echo SubfolioLanguage::get_text('seealso') ?></p>
    <div class="list list--<?php echo SubfolioTheme::get_listing_mode() ?>" data-behavior="hover_list">

      <?php foreach ( SubfolioFiles::related() as $item) : ?>
        <a class="list__row" href='<?php echo $item['link'] ?>'>
          <span class="list__cell list__cell--filename">
            <?php if (SubfolioTheme::get_option('display_icons')) { ?>
              <span class="list__cell--filenameicon">
                <?php $type = SubfolioTheme::get_listing_mode()=='grid' ? $item['icon_grid'] : $item['icon']; ?>
                <?php if ($item['restricted']) { ?>
                  <i class="icon icon__<?php echo $type ?>_<?php if ($item['have_access']) { echo "shared"; } else { echo "protected"; } ?>"></i>
                <?php } else { ?>
                  <i class='icon icon__<?php echo $type ?>'></i>
                <?php } ?>
              </span>
            <?php } ?>
            <span class="list__cell--filenametext"><?php echo $item['filename'] ?></span>
          </span>
        </a>
      <?php endforeach; ?>

    </div>
  </div>

<?php endif ?>