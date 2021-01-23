<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ctrl_s_ext {
    
    function __construct()
    {
        $this->version = "3.0";
    }

    function activate_extension()
    {
        ee()->db->insert('extensions', array(
            'class'    => __CLASS__,
            'method'   => 'addSave',
            'hook'     => 'cp_js_end',
            'settings' => '',
            'priority' => 10,
            'version'  => $this->version,
            'enabled'  => 'y'
        ));
    }
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return false;
        }
       
        ee()->db->where('class', __CLASS__);
        ee()->db->update(
            'extensions',
            array('version' => $this->version)
        );
    }

    function disable_extension()
    {
        ee()->db->where('class', __CLASS__)
            ->delete('extensions');
    }

    
    function addSave(){

        if (version_compare(APP_VER, '5.0.0', '>')) {

            $js = "(function () {
                const entryform = document.querySelector('.ee-main__content');
                const submitbutton = entryform.querySelector('button[value=save]');
                const savenew = entryform.querySelector('button[value=save_and_new]');
                const saveclose = entryform.querySelector('button[value=save_and_close]');
                
                document.addEventListener('keydown', function (e){          
                    // control/command S
                    if(e.key === 's' && (e.ctrlKey || e.metaKey)) {
                        e.preventDefault();           
                        submitbutton.click();
                    }
                    if(e.key === 'd' && (e.ctrlKey || e.metaKey)) {                
                        e.preventDefault();
                        savenew.click();
                    }
                    if(e.key === 'g' && (e.ctrlKey || e.metaKey)) {    
                        e.preventDefault();
                        saveclose.click();
                    }                                

                });
            }());                
            ";

        }

        $other_extensions_data = ee()->extensions->last_call !== false ? ee()->extensions->last_call : '';

        return $other_extensions_data . $js;
    }

}
