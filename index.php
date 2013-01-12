<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * MaxSite CMS
 * (c) http://max-3000.com/
 */



# функция автоподключения плагина
function maxsite_author_autoload()
{
	mso_hook_add( 'admin_init', 'maxsite_author_admin_init'); # хук на админку
    if(mso_segment(1) == 'api') mso_hook_add('init', 'maxsite_author_server_init');
}

# функция выполняется при активации (вкл) плагина
function maxsite_author_activate($args = array())
{	
	mso_create_allow('maxsite_author_client_edit', t('Админ-доступ к каталогу плагинов и тем'));
	mso_create_allow('maxsite_author_server_edit', t('Админ-доступ к серверу каталога плагинов и тем'));
	return $args;
}


# функция выполняется при деинсталяции плагина
function maxsite_author_uninstall($args = array())
{	
	mso_remove_allow('maxsite_author_client_edit'); // удалим созданные разрешения
	mso_remove_allow('maxsite_author_server_edit'); // удалим созданные разрешения
	return $args;
}

# функция выполняется при указаном хуке admin_init
function maxsite_author_admin_init($args = array()) 
{
    if ( mso_check_allow('maxsite_author_client_edit') ) 
    {
        $this_plugin_url = 'maxsite_author_client'; // url и hook
        mso_admin_menu_add('plugins', $this_plugin_url, t('Каталог тем и расширений Maxsite CMS'));
        mso_admin_url_hook ($this_plugin_url, 'maxsite_author_client_admin_page');
    }
    if ( mso_check_allow('maxsite_author_server_edit') ) 
    {
        $this_plugin_url = 'maxsite_author_server'; // url и hook
        mso_admin_menu_add('plugins', $this_plugin_url, t(''));
        mso_admin_url_hook ($this_plugin_url, 'maxsite_author_server_admin_page');
    }
	
	return $args;
}

# функция вызываемая при хуке, указанном в mso_admin_url_hook
function maxsite_author_server_admin_page($args = array()) 
{
	if ( !mso_check_allow('maxsite_author_server_edit') ) 
	{
		echo t('Доступ запрещен');
		return $args;
	}

	# выносим админские функции отдельно в файл
	mso_hook_add_dinamic( 'mso_admin_header', ' return $args . "' . t('maxsite_author') . '"; ' );
	mso_hook_add_dinamic( 'admin_title', ' return "' . t('maxsite_author') . ' - " . $args; ' );
	require(getinfo('plugins_dir') . 'maxsite_author/server.php');
}

function maxsite_author_client_admin_page($args = array()) 
{
	if ( !mso_check_allow('maxsite_author_client_edit') ) 
	{
		echo t('Доступ запрещен');
		return $args;
	}

	# выносим админские функции отдельно в файл
	mso_hook_add_dinamic( 'mso_admin_header', ' return $args . "' . t('maxsite_author') . '"; ' );
	mso_hook_add_dinamic( 'admin_title', ' return "' . t('maxsite_author') . ' - " . $args; ' );
	require(getinfo('plugins_dir') . 'maxsite_author/client.php');
}

function maxsite_author_server_init() {
    require(getinfo('plugins_dir') . 'maxsite_author/api.php');
}

# end file
