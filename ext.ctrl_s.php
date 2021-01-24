<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Ctrl_s_ext {
    
    function __construct()
    {
        $this->version = "3.1";
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

                // Saving.
                const entryform = document.querySelector('.ee-main__content');
                const submitbutton = entryform.querySelector('button[value=save]');
                const savenew = entryform.querySelector('button[value=save_and_new]');
                const saveclose = entryform.querySelector('button[value=save_and_close]');

                // Use numbers to change tabs.
                const tabs = document.querySelectorAll('.js-tab-button');
                var activeTab = 0;
                
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

                    /*
                    // Control + Tab Number to jump to tabs.
                    if ( typeof(e.key*1) === 'number' && (e.key = 'Control') ) {
                        if ( (e.key*1) < tabs.length) {
                            e.preventDefault();
                            tabs.item(e.key*1-1).click();
                        }
                    }*/

                    // Control + Arrow Left/Right to cycle tabs.
                    if (e.key == 'ArrowLeft' && (e.ctrlKey || e.metaKey)) {
                        e.preventDefault();
                        if (activeTab > 0) {
                            activeTab = activeTab - 1;
                        } else {
                            activeTab = tabs.length - 1;
                        }
                        tabs.item(activeTab).click();
                        tab_area = document.querySelector('.t-'+activeTab);
                        const title = tab_area.querySelector('input[type=text]');
                        title.focus();                        
                    }
                    if (e.key == 'ArrowRight' && (e.ctrlKey || e.metaKey)) {
                        e.preventDefault();
                        if (activeTab < tabs.length - 1) {
                            activeTab = activeTab + 1;
                        } else {
                            activeTab = 0;
                        }
                        tabs.item(activeTab).click();
                        tab_area = document.querySelector('.t-'+activeTab);
                        const title = tab_area.querySelector('input[type=text]');
                        title.focus();                        
                    }

                });

                // Jump to the first input with text on page load.
                const title = entryform.querySelector('input[type=text]');
                title.focus();

            }());                
            ";

        }

        $other_extensions_data = ee()->extensions->last_call !== false ? ee()->extensions->last_call : '';

        return $other_extensions_data . $js;
    }

}