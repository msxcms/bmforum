<html>
<head>
<style type="text/css">
body,input,select{font-size:9pt; color:333333; FONT-FAMILY: 宋体}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor=EEEEEE  leftmargin=0 background="background.gif" topmargin=0 scroll=no>
<script language=JavaScript1.2>
function beginGenerator() {
  var validChars = true;
  var inputText = document.ascii.inputField.value;

  inputText = inputText.toLowerCase();

  for(i = 0; i < inputText.length; i++) {
    if(inputText.charAt(i) != "a" && inputText.charAt(i) != "b" && inputText.charAt(i) != "c" && inputText.charAt(i) != "d" && inputText.charAt(i) != "e" && inputText.charAt(i) != "f" && inputText.charAt(i) != "g" && inputText.charAt(i) != "h" && inputText.charAt(i) != "i" && inputText.charAt(i) != "j" && inputText.charAt(i) != "k" && inputText.charAt(i) != "l" && inputText.charAt(i) != "m" && inputText.charAt(i) != "n" && inputText.charAt(i) != "o" && inputText.charAt(i) != "p" && inputText.charAt(i) != "q" && inputText.charAt(i) != "r" && inputText.charAt(i) != "s" && inputText.charAt(i) != "t" && inputText.charAt(i) != "u" && inputText.charAt(i) != "v" && inputText.charAt(i) != "w" && inputText.charAt(i) != "x" && inputText.charAt(i) != "y" && inputText.charAt(i) != "z" && inputText.charAt(i) != " " && inputText.charAt(i) != "0" && inputText.charAt(i) != "1" && inputText.charAt(i) != "2" && inputText.charAt(i) != "3" && inputText.charAt(i) != "4" && inputText.charAt(i) != "5" && inputText.charAt(i) != "6" && inputText.charAt(i) != "7" && inputText.charAt(i) != "8" && inputText.charAt(i) != "9" && inputText.substring(i,(i+2)) != "\\n") {validChars = false; invalChar = inputText.charAt(i)};
  }

  if(validChars == false) {alert('Fatal Error: Character "'+invalChar+'" invalid.  Only characters a-z, 0-9, and newlines (\n) accepted.')}
  if(validChars == true) {
    if(document.ascii.textStyle[0].selected) {buildStyle1(inputText)}
    /*if(document.ascii.textStyle[1].selected) {buildStyle2(inputText)}
    if(document.ascii.textStyle[2].selected) {buildStyle3(inputText)}*/
  }
}

function buildStyle1(inputText,booleanRepeat) {
	var newline = false; var line0 = ""; var line1 = ""; var line2 = ""; var line3 = ""; var space = "    "; var a = new Array(4); var b = new Array(4); var c = new Array(4); var d = new Array(4); var e = new Array(4); var f = new Array(4); var g = new Array(4); var h = new Array(4); var I = new Array(4); var j = new Array(4); var k = new Array(4); var l = new Array(4); var m = new Array(4); var n = new Array(4); var o = new Array(4); var p = new Array(4); var q = new Array(4); var r = new Array(4); var s = new Array(4); var t = new Array(4); var u = new Array(4); var v = new Array(4); var w = new Array(4); var x = new Array(4); var y = new Array(4); var z = new Array(4); var zero = new Array(4); var one = new Array(4); var two = new Array(4); var three = new Array(4); var four = new Array(4); var five = new Array(4); var six = new Array(4); var seven = new Array(4); var eight = new Array(4); var nine = new Array(4);
	a[0] = "     ";	a[1] = " __  ";	a[2] = "(__( ";	a[3] = "     ";
	b[0] = "     ";	b[1] = "│_  ";	b[2] = "│_) ";	b[3] = "     ";
	c[0] = "     ";	c[1] = " __  ";	c[2] = "(___ ";	c[3] = "     ";
	d[0] = "     ";	d[1] = " _│ ";	d[2] = "(_│ ";	d[3] = "     ";
	e[0] = "      ";	e[1] = " ___  ";	e[2] = "(__/_ ";	e[3] = "      ";
	f[0] = " ┌ ";	f[1] = " ┼ ";	f[2] = " ┘  ";	f[3] = "    ";
	g[0] = "     ";	g[1] = " __  ";	g[2] = "(__\ ";	g[3] = " __/ ";
	h[0] = "     ";	h[1] = " ├┐";	h[2] = " ││";	h[3] = "     ";
	I[0] = "  ";	I[1] = " o";	I[2] = "│";	I[3] = "  ";
	j[0] = "     ";	j[1] = "  │ ";	j[2] = "(_│ ";	j[3] = "     ";
	k[0] = "     ";
	k[1] = "│_/ ";
	k[2] = "│ \ ";	k[3] = "     ";
	l[0] = "    ";	l[1] = "│  ";	l[2] = "│_,";	l[3] = "    ";
	m[0] = "        ";	m[1] = " __ __  ";	m[2] = "│ )  ) ";	m[3] = "        ";
	n[0] = "     ";		n[1] = " __  ";		n[2] = "│ ) ";		n[3] = "     ";
	o[0] = "     ";		o[1] = " __  ";		o[2] = "(__) ";		o[3] = "     ";
	p[0] = "     ";	p[1] = " __  ";	p[2] = "│_) ";	p[3] = "│    ";
	q[0] = "     ";q[1] = " __  ";	q[2] = "(_│ ";	q[3] = "  │ ";
	r[0] = "     ";	r[1] = " __  ";	r[2] = "│ ' ";	r[3] = "     ";
	s[0] = "     ";		s[1] = "  __ ";		s[2] = "__)  ";		s[3] = "     ";
	t[0] = "     ";	t[1] = "_│_ ";	t[2] = " │_,";	t[3] = "     ";
	u[0] = "      ";	u[1] = "      ";	u[2] = "(__(_ ";	u[3] = "      ";
	v[0] = "     ";	v[1] = "     ";	v[2] = "(_│ ";	v[3] = "     ";
	w[0] = "        ";	w[1] = "        ";	w[2] = "(__(__( ";	w[3] = "        ";
	x[0] = "    ";		x[1] = "\\_' ";		x[2] = "/ \\ ";		x[3] = "    ";
	
	y[0] = "     ";	y[1] = "     ";	y[2] = "(_│ ";	y[3] = "  │ ";
	z[0] = "     ";		z[1] = "__   ";		z[2] = " (__ ";		z[3] = "     ";
	
	zero[0] = " __  ";	zero[1] = "│ │";	zero[2] = "│_│";	zero[3] = "     ";
	one[0] = "   ";	one[1] = "'│";	one[2] = " │";	one[3] = "   ";
	two[0] = " __  ";	two[1] = " __) ";	two[2] = "(___ ";	two[3] = "     ";
	three[0] = "___ ";	three[1] = " _/ ";	three[2] = "__) ";	three[3] = "    ";
	four[0] = "     ";	four[1] = "(_│ ";	four[2] = "  │ ";	four[3] = "     ";
	five[0] = " __  ";	five[1] = "(__  ";	five[2] = "___) ";	five[3] = "     ";
	six[0] = "     ";	six[1] = " /_  ";	six[2] = "(__) ";	six[3] = "     ";
	seven[0] = "__  ";	seven[1] = "  / ";	seven[2] = " /  ";	seven[3] = "    ";
	eight[0] = " __  ";	eight[1] = "(__) ";	eight[2] = "(__) ";	eight[3] = "     ";
	nine[0] = " __  ";	nine[1] = "(__) ";	nine[2] = "  /  ";	nine[3] = "     ";

	for(i=0; i < inputText.length; i++) {
		if(inputText.charAt(i) == " ") {line0 += space; 	line1 += space; 	line2 += space; 	line3 += space}
		if(inputText.charAt(i) == "a") {line0 += a[0]; 		line1 += a[1];		line2 += a[2]; 		line3 += a[3]}
		if(inputText.charAt(i) == "b") {line0 += b[0]; 		line1 += b[1]; 		line2 += b[2]; 		line3 += b[3]}
		if(inputText.charAt(i) == "c") {line0 += c[0]; 		line1 += c[1]; 		line2 += c[2]; 		line3 += c[3]}
		if(inputText.charAt(i) == "d") {line0 += d[0]; 		line1 += d[1]; 		line2 += d[2]; 		line3 += d[3]}
		if(inputText.charAt(i) == "e") {line0 += e[0]; 		line1 += e[1]; 		line2 += e[2]; 		line3 += e[3]}
		if(inputText.charAt(i) == "f") {line0 += f[0]; 		line1 += f[1]; 		line2 += f[2]; 		line3 += f[3]}
		if(inputText.charAt(i) == "g") {line0 += g[0]; 		line1 += g[1];	 	line2 += g[2]; 		line3 += g[3]}
		if(inputText.charAt(i) == "h") {line0 += h[0]; 		line1 += h[1]; 		line2 += h[2]; 		line3 += h[3]}
		if(inputText.charAt(i) == "i") {line0 += I[0]; 		line1 += I[1]; 		line2 += I[2]; 		line3 += I[3]}
		if(inputText.charAt(i) == "j") {line0 += j[0]; 		line1 += j[1]; 		line2 += j[2]; 		line3 += j[3]}
		if(inputText.charAt(i) == "k") {line0 += k[0]; 		line1 += k[1];		line2 += k[2]; 		line3 += k[3]}
		if(inputText.charAt(i) == "l") {line0 += l[0]; 		line1 += l[1]; 		line2 += l[2]; 		line3 += l[3]}
		if(inputText.charAt(i) == "m") {line0 += m[0]; 		line1 += m[1]; 		line2 += m[2]; 		line3 += m[3]}
		if(inputText.charAt(i) == "n") {line0 += n[0];	 	line1 += n[1]; 		line2 += n[2]; 		line3 += n[3]}
		if(inputText.charAt(i) == "o") {line0 += o[0]; 		line1 += o[1];	 	line2 += o[2]; 		line3 += o[3]}
		if(inputText.charAt(i) == "p") {line0 += p[0]; 		line1 += p[1]; 		line2 += p[2]; 		line3 += p[3]}
		if(inputText.charAt(i) == "q") {line0 += q[0]; 		line1 += q[1];	 	line2 += q[2]; 		line3 += q[3]}
		if(inputText.charAt(i) == "r") {line0 += r[0]; 		line1 += r[1];	 	line2 += r[2]; 		line3 += r[3]}
		if(inputText.charAt(i) == "s") {line0 += s[0]; 		line1 += s[1];	 	line2 += s[2]; 		line3 += s[3]}
		if(inputText.charAt(i) == "t") {line0 += t[0]; 		line1 += t[1];	 	line2 += t[2]; 		line3 += t[3]}
		if(inputText.charAt(i) == "u") {line0 += u[0]; 		line1 += u[1]; 		line2 += u[2]; 		line3 += u[3]}
		if(inputText.charAt(i) == "v") {line0 += v[0];	 	line1 += v[1]; 		line2 += v[2]; 		line3 += v[3]}
		if(inputText.charAt(i) == "w") {line0 += w[0]; 		line1 += w[1]; 		line2 += w[2]; 		line3 += w[3]}
		if(inputText.charAt(i) == "x") {line0 += x[0]; 		line1 += x[1]; 		line2 += x[2]; 		line3 += x[3]}
		if(inputText.charAt(i) == "y") {line0 += y[0]; 		line1 += y[1]; 		line2 += y[2]; 		line3 += y[3]}
		if(inputText.charAt(i) == "z") {line0 += z[0];	 	line1 += z[1]; 		line2 += z[2]; 		line3 += z[3]}
		if(inputText.charAt(i) == "0") {line0 += zero[0]; 	line1 += zero[1]; 	line2 += zero[2]; 	line3 += zero[3]}
		if(inputText.charAt(i) == "1") {line0 += one[0]; 	line1 += one[1]; 	line2 += one[2]; 	line3 += one[3]}
		if(inputText.charAt(i) == "2") {line0 += two[0]; 	line1 += two[1]; 	line2 += two[2]; 	line3 += two[3]}
		if(inputText.charAt(i) == "3") {line0 += three[0];	line1 += three[1];	line2 += three[2]; 	line3 += three[3]}
		if(inputText.charAt(i) == "4") {line0 += four[0]; 	line1 += four[1]; 	line2 += four[2]; 	line3 += four[3]}
		if(inputText.charAt(i) == "5") {line0 += five[0]; 	line1 += five[1]; 	line2 += five[2]; 	line3 += five[3]}
		if(inputText.charAt(i) == "6") {line0 += six[0]; 	line1 += six[1]; 	line2 += six[2]; 	line3 += six[3]}
		if(inputText.charAt(i) == "7") {line0 += seven[0];	line1 += seven[1];	line2 += seven[2]; 	line3 += seven[3]}
		if(inputText.charAt(i) == "8") {line0 += eight[0];	line1 += eight[1];	line2 += eight[2]; 	line3 += eight[3]}
		if(inputText.charAt(i) == "9") {line0 += nine[0]; 	line1 += nine[1]; 	line2 += nine[2]; 	line3 += nine[3]}
		if(inputText.substring(i,(i+2)) == "\\n") {var newline = true; break}
	}
		var outputText = line0+"\n"+line1+"\n"+line2+"\n"+line3;
		parent.AddText("\n"+outputText);
}

</script>
<form name=ascii>
<B>请输入:</B>
内容: <INPUT name=inputField> &nbsp; &nbsp;
字体风格: <SELECT name=textStyle><OPTION>Futuristik
</SELECT> 
<INPUT onclick=beginGenerator() type=button value=生成 name=button> 
</form>

</body></html>
<!------- OPTION selected>Block<OPTION>Wireframe</OPTION> ------>
