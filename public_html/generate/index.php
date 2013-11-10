<html>
<title>Perfect Venn diagram generator - Wikimedia Tool Labs</title>
</html>
<body>

<?php
$left = $_GET['left'];
$right = $_GET['right'];
$both = $_GET['both'];
if (isset($_GET['neither'])) {
$neither = $_GET['neither'];
} else {
$neither = 0;
}
?>

<?php if (min($left, $right, $both, $neither) < 0): ?>

<p>Invalid parameters: all values must be zero or greater.</p>

<?php elseif ($both > min($left, $right)): ?>

<p>Invalid value <?php echo $both; ?> for <b>both</b> (intersection size): must be between 0 and <?php echo min($left, $right); ?>.</p>

<?php else: ?>

<p>Perfect Venn diagram for values:</b>
<ul>
<li><b>Left</b> (red circle): <?php echo($_GET['left']); ?></li>
<li><b>Right</b> (blue circle): <?php echo($_GET['right']); ?></li>
<li><b>Both</b> (purple intersection): <?php echo($_GET['both']); ?></li>
<?php if (isset($_GET['neither']) && $_GET['neither']): ?>
<li><b>Neither</b> (yellow area): <?php echo($_GET['neither']); ?></li>
<?php endif; ?>
</ul>

<?php
echo('<img width="800" src="image/?' . $_SERVER['QUERY_STRING'] . '&filetype=png&width=800">');
$query = '?' . $_SERVER['QUERY_STRING'] . '&width=800&download=1';
echo('<p>Download as: <a href="image/' . $query . '&filetype=svg">.svg</a> | <a href="image/' . $query . '&filetype=png">.png</a> | <a href="image/' . $query . '&filetype=pdf">.pdf</a> | <a href="image/' . $query . '&filetype=ps">.ps</a></p>');
?>

<?php
$width_pixels = 800;
include 'info/index.php';
?>

<?php endif; ?>
</body>
</html>
