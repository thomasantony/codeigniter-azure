codeigniter-azure
=================

A Codeigniter library that wraps over Microsoft's phpAzure library with support for CI's Caching system

Caching is available only for the Table Storage driver at present. 

In order to use the library, copy the files within the libraries folder to your application/libraries folder.

Within your config.php ( or in any other config file which you load ), create the following keys

	$config['azure_storage_account_name'] = 'myaccountname'; // Your Storage account name
	$config['azure_storage_account_key']  = 'aO7UcEnYjfUugiSdsKKpD4HnhjPyNOAICRAeM65MBeRAb3vOJXVb9dAByzwgiim++suCDrFSN0P7H5e0ULEgrw=='; // Your account key

	// Usually the ones below can stay the same unless you are gonna use storage emulator
	$config['azure_table_host'] = 'table.core.windows.net';
	$config['azure_blob_host'] = 'blob.core.windows.net';
	$config['azure_queue_host'] = 'queue.core.windows.net';

	$config['azure_storage_useproxy'] = false;
	$config['azure_storage_proxy'] =    '';
	$config['azure_storage_proxy_port'] = '8080';

	// The primary and backup caching adapters to use for caching azure queries
	$config['azure_caching_adapter'] = 'apc';
	$config['azure_caching_backup']  = 'file';
	$config['azure_caching_interval']  = 3600;   // 1 hour

In order to use the library, call this within your controller or model ( preferably your model )
If you are going to define any entity classes for azure table storage, it can be done in a separate PHP file in the same folder as the model and can be loaded like so:

	$this->load->library('azure');
	// require('entities.php'); // Only needed if you are going to define entity classes and have created this file with the classes in it
	
Please check the phpAzure documentation to know more about defining entity classes here:
<https://github.com/windowsazure/azure-sdk-for-php>

Have fun! :)