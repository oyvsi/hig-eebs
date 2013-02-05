<?php HTML::jsLink('tiny_mce/tiny_mce'); ?>
<script type="text/javascript">
tinyMCE.init({
	mode: "textareas",
	theme: "advanced"
});
</script>

<?php
echo $this->vars['form'];
