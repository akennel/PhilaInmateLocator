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

<head>
<script type="text/javascript">
<!--
function ClearSearchCriteria() {
	document.getElementById("txtLName").value = "";
	document.getElementById("txtFName").value = "";
	document.getElementById("txtppn").value = "";
	document.getElementById("PhilaInmateResultsBlock").innerHTML = "";
}
//-->
</script>
//<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
<!--
function validateInmateLocatorFields()
    {
	var isValid = true;
        if (document.getElementById("txtppn").value == '' && document.getElementById("txtLName").value == '' && document.getElementById("txtFName").value == '')
        {
            alert("Please Enter Valid Criteria");
            document.getElementById("txtppn").focus();
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
var newHTML = "<h2>Found the Following</h2>";
newHTML += "<ul>";

$.get(requestURL, {}, function(xml){
	$('DataSet', xml).each(function(read){
		
		
		$('Table', xml).each(function(read){
		var prisonURL = "";
		var facilityCode = $(this).find("FACILITY").text().trim();
		switch(facilityCode) {
			case "ASDCU":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/alternative_and_special_detention.htm";
			break;
			
			case "CFCF":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/curran_fromhold.htm";
			break;
			
			case "DC":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/detention_center.htm";
			break;
		
			case "HOC":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/house_of_corrections.htm";
			break;
		
			case "PICC":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/industrial_correctional_center.htm";
			break;
			
			case "RCF":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/riverside_correctional_facility.htm";
			break;
			
			case "OJ":
			prisonURL = "http://moiswebprd01.phila.gov/prisons/oj.htm";
			break;
			
			default:
			prisonURL = "http://www.phila.gov/prisons";
			break;
		}
		
			newHTML += "<li>";
			newHTML += "Name: " + $(this).find("FIRST_NAME").text() + $(this).find("LAST_NAME").text() + " | DOB: " + $(this).find("DOB").text() + "<br/>";
			newHTML += "PPN: "  + $(this).find("PPN").text() + " | Facility: <a href=" + prisonURL + ">" + $(this).find("FACILITY").text() + "</a>";
			newHTML += "</li>";
			
		});
		newHTML += "</ul>";
		
		newHTML += "<input type=\"submit\" name=\"btnSearchAgain\" value=\"Search Again\" onclick=\"StartNewSearch()\" id=\"btnSearchAgain\">"
		document.getElementById("PhilaInmateResultsBlock").innerHTML = newHTML;
		document.getElementById("PhilaInmateSearchBlock").style.display = "none";
		document.getElementById("PhilaInmateResultsBlock").style.display = "block";
	});
});


}


function buildInmateLocatorSearchCriteria()
{

var PPN = document.getElementById("txtppn").value;
var LName = document.getElementById("txtLName").value;
var FName = document.getElementById("txtFName").value;
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
        return sbParameter;

}


function StartNewSearch()
{

ClearSearchCriteria();
document.getElementById("PhilaInmateSearchBlock").style.display = "block";
document.getElementById("PhilaInmateResultsBlock").style.display = "none";
}


//-->
</script>

</head>

<style>



</style>

<div id="PhilaInmateLocatorWidget" class ="PhilaWidget">
	<span id="PhilaInmateLocatorMainWindow">
		<h1>Inmate Locator</h1>	
		<div id="PhilaInmateSearchBlock">
			PPN:<input name="txtppn" type="text" id="txtppn">
			<br/>
			Last Name:<input name="txtLName" type="text" id="txtLName">
			<br/>
			First Name:<input name="txtFName" type="text" id="txtFName">
			<br/>
			<input type="submit" name="btnInmateLocator" value="Find Inmate" onclick="startInmateSearch();" id="btnInmateLocator">
		</div>
		<div id="PhilaInmateResultsBlock" style="display:none;">
			<input type="submit" name="btnSearchAgain" value="Search Again" onclick="StartNewSearch();" id="btnSearchAgain">
		</div>
		
	</span>
</div>

EOM;

return $message;
}

function philainmatelocatorwidget($args, $instance) { // widget sidebar output
  extract($args, EXTR_SKIP);
  echo $before_widget; // pre-widget code from theme
  echo philainmatelocator_handler();
  echo $after_widget; // post-widget code from theme
}
?>