<script>
		var oEdit1 = new InnovaEditor("oEdit1");		
		oEdit1.arrStyle = [["BODY",false,"","font:10px verdana,arial,sans-serif;"]];
		
		oEdit1.width=250;
		oEdit1.features=["Save","FullScreen","Preview","Print","Search","SpellCheck",
			"Cut","Copy","Paste","PasteWord","PasteText","|","Undo","Redo","|",
			"ForeColor","BackColor","|","Bookmark","Hyperlink",
			"CustomTag","HTMLFullSource","BRK","Numbering","Bullets","|","Indent","Outdent","LTR","RTL","|","Image","Flash","Media","|","InternalLink","CustomObject","|",
			"Table","Guidelines","Absolute","|","Characters","Line",
			"Form","Clean","ClearAll","BRK",
			"StyleAndFormatting","TextFormatting","ListFormatting","BoxFormatting",
			"ParagraphFormatting","CssText","Styles","|",
			"Paragraph","FontName","FontSize","|",
			"Bold","Italic",
			"Underline","Strikethrough","|","Superscript","Subscript","|",
			"JustifyLeft","JustifyCenter","JustifyRight","JustifyFull"];

/*
FOR LOCALHOST ONLY:
*/
		oEdit1.cmdAssetManager="modalDialogShow('http://diliman_web/admin/editor/assetmanager/assetmanager.php',640,465)";

		
	
/*	
	oEdit1.cmdAssetManager="modalDialogShow('http://mls-dentalcosmetics.com/admin/editor/assetmanager/assetmanager.php',640,465)";
*/	
		
		
		
		oEdit1.onSave = new Function("submitForm()");

		oEdit1.cmdInternalLink = "modelessDialogShow('links.htm',365,270)"
		oEdit1.cmdCustomObject = "modelessDialogShow('objects.htm',365,270)"

		oEdit1.arrCustomTag=[["First Name","{%first_name%}"],
				["Last Name","{%last_name%}"],
				["Email","{%email%}"]];
		
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	    </script>