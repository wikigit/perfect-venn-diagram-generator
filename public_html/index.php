<html>
<title>Perfect Venn diagram generator - Wikimedia Tool Labs</title>
</html>
<body>
<p><b>Perfect Venn diagram generator</b> is a tool for generating Venn
diagrams that are perfectly-to-scale; that is, both circles, their
overlapping area, and the surrounding area (if applicable) will have
an area proportional to the number of items falling in that area. It is
intended to generate diagrams for use in Wikimedia Foundation projects,
but can serve a variety of uses.</p>

<p>You can download the finished diagram in svg, png, pdf, and ps formats.
This tool does not currently add labels, but you can add them youself.</p>

<form name="input" action="generate/" method="get">
Number of items in left circle (including items in both): <input type="text" name="left"><br/>
Number of items in right circle (including items in both): <input type="text" name="right"><br/>
Number of items in both circles (intersection): <input type="text" name="both"><br/>
Number of items in neither circle (leave blank if not applicable): <input type="text" name="neither"><br/>
<input type="submit" value="Generate">

<p>Other tools for generating to-scale Venn diagrams:</p>
<ul>
<li><a href="http://jura.wi.mit.edu/bioc/tools/venn.php">Venn Diagram Generator - Bioinformatics and Research Computing</a> (handles labels and gives color choices but no "neither")</li>
<li><a href="http://www.cs.kent.ac.uk/people/staff/pjr/EulerVennCircles/EulerVennApplet.html">Applet For Drawing 3 Set Area-Proportional Venn Diagrams</a> (handles 3 circles, but areas may not be exact)</li>
</ul>

<p>To contact the developer, leave a note at <a href="http://en.wikipedia.org/wiki/User_talk:Dcoetzee">User talk:Dcoetzee on English Wikipedia</a>, or e-mail <code>dc@moonflare.com</code></p>

<p>This tool is <a href="http://unlicense.org/">free and unencumbered software released into the public domain.</a> Source code is <a href="https://github.com/wikigit/perfect-venn-diagram-generator">available from github</a>.</p>
</form>
</body>
