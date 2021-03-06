<!DOCTYPE html
	PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />


	<link rel="stylesheet" type="text/css" href="css/employees.css" />
	<link rel="stylesheet" type="text/css" href="css/main.css"/>

	<title>Staff Appraisals Report</title>
	

<style type="text/css">
<!--
.style4 {color: #FFFFFF}
.info   { color: black; background-color: transparent; font-weight: normal; }
  .warn   { color: rgb(120,0,0); background-color: transparent; font-weight: normal; }
  .error  { color: red; background-color: transparent; font-weight: bold }
-->
</style>
 <link rel="stylesheet" type="text/css" href="css/epoch_styles.css"/>
 <script type="text/javascript" src="css/epoch_classes.js"></script>
 <script type="text/javascript" src="js/formval.js"></script> 
 <script language="JavaScript" src="js/calendar1.js"></script><!--  -->
 <script type="text/Javascript">

/*You can also place this code in a separate file and link to it like epoch_classes.js*/
	var dp1_cal,dp2_cal
     window.onload = function () {
	
	dp1_cal  = new Epoch('epoch_popup','popup',document.getElementById('datefrom'));
	dp2_cal = new Epoch('epoch_popup','popup',document.getElementById('dateto'));
};

 function validateOnSubmit() {
	var elem;
    var errs=0;
	// execute all element validations in reverse order, so focus gets
    // set to the first one in error.
	
	if (!validatePresent(document.forms.addbankfrm.bank,'inf_bank'))  errs += 1; 
		    
	if (errs>1)  alert('There are fields which need correction before submitting');
    if (errs==1) alert('There is a field which needs correction before submitting');
	
  return (errs==0);
 }
 
 </script>
 
 <style type="text/css">
<!--
.style2 {font-size: x-small}
-->
 </style>
</head>
<body bgcolor="#FFFFFF">
<? 
  include "includes/functions.php";
  include "includes/config.php";
  require_once "includes/db.php";
  
  $d = new dbC();
  $d->connect($db_host, $db_user, $db_pass, $db); 
  
   if (!empty($_POST["datefrom"]) && !empty($_POST["dateto"]))
   {
      $dispmsg = "<b>Employees Appraised between ".$_POST["datefrom"]." and ".$_POST["dateto"]."</b>";
	   
      $datefrom=dateconvert($_POST["datefrom"],1);
	  $dateto=dateconvert($_POST["dateto"],1);
			 		     
	  $sqlstr="select count(tbl_appraisals.id) as totals ,upper(tbl_appresults.appresult) as appresult,tbl_appresults.id from tbl_appraisals inner  join tbl_appresults on 
 tbl_appraisals.result_fk=tbl_appresults.id inner join prmember on prmember.MemberNo = tbl_appraisals.empid_fk  where appdate >= '$datefrom' and appdate <='$dateto' group by tbl_appresults.appresult,tbl_appresults.id";
	}
	else
	  $sqlstr="select count(tbl_appraisals.id) as totals ,upper(tbl_appresults.appresult) as appresult, tbl_appresults.id from tbl_appraisals inner  join tbl_appresults on 
 tbl_appraisals.result_fk=tbl_appresults.id inner join prmember on prmember.MemberNo = tbl_appraisals.empid_fk group by tbl_appresults.appresult,tbl_appresults.id";
		  
	if (!empty($dispmsg))
	  echo "$dispmsg";	  
	//echo $sqlstr;
	 
 ?>
<table width="847" cellpadding="0" cellspacing="0"  id="main">
  <tr>
    <td id="cell_top">&nbsp;
    </td>
  </tr>

  <tr>
    <td >
    &bull;&nbsp;<a href="index.php" onfocus="blurLink(this);">Home</a>&nbsp;&bull;&nbsp;&nbsp;</td>

  </tr>

  <tr>
    <td width="1646"  bgcolor="#FFFFFF" >
	
<form name="totalsrepfrm" method="post" action="appraisalrpt.php">
<table width="100%"  border="0">
      <tr>
        <th width="20%"><div align="right">Employment Date From</div></th>
        <th width="20%">          
          <div class="highlight" id="inf_bank">&nbsp;
            <input name="datefrom" type="text" id="datefrom"  <? if (!empty($dob)) echo "value=$dob"; ?>>
          </div>
        </th>
        <th width="16%"><div align="right">Employment Date To</div></th>
        <th width="44%"><input name="dateto" type="text" id="dateto"  <? if (!empty($dob)) echo "value=$dob"; ?>></th>
      </tr>
      <tr>
        <th>
          <input type="hidden" name="action" <?  if (!empty($action)) echo "value=$action"; ?>>
		  <input type="hidden" name="id" <?  if (!empty($id)) echo "value=$id"; ?>>
		  </th>
        <th colspan="3"><div align="right">
            <input type="reset" name="Reset" value="Reset">
            <input type="submit" name="Submit" value="Submit" onclick="return validateOnSubmit()">
        </div></th>
        </tr>
    </table> 
	</form>    
    </td>
  </tr>
  <tr>
    <td  bgcolor="#FFFFFF" ><table width="100%"  border="0">
      <tr>
       
        <th width="2%">Appraisal Result</th>
		<th width="5%"><div align="center">No. of Employees </div></th>
        </tr>
	  <?
	    	
		 //count active staff for now	
		
		 
		  $result=$d->query($sqlstr) or die(mysql_error());
				
		  while ($row=$d->fetch_object($result))
		  {
		    echo "<tr>";
			echo "<td><a href=\"appraisalrptdet.php?apprid=$row->id\">".$row->appresult."</a></td>";
		    echo "<td>".$row->totals."</td>";
		    $total=$row->totals+$total;
		    echo "</td></tr>";
		  }
		
	     echo "<td> <b>Totals</b> </td>";
		 echo "<td> <b>$total</b> </td>";
	  ?>
    </table></td>
  </tr>
</table>

<?
  $d->close();
?>
</body>

</html>



