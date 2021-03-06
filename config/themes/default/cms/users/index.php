<div class="standard_paragraph">
  <h2>Users List</h2>
  <?php if ($users) { ?>
  <table width="100%;">
  <thead>
    <th>Name</th>
    <th>Full Name</th>
    <th>Admin?</th>
    <th>Action</th>
  </thead>
  <?php
    foreach ($users as $name => $user) { ?>
    <tr>
      <td>
        <a href="<?php print SubfolioTheme::get_site_root(); ?>-cms/users/edit/<?php print $name; ?>"><?php print $name; ?></a>
      </td>
      <td>
        <?php if (isset($user['fullname'])) print $user['fullname'] ?>
      </td>
      <td>
        <?php if (isset($user['admin']) && $user['admin']) print "Yes" ?>
      </td>
      <td>
        <a href="<?php print SubfolioTheme::get_site_root(); ?>-cms/users/edit/<?php print $name; ?>">edit</a> &bull;
        <a onclick="javascript:return confirm('Are you sure?');" href="<?php print SubfolioTheme::get_site_root(); ?>-cms/users/delete/<?php print $name; ?>">delete</a>
      </td>
    </li>
    <?php }
  ?>
  </table>
  <?php } else { ?>
    <p>No users?  How is that possible?!?</p>
  <?php }  ?>
</div>
<p><a href="<?php print SubfolioTheme::get_site_root(); ?>-cms/users/add">Add new user</a></p>
