$(document).ready(function(){
//our function wrapper.

    var self = this;

    var initMyAssociationsAutocomplete = function (){
//initialize the autocomplete that will point to the default ajax_products_list page (it returns the products by id+name)
        $('#product_autocomplete_input_association')
            .autocomplete('ajax_products_list.php?exclude_packs=0&excludeVirtuals=0', {
                minChars: 1,
                autoFill: true,
                max:20,
                matchContains: true,
                mustMatch:true,
                scroll:false,
                cacheLength:0,
                formatItem: function(item) {
                    return item[1]+' - '+item[0];
                }
            }).result(addAssociation);
        //as an option we will add a function to exclude a product if it's already in the list
        $('#product_autocomplete_input_association').setOptions({
            extraParams: {
                excludeIds : self.getAssociationsIds()
            }
        });
    };

    //function to exclude a product if it exists in the list
    this.getAssociationsIds = function()
    {
        if ($('#inputMyAssociations').val() === undefined)
             	return id_product;
        return id_product + ',' + $('#inputMyAssociations').val().replace(/\-/g,',');
    }
    //function to add a new association, adds it in the hidden input and also as a visible div, with a button to delete the association any time.
    var addAssociation = function(event, data, formatted)
    {
        if (data == null)
            return false;
        var productId = data[1];
        var productName = data[0];

        var $divAccessories = $('#divCrossSellers');
        var $inputAccessories = $('#inputMyAssociations');
        var $nameAccessories = $('#nameMyAssociations');

        /* delete product from select + add product line to the div, input_name, input_ids elements */
        $divAccessories.html($divAccessories.html() + '<div class="form-control-static"><button type="button" class="delAssociation btn btn-default" name="' + productId + '"><i class="icon-remove text-danger"></i></button>&nbsp;'+ productName +'</div>');
        $nameAccessories.val($nameAccessories.val() + productName + '¤');
        $inputAccessories.val($inputAccessories.val() + productId + '-');
        $('#product_autocomplete_input_association').val('');
        $('#product_autocomplete_input_association').setOptions({
            extraParams: {excludeIds : self.getAssociationsIds()}
        });
    };
    //the function to delete an associations, delete it from both the hidden inputs and the visible div list.
    this.delAssociations = function(id)
    {
        var div = getE('divCrossSellers');
        var input = getE('inputAccessories');
        var name = getE('nameAccessories');

        console.log(id);

        // Cut hidden fields in array
        var inputCut = input.value.split('-');
        var nameCut = name.value.split('¤');

        if (inputCut.length != nameCut.length)
            return jAlert('Bad size');

        // Reset all hidden fields
        input.value = '';
        name.value = '';
        div.innerHTML = '';
        for (i in inputCut)
        {
            // If empty, error, next
            if (!inputCut[i] || !nameCut[i])
                continue ;

            // Add to hidden fields no selected products OR add to select field selected product
            if (inputCut[i] != id)
            {
                input.value += inputCut[i] + '-';
                name.value += nameCut[i] + '¤';
                div.innerHTML += '<div class="form-control-static"><button type="button" class="delAssociation btn btn-default" name="' + inputCut[i] +'"><i class="icon-remove text-danger"></i></button>&nbsp;' + nameCut[i] + '</div>';
            }
            else
                $('#selectAccessories').append('<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>');
        }

        $('#product_autocomplete_input_association').setOptions({
            extraParams: {excludeIds : self.getAssociationsIds()}
        });
    };

    //finally initialize the function we have written above and create all the binds.
    initMyAssociationsAutocomplete();
//live delegation of the deletion button to our delete function, this will allow us to delete also any element added after the dom creation with the ajax autocomplete.
    $('#divCrossSellers').delegate('.delAssociation', 'click', function(){
        self.delAssociations($(this).attr('name'));
    });

    // this.onReady = function(){
    //     initMyAssociationsAutocomplete();
    //     $('#divCrossSellers').delegate('.delAssociation', 'click', function(){
    //         self.delAssociations($(this).attr('name'));
    //     });
    // };

});