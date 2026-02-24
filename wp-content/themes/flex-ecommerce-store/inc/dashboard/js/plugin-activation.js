jQuery(document).ready(function ($) {
    // Attach click event to the dismiss button
    $(document).on('click', '.notice[data-notice="get-start"] button.notice-dismiss', function () {
        // Dismiss the notice via AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'flex_ecommerce_store_dismissed_notice',
            },
            success: function () {
                // Remove the notice on success
                $('.notice[data-notice="example"]').remove();
            }
        });
    });
});

// Flex Importer plugin activation for Getstarted
document.addEventListener('DOMContentLoaded', function () {
    const flex_ecommerce_store_button = document.getElementById('install-activate-button');
    if (!flex_ecommerce_store_button) return;

    flex_ecommerce_store_button.addEventListener('click', function (e) {
        e.preventDefault();

        const flex_ecommerce_store_redirectUrl = flex_ecommerce_store_button.getAttribute('data-redirect');

        // Step 1: Check if plugin is already active
        const flex_ecommerce_store_checkData = new FormData();
        flex_ecommerce_store_checkData.append('action', 'check_flex_import_activation');

        fetch(installFlexData.ajaxurl, {
            method: 'POST',
            body: flex_ecommerce_store_checkData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.active) {
                // Plugin is already active → just redirect
                window.location.href = flex_ecommerce_store_redirectUrl;
            } else {
                // Not active → proceed with install + activate
                flex_ecommerce_store_button.textContent = 'Installing & Activating...';

                const flex_ecommerce_store_installData = new FormData();
                flex_ecommerce_store_installData.append('action', 'install_and_activate_flex_import_plugin_lite');
                flex_ecommerce_store_installData.append('_ajax_nonce', installFlexData.nonce);

                fetch(installFlexData.ajaxurl, {
                    method: 'POST',
                    body: flex_ecommerce_store_installData,
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = flex_ecommerce_store_redirectUrl;
                    } else {
                        alert('Activation error: ' + (res.data?.message || 'Unknown error'));
                        flex_ecommerce_store_button.textContent = 'Try Again';
                    }
                })
                .catch(error => {
                    alert('Request failed: ' + error.message);
                    flex_ecommerce_store_button.textContent = 'Try Again';
                });
            }
        })
        .catch(error => {
            alert('Check request failed: ' + error.message);
        });
    });
});

// Banner Notice
document.addEventListener('DOMContentLoaded', function () {
    const flex_ecommerce_store_button_banner = document.getElementById('install-activate-button');
    if (!flex_ecommerce_store_button_banner) return;

    flex_ecommerce_store_button_banner.addEventListener('click', function (e) {
        e.preventDefault();

        const flex_ecommerce_store_redirectUrl = flex_ecommerce_store_button_banner.getAttribute('data-redirect');

        // Step 1: Check if plugin is already active
        const flex_ecommerce_store_checkData = new FormData();
        flex_ecommerce_store_checkData.append('action', 'check_flex_import_activation');

        fetch(installFlexData.ajaxurl, {
            method: 'POST',
            body: flex_ecommerce_store_checkData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.active) {
                // Plugin is already active → just redirect
                window.location.href = flex_ecommerce_store_redirectUrl;
            } else {
                // Not active → proceed with install + activate
                flex_ecommerce_store_button_banner.textContent = 'Installing & Activating...';

                const flex_ecommerce_store_installData = new FormData();
                flex_ecommerce_store_installData.append('action', 'install_and_activate_flex_import_plugin_lite');
                flex_ecommerce_store_installData.append('_ajax_nonce', installFlexData.nonce);

                fetch(installFlexData.ajaxurl, {
                    method: 'POST',
                    body: flex_ecommerce_store_installData,
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = flex_ecommerce_store_redirectUrl;
                    } else {
                        alert('Activation error: ' + (res.data?.message || 'Unknown error'));
                        flex_ecommerce_store_button_banner.textContent = 'Try Again';
                    }
                })
                .catch(error => {
                    alert('Request failed: ' + error.message);
                    flex_ecommerce_store_button_banner.textContent = 'Try Again';
                });
            }
        })
        .catch(error => {
            alert('Check request failed: ' + error.message);
        });
    });
});