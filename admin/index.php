<?php require_once __DIR__.'/auth.php'; require_admin(); $pdo=db(); $counts=[]; foreach(['services','projects','gallery','team_members','testimonials','enquiries'] as $t) $counts[$t]=(int)$pdo->query("SELECT
COUNT(*) FROM `$t`")->fetchColumn(); $new=(int)$pdo->query("SELECT COUNT(*) FROM
enquiries WHERE status='new'")->fetchColumn(); ?> <!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin Dashboard</title>
    <style>
      body {
        font-family: Arial;
        background: #f5f1ea;
        margin: 0;
        color: #2d211c;
      }
      .top {
        background: #2d211c;
        color: #fff;
        padding: 18px 5%;
        display: flex;
        justify-content: space-between;
      }
      .wrap {
        max-width: 1100px;
        margin: 35px auto;
        padding: 0 20px;
      }
      .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
      }
      .card {
        background: #fff;
        padding: 22px;
        border-radius: 8px;
      }
      .card b {
        font-size: 34px;
      }
      .links a {
        display: block;
        padding: 13px;
        background: #fff;
        margin: 8px 0;
        border-radius: 6px;
        color: #6d3828;
        text-decoration: none;
      }
    </style>
  </head>
  <body>
    <div class="top">
      <b>Strata &amp; Beam Admin</b
      ><span
        ><?php echo htmlspecialchars($_SESSION['admin_username']);?>
        · <a style="color: #fff" href="logout.php">Logout</a></span
      >
    </div>
    <div class="wrap">
      <h1>Dashboard</h1>
      <div class="grid">
        <?php foreach($counts as $k=>$v):?>
        <div class="card">
          <b><?php echo $v;?></b>
          <div><?php echo ucwords(str_replace('_',' ',$k));?></div>
        </div>
        <?php endforeach;?>
      </div>
      <h2>Manage Content</h2>
      <div class="links">
        <a href="content.php?type=services">Services</a
        ><a href="content.php?type=projects">Projects</a
        ><a href="content.php?type=gallery">Gallery</a
        ><a href="content.php?type=team">Team Members</a
        ><a href="content.php?type=testimonials">Testimonials</a
        ><a href="enquiries.php">Enquiries (<?php echo $new;?> new)</a
        ><a href="settings.php">Website Settings</a>
      </div>
      <p><a href="../index.php">← View website</a></p>
    </div>
  </body>
</html>
