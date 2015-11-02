<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
	body{font:10px verdana,arial,sans-serif;}
	a{color:#cc0000;font-size:xx-small;}
</style>

<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='scripts/moz/editor.js'></script>";
?>

<script>
function submitForm()
	{
	document.forms.Form1.elements.inpContent.value = oEdit1.getHTMLBody();
	document.forms.Form1.submit()
	}
</script>

</head>
<body>

<h5>Complete Toolbar Buttons (PHP example) &nbsp;-&nbsp;<a href="../default.htm">Back</a></h5>

<pre id="idTemporary" name="idTemporary" style="display:none;">
<?
if (isset($_POST["inpContent"])) 
	{
	$sContent=stripslashes($_POST['inpContent']);//remove slashes (/)	
	echo htmlentities($sContent);
	}
?>
</pre>

<form method="post" action="default1.php" id="Form1">
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");		
		oEdit1.arrStyle = [["BODY",false,"","font:10px verdana,arial,sans-serif;"]];
		
		oEdit1.width=205;
		oEdit1.features=["Save","FullScreen","Preview","Print","Search","SpellCheck",
			"Cut","Copy","Paste","PasteWord","PasteText","|","Undo","Redo","|",
			"ForeColor","BackColor","|","Bookmark","Hyperlink",
			"CustomTag","HTMLFullSource","HTMLSource","XHTMLFullSource",
			"XHTMLSource","BRK","Numbering","Bullets","|","Indent","Outdent","LTR","RTL","|","Image","Flash","Media","|","InternalLink","CustomObject","|",
			"Table","Guidelines","Absolute","|","Characters","Line",
			"Form","Clean","ClearAll","BRK",
			"StyleAndFormatting","TextFormatting","ListFormatting","BoxFormatting",
			"ParagraphFormatting","CssText","Styles","|",
			"Paragraph","FontName","FontSize","|",
			"Bold","Italic",
			"Underline","Strikethrough","|","Superscript","Subscript","|",
			"JustifyLeft","JustifyCenter","JustifyRight","JustifyFull"];

		oEdit1.cmdAssetManager="modalDialogShow('/editor/assetmanager/assetmanager.php',640,465)";
		
		oEdit1.onSave = new Function("submitForm()");

		oEdit1.cmdInternalLink = "modelessDialogShow('links.htm',265,270)"
		oEdit1.cmdCustomObject = "modelessDialogShow('objects.htm',265,270)"

		oEdit1.arrCustomTag=[["First Name","{%first_name%}"],
				["Last Name","{%last_name%}"],
				["Email","{%email%}"]];
		
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="inpContent" id="inpContent">
</form>


<br /><br />

<?
if (isset($_POST["inpContent"])) 
	{
	$sContent=stripslashes($_POST['inpContent']);//remove slashes (/)	
	echo $sContent;
	}
?>

</body>
</html>