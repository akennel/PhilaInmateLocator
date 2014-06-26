<?php
/* Plugin Name: Phila Inmate Locator Widget
Plugin URI: localhost/wordpress
Description: Helps find inmaates
Version: 1.0
Author: Andrew Kennel
Author URI: localhost/wordpress
*/
add_shortcode('PhilaInmateLocator', 'philainmatelocator_handler');

function philainmatelocator_handler(){
$message = <<<EOM

<html>
<head>
<script type="text/javascript">
<!--
function ClearSearchCriteria() {
	document.getElementById("txtLName").value = "";
	document.getElementById("txtFName").value = "";
	document.getElementById("txtDob").value = "";
	document.getElementById("txtppn").value = "";
	document.getElementById("PhilaInmateResults").innerHTML = "";
}
//-->
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
<!--
function validateInmateLocatorFields()
    {
	var isValid = true;
        if (document.getElementById("txtppn").value == '' && document.getElementById("txtLName").value == '' && document.getElementById("txtFName").value == '' && document.getElementById("txtDob").value == '')
        {
            alert("Please Enter Valid Criteria");
            document.getElementById("txtppn").focus();
            isValid = false;
        }
        if(document.getElementById("txtDob").value == '')
        {
            if ((document.getElementById("txtLName").value != '' && document.getElementById("txtFName").value == ''))
            {
                alert("Please Enter First Name.");
                document.getElementById("txtFName").focus();
                isValid = false;
            }
            if ((document.getElementById("txtLName").value == '' && document.getElementById("txtFName").value != ''))
            {
                alert("Please Enter Last Name.");
                document.getElementById("txtLName").focus();
                isValid = false;
            }
        }
        if(document.getElementById("txtDob").value != '')
        {
            if ((document.getElementById("txtLName").value == '' && document.getElementById("txtFName").value == ''))
            {
                alert("Please Enter First Name or Last Name.");
                document.getElementById("txtFName").focus();
                isValid = false;
            }
            if ((document.getElementById("txtLName").value != '' && document.getElementById("txtFName").value == ''))
            {
                alert("Please Enter First Name.");
                document.getElementById("txtFName").focus();
                isValid = false;
            }
            if ((document.getElementById("txtLName").value == '' && document.getElementById("txtFName").value != ''))
            {
                alert("Please Enter Last Name.");
                document.getElementById("txtLName").focus();
                isValid = false;
            }
        }
		return isValid;
    }
function startInmateSearch()
{
	var validFields = validateInmateLocatorFields();
	if (validFields == true)
	{
		var searchCriteria = buildInmateLocatorSearchCriteria();	
		getInmates(searchCriteria);
	}
	
	

}

function getInmates(searchCriteria)
{
var results = "";
var requestURL = "http://moisappprd01.phila.gov/PrisonInmateLocator/InmateLocator.asmx/GetListOfInmates?WhereClause=" + searchCriteria;
//requestURL = "http://localhost/GetListOfInmates.xml";
var resultsCount = 0;
var newHTML = "<h2>Found the Following</h2>";
newHTML += "<ul>";

$.get(requestURL, {}, function(xml){
	$('DataSet', xml).each(function(read){
		
		$('Table', xml).each(function(read){
			resultsCount = resultsCount + 1;
			newHTML += "<li>";
			newHTML += "Facility: " + $(this).find("FACILITY").text() + " | " + $(this).find("PPN").text() + " " ;
			newHTML += "</li>";
		});
		newHTML += "</ul>";
		newHTML += "Found " + resultsCount + " results.";
		document.getElementById("PhilaInmateResults").innerHTML = newHTML;
	});
});


}


function buildInmateLocatorSearchCriteria()
{

var PPN = document.getElementById("txtppn").value;
var LName = document.getElementById("txtLName").value;
var FName = document.getElementById("txtFName").value;
var DOB = document.getElementById("txtDob").value;
var sbParameter = "";

	if (PPN != "")
        {
            if (PPN.trim.length == 6)
                sbParameter +=(" PPN = ' " + PPN.toUpperCase() + "'");
            else
                sbParameter += (" PPN = '" + PPN.toUpperCase().trim() + "'");
        }
        if (LName != "" && sbParameter.length > 0)
        {
            sbParameter += (" and Last_Name = '" + LName.Value.toUpperCase().trim() + "'");
        }
        else if (LName != "")
        {
            sbParameter += (" Last_Name = '" + LName.toUpperCase().trim() + "'");
        }

        if (FName != "" && sbParameter.length > 0)
        {
            sbParameter += (" and First_Name = '" + FName.toUpperCase().trim() + "'");
        }
        else if (FName != "")
        {
            sbParameter += (" First_Name = '" + FName.toUpperCase().trim() + "'");
        }
        if (DOB != "" && sbParameter.length > 0)
        {
            sbParameter += (" and DOB = '" + DOB.toUpperCase().trim() + "'");
        }
        else if (DOB != "")
        {
            sbParameter += (" DOB = '" + DOB.toUpperCase().trim() + "'");
        }
        return sbParameter;

}



//-->
</script>

</head>

<style>



</style>

<div id="PhilaInmateLocatorWidget" class ="PhilaWidget">
	<span id="PhilaInmateLocatorMainWindow">
		<h1>Inmate Locator</h1>	
		PPN:<input name="txtppn" type="text" id="txtppn">
		<br/>
		Last Name:<input name="txtLName" type="text" id="txtLName">
		<br/>
		First Name:<input name="txtFName" type="text" id="txtFName">
		<br/>
		Date of Birth:<input name="txtDob" type="text" id="txtDob">
		<br/>
		<div id="PhilaInmateResults"></div>
		<input type="submit" name="btnInmateLocator" value="Find Inmate" onclick="startInmateSearch();" id="btnInmateLocator"><input type="submit" name="btnClear" value="Clear" onclick="ClearSearchCriteria();" id="btnClear">
	</span>
</div> 

EOM;

return $message;
}

function philapaywidget($args, $instance) { // widget sidebar output
  extract($args, EXTR_SKIP);
  echo $before_widget; // pre-widget code from theme
  echo philainmatelocator_handler();
  echo $after_widget; // post-widget code from theme
}
?>