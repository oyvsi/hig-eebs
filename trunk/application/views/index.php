<h1>Welcome to ze bloggsystem</h1>
<div id="navigation">
<?php
	echo '<li>' . HTML::appLink('Toppliste', 'blog/toppliste') . '</li>';
?>
</div>

<?php
/*	$form = new Form('arne', 'strand', 'nils');
	$form->addInput('text', 'Laffen', 'Skriv Laffen');
	echo $form->genForm();
*/
foreach($this->args['blogPosts'] as $blogPost) {
	print_r($blogPost);
}
