jQuery(document).ready(function ($) {
    // Attach click event to the dismiss button
    $(document).on('click', '.notice[data-notice="get-start"] button.notice-dismiss', function () {
        // Dismiss the notice via AJAX
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'ecommerce_gadget_store_dismissed_notice',
            },
            success: function () {
                // Remove the notice on success
                $('.notice[data-notice="example"]').remove();
            }
        });
    });
});

// Plugin – AI Content Writer plugin activation
document.addEventListener('DOMContentLoaded', function () {
    const ecommerce_gadget_store_button = document.getElementById('install-activate-button');

    if (!ecommerce_gadget_store_button) return;

    ecommerce_gadget_store_button.addEventListener('click', function (e) {
        e.preventDefault();

        const ecommerce_gadget_store_redirectUrl = ecommerce_gadget_store_button.getAttribute('data-redirect');

        // Step 1: Check if plugin is already active
        const ecommerce_gadget_store_checkData = new FormData();
        ecommerce_gadget_store_checkData.append('action', 'check_plugin_activation');

        fetch(installPluginData.ajaxurl, {
            method: 'POST',
            body: ecommerce_gadget_store_checkData,
        })
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.active) {
                // Plugin is already active → just redirect
                window.location.href = ecommerce_gadget_store_redirectUrl;
            } else {
                // Not active → proceed with install + activate
                ecommerce_gadget_store_button.textContent = 'Installing & Activating...';

                const ecommerce_gadget_store_installData = new FormData();
                ecommerce_gadget_store_installData.append('action', 'install_and_activate_required_plugin');
                ecommerce_gadget_store_installData.append('_ajax_nonce', installPluginData.nonce);

                fetch(installPluginData.ajaxurl, {
                    method: 'POST',
                    body: ecommerce_gadget_store_installData,
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        window.location.href = ecommerce_gadget_store_redirectUrl;
                    } else {
                        alert('Activation error: ' + (res.data?.message || 'Unknown error'));
                        ecommerce_gadget_store_button.textContent = 'Try Again';
                    }
                })
                .catch(error => {
                    alert('Request failed: ' + error.message);
                    ecommerce_gadget_store_button.textContent = 'Try Again';
                });
            }
        })
        .catch(error => {
            alert('Check request failed: ' + error.message);
        });
    });
});
