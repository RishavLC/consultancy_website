<?php
require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
$type=$_GET['type']??'services'; $allowed=['services','projects','gallery','team','testimonials']; if(!in_array($type,$allowed,true)) exit('Invalid section');
$cfg=[
'services'=>['table'=>'services','title'=>'Services','fields'=>['slug'=>'Slug','icon'=>'Icon','title'=>'Title','short_text'=>'Short text','description'=>'Description','features'=>'Features (one per line)','sort_order'=>'Order','is_active'=>'Active']],
'projects'=>['table'=>'projects','title'=>'Projects','fields'=>['category'=>'Category','year'=>'Year','title'=>'Title','location'=>'Location','summary'=>'Summary','detail'=>'Detail','scope'=>'Scope (one per line)','stats'=>'Stats (Label=Value, one per line)','image'=>'Image filename','sort_order'=>'Order','is_active'=>'Active']],
'gallery'=>['table'=>'gallery','title'=>'Gallery','fields'=>['category'=>'Category','title'=>'Title','image'=>'Image filename','sort_order'=>'Order','is_active'=>'Active']],
'team'=>['table'=>'team_members','title'=>'Team Members','fields'=>['name'=>'Name','role'=>'Role','bio'=>'Bio','image'=>'Image filename','sort_order'=>'Order','is_active'=>'Active']],
'testimonials'=>['table'=>'testimonials','title'=>'Testimonials','fields'=>['name'=>'Name','project'=>'Project','quote'=>'Quote','sort_order'=>'Order','is_active'=>'Active']],]; $c=$cfg[$type]; $table=$c['table']; $message='';
function clean_data($type,$post){
    $d=[];
    foreach($post as $k=>$v){ if($k==='id'||$k==='save') continue; $d[$k]=is_string($v)?trim($v):$v; }
    if($type==='services') {
        $lines=preg_split('/\r?\n/',$d['features']??'');
        $d['features']=json_encode(array_values(array_filter(array_map('trim',$lines))),JSON_UNESCAPED_UNICODE);
    }
    if($type==='projects') {
        $lines=preg_split('/\r?\n/',$d['scope']??'');
        $d['scope']=json_encode(array_values(array_filter(array_map('trim',$lines))),JSON_UNESCAPED_UNICODE);
        $stats=[];
        foreach(preg_split('/\r?\n/',$d['stats']??'') as $line){
            if(strpos($line,'=')!==false){ [$k,$v]=array_map('trim',explode('=',$line,2)); if($k!=='') $stats[$k]=$v; }
        }
        $d['stats']=json_encode($stats,JSON_UNESCAPED_UNICODE);
    }
    foreach(['sort_order','year','is_active'] as $n) if(isset($d[$n])) $d[$n]=(int)$d[$n];
    if(isset($d['is_active'])===false && in_array($type,['services','projects','gallery','team','testimonials'],true)) $d['is_active']=0;
    return $d;
}
if(isset($_GET['delete'])){$id=(int)$_GET['delete'];$pdo->prepare("DELETE FROM `$table` WHERE id=?")->execute([$id]);header('Location: content.php?type='.urlencode($type));exit;}
$editing=null;if(isset($_GET['edit'])){$st=$pdo->prepare("SELECT * FROM `$table` WHERE id=?");$st->execute([(int)$_GET['edit']]);$editing=$st->fetch();}
if($_SERVER['REQUEST_METHOD']==='POST'){$d=clean_data($type,$_POST);$id=(int)($_POST['id']??0);if($id){$sets=[];$vals=[];foreach($d as $k=>$v){$sets[]="`$k`=?";$vals[]=$v;}$vals[]=$id;$pdo->prepare("UPDATE `$table` SET ".implode(',',$sets)." WHERE id=?")->execute($vals);}else{$cols=array_keys($d);$pdo->prepare("INSERT INTO `$table` (`".implode('`,`',$cols)."`) VALUES (".implode(',',array_fill(0,count($cols),'?')).")")->execute(array_values($d));}$message='Saved successfully.';}
$items=$pdo->query("SELECT * FROM `$table` ORDER BY sort_order,id DESC")->fetchAll();
function form_value($editing,$k,$type){$v=$editing[$k]??'';if($type==='services'&&$k==='features')$v=implode("\n",json_decode($v?:'[]',true)?:[]);if($type==='projects'&&$k==='scope')$v=implode("\n",json_decode($v?:'[]',true)?:[]);if($type==='projects'&&$k==='stats'){ $a=json_decode($v?:'{}',true)?:[];$v='';foreach($a as $x=>$y)$v.=$x.'='.$y."\n";}return $v;}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?php echo $c['title'];?></title><style>body{font-family:Arial;background:#f5f1ea;margin:0;color:#2d211c}.top{background:#2d211c;color:#fff;padding:18px 5%}.wrap{max-width:1200px;margin:25px auto;padding:0 20px}.form,.item{background:#fff;padding:20px;margin-bottom:18px;border-radius:8px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px}label{display:block;font-weight:bold;margin:10px 0 5px}input,textarea{width:100%;box-sizing:border-box;padding:10px;border:1px solid #ccc;border-radius:4px}textarea{min-height:110px}button,.btn{background:#7d402d;color:#fff;border:0;padding:10px 14px;border-radius:5px;text-decoration:none;display:inline-block;margin-top:12px}.muted{color:#777}.danger{background:#a22}.active{width:auto}.actions a{margin-right:8px}.list{margin-top:25px}.check{width:auto}</style></head><body><div class="top"><b>Strata &amp; Beam Admin — <?php echo htmlspecialchars($c['title']);?></b> · <a style="color:#fff" href="index.php">Dashboard</a></div><div class="wrap"><a href="index.php">← Dashboard</a><h1><?php echo $c['title'];?></h1><?php if($message):?><p style="color:green"><?php echo $message;?></p><?php endif;?><form class="form" method="post"><input type="hidden" name="id" value="<?php echo (int)($editing['id']??0);?>"><div class="grid"><?php foreach($c['fields'] as $k=>$label):?><div><?php if($k==='is_active'):?><label><input class="check" type="checkbox" name="is_active" value="1" <?php echo (!isset($editing)||!empty($editing['is_active']))?'checked':'';?>> Active</label><?php else:?><label><?php echo $label;?></label><?php if(in_array($k,['description','short_text','bio','quote','summary','detail','features','scope','stats'],true)):?><textarea name="<?php echo $k;?>"><?php echo htmlspecialchars(form_value($editing,$k,$type));?></textarea><?php else:?><input name="<?php echo $k;?>" value="<?php echo htmlspecialchars(form_value($editing,$k,$type));?>" <?php echo in_array($k,['sort_order','year'])?'type="number"':'';?> required><?php endif;?><?php endif;?></div><?php endforeach;?></div><button name="save">Save</button> <?php if($editing):?><a class="btn" href="content.php?type=<?php echo $type;?>">Cancel</a><?php endif;?></form><div class="list"><?php foreach($items as $it):?><div class="item"><b><?php echo htmlspecialchars($it['title']??$it['name']??'Item #'.$it['id']);?></b><div class="muted">ID <?php echo $it['id'];?></div><div class="actions"><a class="btn" href="?type=<?php echo $type;?>&edit=<?php echo $it['id'];?>">Edit</a><a class="btn danger" onclick="return confirm('Delete this item?')" href="?type=<?php echo $type;?>&delete=<?php echo $it['id'];?>">Delete</a></div></div><?php endforeach;?></div></div></body></html>
