<?php require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
$fields=[
 'name'=>['Full company name','text','Shown in the page title and footer, e.g. Strata & Beam Engineering.'],
 'shortName'=>['Short name (logo text)','text','Shown beside the logo in the header.'],
 'phone'=>['Phone number','text','Shown on the Contact page and footer.'],
 'email'=>['Email address','email','Shown on the site AND where new enquiry notifications are sent.'],
 'address'=>['Office address','text','Shown on the Contact page and used for the map.'],
 'mapQuery'=>['Map search text (optional)','text','What the map looks up. Leave empty to use the office address above.'],
 'founded'=>['Year founded','number','Used in the About page and footer, e.g. 2008.'],
 'welcome'=>['Welcome message (homepage)','text','Short welcome heading on the homepage, e.g. Welcome to Strata & Beam Engineering.'],
 'intro'=>['Company introduction (homepage)','textarea','2–3 sentences introducing the company. Shown on the homepage.'],
 'mission'=>['Mission (About page)','textarea','One or two sentences: what the company sets out to do.'],
 'vision'=>['Vision (About page)','textarea','One or two sentences: where the company wants to be.'],
 'facebook'=>['Facebook page link (optional)','url','Full link, e.g. https://facebook.com/yourpage. Empty = icon hidden.'],
 'instagram'=>['Instagram link (optional)','url','Full link. Empty = icon hidden.'],
 'linkedin'=>['LinkedIn link (optional)','url','Full link. Empty = icon hidden.'],
];
$error=''; $message='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!csrf_check()){ $error='Your session expired — please try saving again.'; }
  else{
    $vals=[]; foreach($fields as $k=>$f){ $vals[$k]=trim((string)($_POST[$k]??'')); }
    foreach(['welcome'=>200,'intro'=>1200,'mission'=>800,'vision'=>800] as $lk=>$lm){ if(mb_strlen($vals[$lk])>$lm) $error='"'.$fields[$lk][0].'" is too long (maximum '.$lm.' characters).'; }
    if($error){}
    elseif($vals['name']===''||$vals['shortName']==='') $error='Company name and short name cannot be empty.';
    elseif($vals['email']!==''&&!filter_var($vals['email'],FILTER_VALIDATE_EMAIL)) $error='Please enter a valid email address.';
    elseif(!ctype_digit($vals['founded'])||(int)$vals['founded']<1900||(int)$vals['founded']>(int)date('Y')) $error='Please enter a valid year founded (e.g. 2008).';
    else{
      foreach(['facebook','instagram','linkedin'] as $u){ if($vals[$u]!==''&&!preg_match('#^https?://#i',$vals[$u])) $error='Social links must start with http:// or https://'; }
      if(!$error){
        $st=$pdo->prepare('INSERT INTO site_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
        foreach($vals as $k=>$v)$st->execute([$k,$v]);
        $message='Settings saved.';
      }
    }
  }
}
$settings=[];foreach($pdo->query('SELECT setting_key,setting_value FROM site_settings') as $r)$settings[$r['setting_key']]=$r['setting_value'];
if($error&&$_SERVER['REQUEST_METHOD']==='POST'){ foreach($fields as $k=>$f) if(isset($_POST[$k])) $settings[$k]=trim((string)$_POST[$k]); }
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Website Settings — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body><div class="top"><b>Admin — Settings</b><a href="dashboard.php">Dashboard</a></div><div class="wrap"><div class="box"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Website Settings</h1>
<?php if($error):?><p class="err" role="alert"><?php echo htmlspecialchars($error);?></p><?php endif;?><?php if($message)echo '<p class="ok">'.htmlspecialchars($message).'</p>';?>
<form method="post"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>">
<?php foreach($fields as $k=>$f):?><label for="f_<?php echo $k;?>"><?php echo htmlspecialchars($f[0]);?></label><?php if($f[1]==='textarea'):?><textarea id="f_<?php echo $k;?>" name="<?php echo $k;?>" rows="4"><?php echo htmlspecialchars($settings[$k]??'');?></textarea><?php else:?><input id="f_<?php echo $k;?>" type="<?php echo $f[1];?>" name="<?php echo $k;?>" value="<?php echo htmlspecialchars($settings[$k]??'');?>"><?php endif;?><div class="hint"><?php echo htmlspecialchars($f[2]);?></div><?php endforeach;?>
<button>Save Settings</button></form></div></div></body></html>
