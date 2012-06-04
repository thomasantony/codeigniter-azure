<?php
/**
 * A Windows Azure helper library for Code Igniter
 *
 * @author Thomas Antony
 * @website http://www.thomasantony.net
 * 
 */
define ('AZURE_CACHING_ENABLED',true);
class Azure {
    function __construct()
    {
        require_once ('Microsoft/AutoLoader.php');        
        $this->CI =& get_instance();
    }
    
    public function get_table_storage()
    {
        if(AZURE_CACHING_ENABLED)
        {
            require_once('Table_Cached.php');
            return new Microsoft_WindowsAzure_Storage_Table_Cached(
                $this->CI->config->item('azure_table_host'), 
            	$this->CI->config->item('azure_storage_account_name'),
            	$this->CI->config->item('azure_storage_account_key')
            );
        }else{
            return new Microsoft_WindowsAzure_Storage_Table(
                $this->CI->config->item('azure_table_host'), 
            	$this->CI->config->item('azure_storage_account_name'),
            	$this->CI->config->item('azure_storage_account_key')
            );
        }
    }
    public function get_blob_storage()
    {
        return new Microsoft_WindowsAzure_Storage_Blob(
        	$this->CI->config->item('azure_blob_host'), 
        	$this->CI->config->item('azure_storage_account_name'),
        	$this->CI->config->item('azure_storage_account_key')
        //	false, 
        //	Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract::retryN(10, 250)
        );
    }
    public function get_queue_storage()
    {
        return new Microsoft_WindowsAzure_Storage_Queue(
            $this->CI->config->item('azure_queue_host'), 
        	$this->CI->config->item('azure_storage_account_name'),
        	$this->CI->config->item('azure_storage_account_key')
        );
    }
    
}