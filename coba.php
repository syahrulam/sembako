<input class="form-control id_item" type="text" name="id_item_${itemCounter}" style="display:none"/>
<div class="col-md-3">
    <div class="form-group">
        <label for="nama_item" class="text-dark">Nama Item<span class='red'> *</span></label>
        <input class="form-control nama_item" type="text" name="nama_item" required/>
        <div class="result"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
    function handleItemSearch(inputElement) {
        var searchTerm = inputElement.val();
        var resultItem = inputElement.parent().find(".result");

        if (searchTerm !== "") {
            $.ajax({
                type: "POST",
                url: "search_item.php",
                data: {
                    searchTerm: searchTerm
                },
                success: function(data) {
                    resultItem.html(data);
                }
            });
        } else {
            resultItem.empty();
        }
    }

    $(document).on("input", ".nama_item", function() {
        handleItemSearch($(this));
    });

    $(document).on("click", ".result li", function() {
        var selectedItem = $(this).text();
        var itemContainer = $(this).closest(".form-group");
        itemContainer.find(".nama_item").val(selectedItem);

        $.ajax({
            type: "POST",
            url: "get_quantity.php",
            data: {
                selectedItem: selectedItem
            },
            success: function(response) {
                var data = JSON.parse(response);
                // Assuming you have a field named 'quantity' in your item table
                var quantity = data.quantity; 
                // Assuming you have an input field for quantity with class 'quantity'
                itemContainer.find(".quantity").val(quantity);
            }
        });

        itemContainer.find(".result").empty();
    });
});

</script>