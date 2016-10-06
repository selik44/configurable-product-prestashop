<?php /*%%SmartyHeaderCode:82897948557f50b91156358-24719053%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e0b2c10d80004888e8d1cd0981efdd2e02dbe19' => 
    array (
      0 => '/home/dar1n196/public_html/compbuilder-prestashop/themes/default-bootstrap/modules/blocksearch/blocksearch-top.tpl',
      1 => 1473167114,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '82897948557f50b91156358-24719053',
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57f58d6f750508_23007373',
  'has_nocache_code' => false,
  'cache_lifetime' => 31536000,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f58d6f750508_23007373')) {function content_57f58d6f750508_23007373($_smarty_tpl) {?><!-- Block search module TOP -->
<div id="search_block_top" class="col-sm-4 clearfix">
	<form id="searchbox" method="get" action="//compbuilder-prestashop.darinx.com/en/search" >
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input class="search_query form-control" type="text" id="search_query_top" name="search_query" placeholder="Search" value="" />
		<button type="submit" name="submit_search" class="btn btn-default button-search">
			<span>Search</span>
		</button>
	</form>
</div>
<!-- /Block search module TOP --><?php }} ?>
