<html>
<head>
<title>Autocomplete examples</title>
<link href="autocomplete.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="jquery.autocomplete.js"></script>
<script type="text/javascript">
jQuery().ready(function($){
	$("#autocomplete5").autocomplete({
		source:"#select",
		fillin:true,
		onKeyPress:function(){
			var o=this;
			setTimeout(function(){
				o.ac.val(o.ac.val().replace(/[^0-9]+/g,""));
			},50);
		},
	});
});
</script>
</head>
<body>
<input id="autocomplete5" placeholder="xxxx" style="font-weight: bold;" />
<select id="select" style="display:none;">
<option>Test1</option>
<option>Test2</option>
<option selected="selected">Test3</option>
<option>Test4</option>
</select>
</body>
</html>