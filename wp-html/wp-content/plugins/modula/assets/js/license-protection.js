jQuery(document).ready(function ($) {

    $(document).on('contextmenu dragstart', function () {
        return false;
    });

    /**
     * Monitor which keys are being pressed
     */
    var modula_protection_keys = {
        'alt': false,
        'shift': false,
        'meta': false,
    };

    $(document).on('keydown', function (e) {

        // Alt Key Pressed
        if (e.altKey) {
            modula_protection_keys.alt = true;
        }

        // Shift Key Pressed
        if (e.shiftKey) {
            modula_protection_keys.shift = true;
        }

        // Meta Key Pressed (e.g. Mac Cmd)
        if (e.metaKey) {
            modula_protection_keys.meta = true;
        }

        if (e.ctrlKey && '85' == e.keyCode) {
            modula_protection_keys.ctrl = true;
        }


    });
    $(document).on('keyup', function (e) {

        // Alt Key Released
        if (!e.altKey) {
            modula_protection_keys.alt = false;
        }

        // Shift Key Released
        if (e.shiftKey) {
            modula_protection_keys.shift = false;
        }

        // Meta Key Released (e.g. Mac Cmd)
        if (!e.metaKey) {
            modula_protection_keys.meta = false;
        }

        if (!e.ctrlKey) {
            modula_protection_keys.ctrl = false;
        }

    });

    /**
     * Prevent automatic download when Alt + left click
     */
    $(document).on('click', '#modula_pro_license_key', function (e) {
        if (modula_protection_keys.alt || modula_protection_keys.shift || modula_protection_keys.meta || modula_protection_keys.ctrl) {
            // User is trying to download - stop!
            e.preventDefault();
            return false;
        }
    });

    $(document).on('keydown click',function(e){
        if (modula_protection_keys.ctrl) {
            // User is trying to view source
            e.preventDefault();
            return false;
        }
    });
});