<?php 
if(!defined("IN_MYBB"))
{
	die("Direct access to this file is not allowed.");
}

$plugins->add_hook('showthread_start', 'dcodesyntax');
function dcodesyntaxhighlight_info()
{
    global $mybb;
    if(isset($mybb->settings['dcodesyntax_activate'])== 1)
    {
        $dcodesyntax_stats = '<div style="float: right;"><a href="index.php?module=config&amp;action=change&amp;search=dcodesyntaxsettings" style="color:#1c9f1c; background: url(../images/icons/user.png) no-repeat 0px 18px; padding: 18px; text-decoration: none;"> Plugin Settings</a></div>';
    }
    else
    {
        $dcodesyntax_stats .= '<div style="float: right; color: rgb(255, 27, 0);">Plugin disabled!</div>';
    }
	return array(
		"name"			=> "DCode Syntax Highlighter",
		"description"	=> "An advanced code/ syntax highlighting system for MyBB." . $dcodesyntax_stats,
		"website"		=> "http://rashanhasaranga.com",
		"author"		=> "Rashan Hasaranga",
		"authorsite"	=> "http://rashanhasaranga.com",
		"version"		=> "1.1",
		"codename"		=> "dcodesyntaxhighlight",
		"compatibility" => "*"
	);
}

function dcodesyntaxhighlight_activate() {
global $db, $mybb;
$setting_group = array(
    'name' => 'dcodesyntaxsettings',
    'title' => 'DCode Syntax Highlighter Settings',
    'description' => 'Settings for the DCode Syntax Highlighter plugin',
    'isdefault' => 0
);

$gid = $db->insert_query("settinggroups", $setting_group);
$setting_array = array(

     'dcodesyntax_activate' => array(
        'title' => 'Enable/Disable Plugin',
        'description' => 'Select if you want to activate this plugin',
        'optionscode' => "yesno",
        'value' => 1,
        'disporder' => 1
    ),
    'dcodesyntax_theme' => array(
        'title' => 'Codeblock Theme',
        'description' => 'Select color styles for codeblock',
        'optionscode' => "select\n0=Default\n1=Dark\n2=Rainbow\n3=Arta\n4=Ascetic\n5=Atom-one-dark\n6=Atom-one-light\n7=Brown-paper\n8=Codepen-embed\n9=Darcula\n10=Far\n11=Github\n12=Google Code\n13=Grayscale\n14=Hybrid\n15=Ocean\n16=Purebasic\n17=Solarized-Dark\n18=Solarized-Light\n19=Tomorrow\n20=VS\n21=xCode\n22=Zenburn",
        'value' => 0,
        'disporder' => 2
    )
);

foreach($setting_array as $name => $setting)
{
    $setting['name'] = $name;
    $setting['gid'] = $gid;

    $db->insert_query('settings', $setting);
}

rebuild_settings();

require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

find_replace_templatesets(
    "showthread",
    "#" . preg_quote('</head>') . "#i",
    '{$dcodesyntax}</head>'
);
}

function dcodesyntaxhighlight_deactivate()
{
global $db;
$db->delete_query('settings', "name IN ('dcodesyntax_theme')");
$db->delete_query('settings', "name IN ('dcodesyntax_activate')");
$db->delete_query('settinggroups', "name = 'dcodesyntaxsettings'");
rebuild_settings();

require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
find_replace_templatesets(
    "showthread",
    "#" . preg_quote('{$dcodesyntax}</head>') . "#i",
    '</head>'
);
}



function dcodesyntax()
{
global $dcodesyntax, $mybb; 

$highlighterjs = "<script src=\"" . $mybb->settings['bburl'] . "/jscripts/highlight.min.js\"></script>";

$embedjs = "<script type=\"text/javascript\"> $(document).ready(function() { $('code').each(function(i, block) { hljs.highlightBlock(block); }); }); </script>";

if ($mybb->settings['dcodesyntax_theme'] == "0") { //default
    $codeblocktheme = "/* Original highlight.js style (c) Ivan Sagalaev <maniac@softwaremaniacs.org> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #F0F0F0; } /* Base color: saturation 0; */ .hljs, .hljs-subst { color: #444; } .hljs-comment { color: #888888; } .hljs-keyword, .hljs-attribute, .hljs-selector-tag, .hljs-meta-keyword, .hljs-doctag, .hljs-name { font-weight: bold; } /* User color: hue: 0 */ .hljs-type, .hljs-string, .hljs-number, .hljs-selector-id, .hljs-selector-class, .hljs-quote, .hljs-template-tag, .hljs-deletion { color: #880000; } .hljs-title, .hljs-section { color: #880000; font-weight: bold; } .hljs-regexp, .hljs-symbol, .hljs-variable, .hljs-template-variable, .hljs-link, .hljs-selector-attr, .hljs-selector-pseudo { color: #BC6060; } /* Language color: hue: 90; */ .hljs-literal { color: #78A960; } .hljs-built_in, .hljs-bullet, .hljs-code, .hljs-addition { color: #397300; } /* Meta color: hue: 200 */ .hljs-meta { color: #1f7199; } .hljs-meta-string { color: #4d99bf; } /* Misc effects */ .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; }";
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "1") { //dark
    $codeblocktheme = "/* Dark style from softwaremaniacs.org (c) Ivan Sagalaev <Maniac@SoftwareManiacs.Org> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #444; } .hljs-keyword, .hljs-selector-tag, .hljs-literal, .hljs-section, .hljs-link { color: white; } .hljs, .hljs-subst { color: #ddd; } .hljs-string, .hljs-title, .hljs-name, .hljs-type, .hljs-attribute, .hljs-symbol, .hljs-bullet, .hljs-built_in, .hljs-addition, .hljs-variable, .hljs-template-tag, .hljs-template-variable { color: #d88; } .hljs-comment, .hljs-quote, .hljs-deletion, .hljs-meta { color: #777; } .hljs-keyword, .hljs-selector-tag, .hljs-literal, .hljs-title, .hljs-section, .hljs-doctag, .hljs-type, .hljs-name, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } ";
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "2") { //rainbow
    $codeblocktheme = "/* Style with support for rainbow parens */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #474949; color: #d1d9e1; } .hljs-comment, .hljs-quote { color: #969896; font-style: italic; } .hljs-keyword, .hljs-selector-tag, .hljs-literal, .hljs-type, .hljs-addition { color: #cc99cc; } .hljs-number, .hljs-selector-attr, .hljs-selector-pseudo { color: #f99157; } .hljs-string, .hljs-doctag, .hljs-regexp { color: #8abeb7; } .hljs-title, .hljs-name, .hljs-section, .hljs-built_in { color: #b5bd68; } .hljs-variable, .hljs-template-variable, .hljs-selector-id, .hljs-class .hljs-title { color: #ffcc66; } .hljs-section, .hljs-name, .hljs-strong { font-weight: bold; } .hljs-symbol, .hljs-bullet, .hljs-subst, .hljs-meta, .hljs-link { color: #f99157; } .hljs-deletion { color: #dc322f; } .hljs-formula { background: #eee8d5; } .hljs-attr, .hljs-attribute { color: #81a2be; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "3") { //arta
    $codeblocktheme = "/* Date: 17.V.2011 Author: pumbur <pumbur@pumbur.net> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #222; } .hljs, .hljs-subst { color: #aaa; } .hljs-section { color: #fff; } .hljs-comment, .hljs-quote, .hljs-meta { color: #444; } .hljs-string, .hljs-symbol, .hljs-bullet, .hljs-regexp { color: #ffcc33; } .hljs-number, .hljs-addition { color: #00cc66; } .hljs-built_in, .hljs-builtin-name, .hljs-literal, .hljs-type, .hljs-template-variable, .hljs-attribute, .hljs-link { color: #32aaee; } .hljs-keyword, .hljs-selector-tag, .hljs-name, .hljs-selector-id, .hljs-selector-class { color: #6644aa; } .hljs-title, .hljs-variable, .hljs-deletion, .hljs-template-tag { color: #bb1166; } .hljs-section, .hljs-doctag, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "4") { //ascetic
    $codeblocktheme = "/* Original style from softwaremaniacs.org (c) Ivan Sagalaev <Maniac@SoftwareManiacs.Org> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: white; color: black; } .hljs-string, .hljs-variable, .hljs-template-variable, .hljs-symbol, .hljs-bullet, .hljs-section, .hljs-addition, .hljs-attribute, .hljs-link { color: #888; } .hljs-comment, .hljs-quote, .hljs-meta, .hljs-deletion { color: #ccc; } .hljs-keyword, .hljs-selector-tag, .hljs-section, .hljs-name, .hljs-type, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "5") { //atom-one-dark
    $codeblocktheme = "/* Atom One Dark by Daniel Gamage Original One Dark Syntax theme from https://github.com/atom/one-dark-syntax base: #282c34 mono-1: #abb2bf mono-2: #818896 mono-3: #5c6370 hue-1: #56b6c2 hue-2: #61aeee hue-3: #c678dd hue-4: #98c379 hue-5: #e06c75 hue-5-2: #be5046 hue-6: #d19a66 hue-6-2: #e6c07b */ .hljs { display: block; overflow-x: auto; padding: 0.5em; color: #abb2bf; background: #282c34; } .hljs-comment, .hljs-quote { color: #5c6370; font-style: italic; } .hljs-doctag, .hljs-keyword, .hljs-formula { color: #c678dd; } .hljs-section, .hljs-name, .hljs-selector-tag, .hljs-deletion, .hljs-subst { color: #e06c75; } .hljs-literal { color: #56b6c2; } .hljs-string, .hljs-regexp, .hljs-addition, .hljs-attribute, .hljs-meta-string { color: #98c379; } .hljs-built_in, .hljs-class .hljs-title { color: #e6c07b; } .hljs-attr, .hljs-variable, .hljs-template-variable, .hljs-type, .hljs-selector-class, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-number { color: #d19a66; } .hljs-symbol, .hljs-bullet, .hljs-link, .hljs-meta, .hljs-selector-id, .hljs-title { color: #61aeee; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } .hljs-link { text-decoration: underline; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "6") { //atom-one-light
    $codeblocktheme = "/* Atom One Light by Daniel Gamage Original One Light Syntax theme from https://github.com/atom/one-light-syntax base: #fafafa mono-1: #383a42 mono-2: #686b77 mono-3: #a0a1a7 hue-1: #0184bb hue-2: #4078f2 hue-3: #a626a4 hue-4: #50a14f hue-5: #e45649 hue-5-2: #c91243 hue-6: #986801 hue-6-2: #c18401 */ .hljs { display: block; overflow-x: auto; padding: 0.5em; color: #383a42; background: #fafafa; } .hljs-comment, .hljs-quote { color: #a0a1a7; font-style: italic; } .hljs-doctag, .hljs-keyword, .hljs-formula { color: #a626a4; } .hljs-section, .hljs-name, .hljs-selector-tag, .hljs-deletion, .hljs-subst { color: #e45649; } .hljs-literal { color: #0184bb; } .hljs-string, .hljs-regexp, .hljs-addition, .hljs-attribute, .hljs-meta-string { color: #50a14f; } .hljs-built_in, .hljs-class .hljs-title { color: #c18401; } .hljs-attr, .hljs-variable, .hljs-template-variable, .hljs-type, .hljs-selector-class, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-number { color: #986801; } .hljs-symbol, .hljs-bullet, .hljs-link, .hljs-meta, .hljs-selector-id, .hljs-title { color: #4078f2; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } .hljs-link { text-decoration: underline; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "7") { //brown-paper
    $codeblocktheme = "/* Brown Paper style from goldblog.com.ua (c) Zaripov Yura <yur4ik7@ukr.net> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background:#b7a68e url(./brown-papersq.png); } .hljs-keyword, .hljs-selector-tag, .hljs-literal { color:#005599; font-weight:bold; } .hljs, .hljs-subst { color: #363c69; } .hljs-string, .hljs-title, .hljs-section, .hljs-type, .hljs-attribute, .hljs-symbol, .hljs-bullet, .hljs-built_in, .hljs-addition, .hljs-variable, .hljs-template-tag, .hljs-template-variable, .hljs-link, .hljs-name { color: #2c009f; } .hljs-comment, .hljs-quote, .hljs-meta, .hljs-deletion { color: #802022; } .hljs-keyword, .hljs-selector-tag, .hljs-literal, .hljs-doctag, .hljs-title, .hljs-section, .hljs-type, .hljs-name, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "8") { //codepen-embed
    $codeblocktheme = "/* codepen.io Embed Theme Author: Justin Perry <http://github.com/ourmaninamsterdam> Original theme - https://github.com/chriskempson/tomorrow-theme */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #222; color: #fff; } .hljs-comment, .hljs-quote { color: #777; } .hljs-variable, .hljs-template-variable, .hljs-tag, .hljs-regexp, .hljs-meta, .hljs-number, .hljs-built_in, .hljs-builtin-name, .hljs-literal, .hljs-params, .hljs-symbol, .hljs-bullet, .hljs-link, .hljs-deletion { color: #ab875d; } .hljs-section, .hljs-title, .hljs-name, .hljs-selector-id, .hljs-selector-class, .hljs-type, .hljs-attribute { color: #9b869b; } .hljs-string, .hljs-keyword, .hljs-selector-tag, .hljs-addition { color: #8f9c6c; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "9") { //darcula
    $codeblocktheme = "/* Darcula color scheme from the JetBrains family of IDEs */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #2b2b2b; } .hljs { color: #bababa; } .hljs-strong, .hljs-emphasis { color: #a8a8a2; } .hljs-bullet, .hljs-quote, .hljs-link, .hljs-number, .hljs-regexp, .hljs-literal { color: #6896ba; } .hljs-code, .hljs-selector-class { color: #a6e22e; } .hljs-emphasis { font-style: italic; } .hljs-keyword, .hljs-selector-tag, .hljs-section, .hljs-attribute, .hljs-name, .hljs-variable { color: #cb7832; } .hljs-params { color: #b9b9b9; } .hljs-string { color: #6a8759; } .hljs-subst, .hljs-type, .hljs-built_in, .hljs-builtin-name, .hljs-symbol, .hljs-selector-id, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-template-tag, .hljs-template-variable, .hljs-addition { color: #e0c46c; } .hljs-comment, .hljs-deletion, .hljs-meta { color: #7f7f7f; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "10") { //far
    $codeblocktheme = "/* FAR Style (c) MajestiC <majestic2k@gmail.com> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #000080; } .hljs, .hljs-subst { color: #0ff; } .hljs-string, .hljs-attribute, .hljs-symbol, .hljs-bullet, .hljs-built_in, .hljs-builtin-name, .hljs-template-tag, .hljs-template-variable, .hljs-addition { color: #ff0; } .hljs-keyword, .hljs-selector-tag, .hljs-section, .hljs-type, .hljs-name, .hljs-selector-id, .hljs-selector-class, .hljs-variable { color: #fff; } .hljs-comment, .hljs-quote, .hljs-doctag, .hljs-deletion { color: #888; } .hljs-number, .hljs-regexp, .hljs-literal, .hljs-link { color: #0f0; } .hljs-meta { color: #008080; } .hljs-keyword, .hljs-selector-tag, .hljs-title, .hljs-section, .hljs-name, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "11") { //github
    $codeblocktheme = "/* github.com style (c) Vasily Polovnyov <vast@whiteants.net> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; color: #333; background: #f8f8f8; } .hljs-comment, .hljs-quote { color: #998; font-style: italic; } .hljs-keyword, .hljs-selector-tag, .hljs-subst { color: #333; font-weight: bold; } .hljs-number, .hljs-literal, .hljs-variable, .hljs-template-variable, .hljs-tag .hljs-attr { color: #008080; } .hljs-string, .hljs-doctag { color: #d14; } .hljs-title, .hljs-section, .hljs-selector-id { color: #900; font-weight: bold; } .hljs-subst { font-weight: normal; } .hljs-type, .hljs-class .hljs-title { color: #458; font-weight: bold; } .hljs-tag, .hljs-name, .hljs-attribute { color: #000080; font-weight: normal; } .hljs-regexp, .hljs-link { color: #009926; } .hljs-symbol, .hljs-bullet { color: #990073; } .hljs-built_in, .hljs-builtin-name { color: #0086b3; } .hljs-meta { color: #999; font-weight: bold; } .hljs-deletion { background: #fdd; } .hljs-addition { background: #dfd; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "12") { //googlecode
    $codeblocktheme = "/* Google Code style (c) Aahan Krish <geekpanth3r@gmail.com> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: white; color: black; } .hljs-comment, .hljs-quote { color: #800; } .hljs-keyword, .hljs-selector-tag, .hljs-section, .hljs-title, .hljs-name { color: #008; } .hljs-variable, .hljs-template-variable { color: #660; } .hljs-string, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-regexp { color: #080; } .hljs-literal, .hljs-symbol, .hljs-bullet, .hljs-meta, .hljs-number, .hljs-link { color: #066; } .hljs-title, .hljs-doctag, .hljs-type, .hljs-attr, .hljs-built_in, .hljs-builtin-name, .hljs-params { color: #606; } .hljs-attribute, .hljs-subst { color: #000; } .hljs-formula { background-color: #eee; font-style: italic; } .hljs-selector-id, .hljs-selector-class { color: #9B703F } .hljs-addition { background-color: #baeeba; } .hljs-deletion { background-color: #ffc8bd; } .hljs-doctag, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "13") { //grayscale
    $codeblocktheme = "/* grayscale style (c) MY Sun <simonmysun@gmail.com> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; color: #333; background: #fff; } .hljs-comment, .hljs-quote { color: #777; font-style: italic; } .hljs-keyword, .hljs-selector-tag, .hljs-subst { color: #333; font-weight: bold; } .hljs-number, .hljs-literal { color: #777; } .hljs-string, .hljs-doctag, .hljs-formula { color: #333; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAJ0lEQVQIW2O8e/fufwYGBgZBQUEQxcCIIfDu3Tuwivfv30NUoAsAALHpFMMLqZlPAAAAAElFTkSuQmCC) repeat; } .hljs-title, .hljs-section, .hljs-selector-id { color: #000; font-weight: bold; } .hljs-subst { font-weight: normal; } .hljs-class .hljs-title, .hljs-type, .hljs-name { color: #333; font-weight: bold; } .hljs-tag { color: #333; } .hljs-regexp { color: #333; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAICAYAAADA+m62AAAAPUlEQVQYV2NkQAN37979r6yszIgujiIAU4RNMVwhuiQ6H6wQl3XI4oy4FMHcCJPHcDS6J2A2EqUQpJhohQDexSef15DBCwAAAABJRU5ErkJggg==) repeat; } .hljs-symbol, .hljs-bullet, .hljs-link { color: #000; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAKElEQVQIW2NkQAO7d+/+z4gsBhJwdXVlhAvCBECKwIIwAbhKZBUwBQA6hBpm5efZsgAAAABJRU5ErkJggg==) repeat; } .hljs-built_in, .hljs-builtin-name { color: #000; text-decoration: underline; } .hljs-meta { color: #999; font-weight: bold; } .hljs-deletion { color: #fff; background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAADCAYAAABS3WWCAAAAE0lEQVQIW2MMDQ39zzhz5kwIAQAyxweWgUHd1AAAAABJRU5ErkJggg==) repeat; } .hljs-addition { color: #000; background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAYAAADgkQYQAAAALUlEQVQYV2N89+7dfwYk8P79ewZBQUFkIQZGOiu6e/cuiptQHAPl0NtNxAQBAM97Oejj3Dg7AAAAAElFTkSuQmCC) repeat; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "14") { //hybrid
    $codeblocktheme = "/* vim-hybrid theme by w0ng (https://github.com/w0ng/vim-hybrid) */ /*background color*/ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #1d1f21; } /*selection color*/ .hljs::selection, .hljs span::selection { background: #373b41; } .hljs::-moz-selection, .hljs span::-moz-selection { background: #373b41; } /*foreground color*/ .hljs { color: #c5c8c6; } /*color: fg_yellow*/ .hljs-title, .hljs-name { color: #f0c674; } /*color: fg_comment*/ .hljs-comment, .hljs-meta, .hljs-meta .hljs-keyword { color: #707880; } /*color: fg_red*/ .hljs-number, .hljs-symbol, .hljs-literal, .hljs-deletion, .hljs-link { color: #cc6666 } /*color: fg_green*/ .hljs-string, .hljs-doctag, .hljs-addition, .hljs-regexp, .hljs-selector-attr, .hljs-selector-pseudo { color: #b5bd68; } /*color: fg_purple*/ .hljs-attribute, .hljs-code, .hljs-selector-id { color: #b294bb; } /*color: fg_blue*/ .hljs-keyword, .hljs-selector-tag, .hljs-bullet, .hljs-tag { color: #81a2be; } /*color: fg_aqua*/ .hljs-subst, .hljs-variable, .hljs-template-tag, .hljs-template-variable { color: #8abeb7; } /*color: fg_orange*/ .hljs-type, .hljs-built_in, .hljs-builtin-name, .hljs-quote, .hljs-section, .hljs-selector-class { color: #de935f; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "15") { //ocean
    $codeblocktheme = "/* Ocean Dark Theme */ /* https://github.com/gavsiu */ /* Original theme - https://github.com/chriskempson/base16 */ /* Ocean Comment */ .hljs-comment, .hljs-quote { color: #65737e; } /* Ocean Red */ .hljs-variable, .hljs-template-variable, .hljs-tag, .hljs-name, .hljs-selector-id, .hljs-selector-class, .hljs-regexp, .hljs-deletion { color: #bf616a; } /* Ocean Orange */ .hljs-number, .hljs-built_in, .hljs-builtin-name, .hljs-literal, .hljs-type, .hljs-params, .hljs-meta, .hljs-link { color: #d08770; } /* Ocean Yellow */ .hljs-attribute { color: #ebcb8b; } /* Ocean Green */ .hljs-string, .hljs-symbol, .hljs-bullet, .hljs-addition { color: #a3be8c; } /* Ocean Blue */ .hljs-title, .hljs-section { color: #8fa1b3; } /* Ocean Purple */ .hljs-keyword, .hljs-selector-tag { color: #b48ead; } .hljs { display: block; overflow-x: auto; background: #2b303b; color: #c0c5ce; padding: 0.5em; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "16") { //purebasic
    $codeblocktheme = ".hljs { /* Common set of rules required by highlight.js (don'r remove!) */ display: block; overflow-x: auto; padding: 0.5em; background: #FFFFDF; /* Half and Half (approx.) */ /* --- Uncomment to add PureBASIC native IDE styled font! font-family: Consolas; */ } .hljs, /* --- used for PureBASIC base color --- */ .hljs-type, /* --- used for PureBASIC Procedures return type --- */ .hljs-function, /* --- used for wrapping PureBASIC Procedures definitions --- */ .hljs-name, .hljs-number, .hljs-attr, .hljs-params, .hljs-subst { color: #000000; /* Black */ } .hljs-comment, /* --- used for PureBASIC Comments --- */ .hljs-regexp, .hljs-section, .hljs-selector-pseudo, .hljs-addition { color: #00AAAA; /* Persian Green (approx.) */ } .hljs-title, /* --- used for PureBASIC Procedures Names --- */ .hljs-tag, .hljs-variable, .hljs-code { color: #006666; /* Blue Stone (approx.) */ } .hljs-keyword, /* --- used for PureBASIC Keywords --- */ .hljs-class, .hljs-meta-keyword, .hljs-selector-class, .hljs-built_in, .hljs-builtin-name { color: #006666; /* Blue Stone (approx.) */ font-weight: bold; } .hljs-string, /* --- used for PureBASIC Strings --- */ .hljs-selector-attr { color: #0080FF; /* Azure Radiance (approx.) */ } .hljs-symbol, /* --- used for PureBASIC Constants --- */ .hljs-link, .hljs-deletion, .hljs-attribute { color: #924B72; /* Cannon Pink (approx.) */ } .hljs-meta, .hljs-literal, .hljs-selector-id { color: #924B72; /* Cannon Pink (approx.) */ font-weight: bold; } .hljs-strong, .hljs-name { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "17") { //solarized-dark
    $codeblocktheme = ".hljs { /* Common set of rules required by highlight.js (don'r remove!) */ display: block; overflow-x: auto; padding: 0.5em; background: #FFFFDF; /* Half and Half (approx.) */ /* --- Uncomment to add PureBASIC native IDE styled font! font-family: Consolas; */ } .hljs, /* --- used for PureBASIC base color --- */ .hljs-type, /* --- used for PureBASIC Procedures return type --- */ .hljs-function, /* --- used for wrapping PureBASIC Procedures definitions --- */ .hljs-name, .hljs-number, .hljs-attr, .hljs-params, .hljs-subst { color: #000000; /* Black */ } .hljs-comment, /* --- used for PureBASIC Comments --- */ .hljs-regexp, .hljs-section, .hljs-selector-pseudo, .hljs-addition { color: #00AAAA; /* Persian Green (approx.) */ } .hljs-title, /* --- used for PureBASIC Procedures Names --- */ .hljs-tag, .hljs-variable, .hljs-code { color: #006666; /* Blue Stone (approx.) */ } .hljs-keyword, /* --- used for PureBASIC Keywords --- */ .hljs-class, .hljs-meta-keyword, .hljs-selector-class, .hljs-built_in, .hljs-builtin-name { color: #006666; /* Blue Stone (approx.) */ font-weight: bold; } .hljs-string, /* --- used for PureBASIC Strings --- */ .hljs-selector-attr { color: #0080FF; /* Azure Radiance (approx.) */ } .hljs-symbol, /* --- used for PureBASIC Constants --- */ .hljs-link, .hljs-deletion, .hljs-attribute { color: #924B72; /* Cannon Pink (approx.) */ } .hljs-meta, .hljs-literal, .hljs-selector-id { color: #924B72; /* Cannon Pink (approx.) */ font-weight: bold; } .hljs-strong, .hljs-name { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "18") { //solarized-light
    $codeblocktheme = "/* Orginal Style from ethanschoonover.com/solarized (c) Jeremy Hull <sourdrums@gmail.com> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #fdf6e3; color: #657b83; } .hljs-comment, .hljs-quote { color: #93a1a1; } /* Solarized Green */ .hljs-keyword, .hljs-selector-tag, .hljs-addition { color: #859900; } /* Solarized Cyan */ .hljs-number, .hljs-string, .hljs-meta .hljs-meta-string, .hljs-literal, .hljs-doctag, .hljs-regexp { color: #2aa198; } /* Solarized Blue */ .hljs-title, .hljs-section, .hljs-name, .hljs-selector-id, .hljs-selector-class { color: #268bd2; } /* Solarized Yellow */ .hljs-attribute, .hljs-attr, .hljs-variable, .hljs-template-variable, .hljs-class .hljs-title, .hljs-type { color: #b58900; } /* Solarized Orange */ .hljs-symbol, .hljs-bullet, .hljs-subst, .hljs-meta, .hljs-meta .hljs-keyword, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-link { color: #cb4b16; } /* Solarized Red */ .hljs-built_in, .hljs-deletion { color: #dc322f; } .hljs-formula { background: #eee8d5; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "19") { //tomorrow
    $codeblocktheme = "/* http://jmblog.github.com/color-themes-for-google-code-highlightjs */ /* Tomorrow Comment */ .hljs-comment, .hljs-quote { color: #8e908c; } /* Tomorrow Red */ .hljs-variable, .hljs-template-variable, .hljs-tag, .hljs-name, .hljs-selector-id, .hljs-selector-class, .hljs-regexp, .hljs-deletion { color: #c82829; } /* Tomorrow Orange */ .hljs-number, .hljs-built_in, .hljs-builtin-name, .hljs-literal, .hljs-type, .hljs-params, .hljs-meta, .hljs-link { color: #f5871f; } /* Tomorrow Yellow */ .hljs-attribute { color: #eab700; } /* Tomorrow Green */ .hljs-string, .hljs-symbol, .hljs-bullet, .hljs-addition { color: #718c00; } /* Tomorrow Blue */ .hljs-title, .hljs-section { color: #4271ae; } /* Tomorrow Purple */ .hljs-keyword, .hljs-selector-tag { color: #8959a8; } .hljs { display: block; overflow-x: auto; background: white; color: #4d4d4c; padding: 0.5em; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "20") { //vs
    $codeblocktheme = "/* Visual Studio-like style based on original C# coloring by Jason Diamond <jason@diamond.name> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: white; color: black; } .hljs-comment, .hljs-quote, .hljs-variable { color: #008000; } .hljs-keyword, .hljs-selector-tag, .hljs-built_in, .hljs-name, .hljs-tag { color: #00f; } .hljs-string, .hljs-title, .hljs-section, .hljs-attribute, .hljs-literal, .hljs-template-tag, .hljs-template-variable, .hljs-type, .hljs-addition { color: #a31515; } .hljs-deletion, .hljs-selector-attr, .hljs-selector-pseudo, .hljs-meta { color: #2b91af; } .hljs-doctag { color: #808080; } .hljs-attr { color: #f00; } .hljs-symbol, .hljs-bullet, .hljs-link { color: #00b0e8; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "21") { //xcode
    $codeblocktheme = "/* XCode style (c) Angel Garcia <angelgarcia.mail@gmail.com> */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #fff; color: black; } .hljs-comment, .hljs-quote { color: #006a00; } .hljs-keyword, .hljs-selector-tag, .hljs-literal { color: #aa0d91; } .hljs-name { color: #008; } .hljs-variable, .hljs-template-variable { color: #660; } .hljs-string { color: #c41a16; } .hljs-regexp, .hljs-link { color: #080; } .hljs-title, .hljs-tag, .hljs-symbol, .hljs-bullet, .hljs-number, .hljs-meta { color: #1c00cf; } .hljs-section, .hljs-class .hljs-title, .hljs-type, .hljs-attr, .hljs-built_in, .hljs-builtin-name, .hljs-params { color: #5c2699; } .hljs-attribute, .hljs-subst { color: #000; } .hljs-formula { background-color: #eee; font-style: italic; } .hljs-addition { background-color: #baeeba; } .hljs-deletion { background-color: #ffc8bd; } .hljs-selector-id, .hljs-selector-class { color: #9b703f; } .hljs-doctag, .hljs-strong { font-weight: bold; } .hljs-emphasis { font-style: italic; } "; 
} 
elseif ($mybb->settings['dcodesyntax_theme'] == "22") { //zenburn
    $codeblocktheme = "/* Zenburn style from voldmar.ru (c) Vladimir Epifanov <voldmar@voldmar.ru> based on dark.css by Ivan Sagalaev */ .hljs { display: block; overflow-x: auto; padding: 0.5em; background: #3f3f3f; color: #dcdcdc; } .hljs-keyword, .hljs-selector-tag, .hljs-tag { color: #e3ceab; } .hljs-template-tag { color: #dcdcdc; } .hljs-number { color: #8cd0d3; } .hljs-variable, .hljs-template-variable, .hljs-attribute { color: #efdcbc; } .hljs-literal { color: #efefaf; } .hljs-subst { color: #8f8f8f; } .hljs-title, .hljs-name, .hljs-selector-id, .hljs-selector-class, .hljs-section, .hljs-type { color: #efef8f; } .hljs-symbol, .hljs-bullet, .hljs-link { color: #dca3a3; } .hljs-deletion, .hljs-string, .hljs-built_in, .hljs-builtin-name { color: #cc9393; } .hljs-addition, .hljs-comment, .hljs-quote, .hljs-meta { color: #7f9f7f; } .hljs-emphasis { font-style: italic; } .hljs-strong { font-weight: bold; } "; 
} 

if ($mybb->settings['dcodesyntax_activate'] == "1") { 
 $dcodesyntax = "<style>" . $codeblocktheme . "</style>" . $highlighterjs . $embedjs;
} else {
   $dcodesyntax = "";
} 

}

?>
