<!DOCTYPE html>
<html lang="ja">
<head>
     <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
    <body>
     <?php
   //データベースの設置
       $dsn = 'mysql:dbname=tb240812db;host=localhost';
       $user = 'tb-240812';
       $password = '9d7UeZgeBa';
       $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //データベースのテーブルを作る 
       $sql = "CREATE TABLE IF NOT EXISTS `5-1`"
       ." ("
       . "id INT AUTO_INCREMENT PRIMARY KEY,"
       . "name char(32),"
       . "str TEXT,"
       ."date TEXT,"
       ."pass TEXT"
       .");";
       $stmt =$pdo->query($sql);
      
     
       
   //フォームが空じゃないかつ編集番号が無いなら新規書き込み、編集番号があるなら編集
              
            if(!empty($_POST["str"]) && !empty($_POST["name"]) && !empty($_POST["pass"])){ 
                  
                //編集フォームに書きこみがなかった場合(新規書き込み機能)
                if(empty($_POST["editNO2"])){
                    $name=$_POST['name'];
                    $str=$_POST['str'];
                    $date=date("Y/m/d H:i:s");
                    $pass=$_POST["pass"];
                    
                    $sql = $pdo -> prepare("INSERT INTO `5-1` (name, str, date, pass) VALUES (:name, :str,:date,:pass)");//枠の準備
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':str', $str,   PDO::PARAM_STR);
                    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $sql -> execute();//実行
                }
                  
            }
            
            //編集フォームに書きこみがあった場合、その番号の書き込みをeditname,editstrとして変換
            if(!empty($_POST["editNO"]) &&!empty($_POST["pass3"])){
                    $editNO=$_POST["editNO"];
                    $pass3=$_POST["pass3"];
                    //編集フォームの番号と、データベースに書きこまれた数字が一致する時、SERECTで選択
                    $sql='SELECT * FROM `5-1`';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($row['id']==$editNO && $row['pass']==$pass3){
                          $editNO=$row['id'];
                          $editname=$row['name'];
                          $editstr=$row['str'];
                           
                        }
                    }
            }
            //編集したい内容が返ってきて、editNO2に数字がある時
            elseif(!empty($_POST["editNO2"])){
                if(!empty($_POST["str"]) && !empty($_POST["name"]) && !empty($_POST["pass"]) ){ 
                          
                    $editNO2=$_POST["editNO2"];
                    $name=$_POST['name'];
                    $str=$_POST['str'];
                    $pass=$_POST["pass"];
                    $date=date("Y/m/d H:i:s");
                         
                    $sql = 'UPDATE `5-1` SET id=:id, name=:name,str=:str, date=:date, pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt -> bindParam(':id', $editNO2, PDO::PARAM_INT); 
                    $stmt -> bindParam('name', $name, PDO::PARAM_STR);   
                    $stmt -> bindParam(':str', $str, PDO::PARAM_STR); 
                    $stmt -> bindParam(':date', $date, PDO::PARAM_STR); 
                    $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->execute();
                              
                          
                        
                }    
                          
                     }
                    
            //削除機能
            if(!empty($_POST["num"]) && !empty($_POST["pass2"])) {
                $num=$_POST["num"];
                $pass2=$_POST["pass2"];
                //パスワードが一致する時のみ、DELETEの命令文を送る
                $sql='DELETE from `5-1` WHERE pass=:pass AND id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':id', $num, PDO::PARAM_INT); 
                $stmt -> bindParam(':pass', $pass2, PDO::PARAM_STR);         
                $stmt->execute();
            }
     
       

    //テーブル表示            
    $sql = 'SELECT * FROM `5-1`';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['str'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }         
        
       ?>
        <form action="" method="post" >　
        <table>
        <input type="text" name="name"value="<?php if(isset($editname)) {echo $editname;} ?>"placeholder="名前">
        <input type="text" name="str" value="<?php if(isset($editstr)) {echo $editstr;} ?>"placeholder="コメント">　<!-- value･･･初期表示の文字を指定 -->
        <input type="text" name="pass" placeholder="パスワード">
        <input type="hidden" name="editNO2" value="<?php if(isset($editNO)) {echo $editNO;} ?>">
        <input type="submit" name="submit">
        </table>
       </form>
       
       <form action="" method="post" >
        <input type="number" name="num" >
        <input type="text" name="pass2" placeholder="パスワード" >
        <input type="submit" name="submit" value="削除">
        
         </form>
          <form action="" method="post" >
        <input type="number" name="editNO" >
         <input type="text" name="pass3" placeholder="パスワード" >
        <input type="submit" name="submit" value="編集">
       
         </form>
         
         <?php
      
         
         ?>
    </body>
</html>