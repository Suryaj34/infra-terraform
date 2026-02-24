(function(api) {

    api.sectionConstructor['ecommerce-gadget-store-upsell'] = api.Section.extend({
        attachEvents: function() {},
        isContextuallyActive: function() {
            return true;
        }
    });

})(wp.customize);