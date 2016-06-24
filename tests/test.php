<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/15
 * Time: 下午9:47
 */


$db = new PDO('mysql:host=localhost;dbname=yforum', 'root', '');

for($i=23;$i<=53;$i++)
{
    $postNum=$db->query("select count(*) from post where user_id=$i")->fetch();
    $commentNum=$db->query("select count(*) from post_comment where user_id=$i")->fetch();
    $pre = $db->prepare('insert into user_info(user_id,post_count,comment_count,
                created_at,updated_at,last_login_time) VALUES (:user_id,:post_count,:comment_count
                ,:created_at,:updated_at,:last_login_time)');
    $time=time()-rand(360000,3600000);
    $pre->execute([':user_id'=>$i,':post_count'=>$postNum[0],':comment_count'=>$commentNum[0],
        ':created_at'=>$time,':updated_at'=>$time,'last_login_time'=>$time]);
}
//foreach ($metas as $t)
//{
//    $pre = $db->prepare('insert into post_meta(name, parent, create_at, update_at)
//    VALUES (:name, :parent, :created_at, :updated_at)');
//    $pre->execute([':name'=>$t,':parent'=>6, 'created_at'=>time(), ':updated_at'=>time()]);
//}
//for ($i = 23; $i < 54; $i++) {
//    $username=uniqid();
//    $auth_key='YdUb38mjmJcdT7P_yiC1gWFLg_H61wG5';
//    $password_hash='$2y$13$cQ45dkj2GFCixnf5pOiItucEJsLoYsU5kYmQdI4xLA3Rf/BW5RpLe';
//    $email=$username.'@163.com';
//    $status=10;
//    $created_at=time();
//    $updated_at=$created_at;
//    $role=10;
//
//    $pre=$db->prepare('insert into user(username, auth_key, password_hash, email, status, created_at, updated_at, role)
//          VALUES (:username,:auth_key,:password_hash,:email,:status,:created_at,:updated_at,:role)');
//    $pre->execute([':username'=>$username,':auth_key'=>$auth_key,':password_hash'=>$password_hash,':email'=>$email,
//        ':status'=>$status,':created_at'=>$created_at,':updated_at'=>$updated_at,':role'=>$role]);
//
//    $pre = $db->prepare('update user set username=:username where id=:id');
//    $pre->execute([':username'=>$names[$i-23],':id'=>$i]);
//}

$db = null;

