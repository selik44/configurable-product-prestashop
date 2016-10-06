<?php /* Smarty version Smarty-3.1.19, created on 2016-10-05 16:24:36
         compiled from "/home/dar1n196/public_html/compbuilder-prestashop/admin2579coman/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21054845457f50d2432a5e8-16249085%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '27b160f066d7adfb27312862ddc17e21acf30bef' => 
    array (
      0 => '/home/dar1n196/public_html/compbuilder-prestashop/admin2579coman/themes/default/template/content.tpl',
      1 => 1473167112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21054845457f50d2432a5e8-16249085',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57f50d24331965_49373769',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57f50d24331965_49373769')) {function content_57f50d24331965_49373769($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
