<?php

$file = rex_file::get(rex_path::addon('lus_cleverreach','README.md'));
$Parsedown = new Parsedown();
$content =  '<div id="lus_cleverreach">'.$Parsedown->text($file);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('title'));
$fragment->setVar('class', 'info', false);
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');


