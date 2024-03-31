<div class="item-container">
<div class="col-md-3">
    <div class="form-group">
        <label for="nama_item" class="text-dark">Nama Item<span class='red'> *</span></label>
        <input class="form-control nama_item" type="text" name="nama_item" required />


        <input class="form-control jenis_satuan_besar" type="text" name="jenis_satuan_besar" required />
        <input class="form-control jenis_satuan_kecil" type="text" name="jenis_satuan_kecil" required />
        <div class="result"></div>

        <input type="text" class="harga_satuan">
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle item search
            function handleItemSearch(inputElement) {
                var searchTerm = inputElement.val();
                var resultContainer = inputElement.parent().find(".result");

                if (searchTerm !== "") {
                    $.ajax({
                        type: "POST",
                        url: "search_item.php",
                        data: {
                            searchTerm: searchTerm
                        },
                        success: function(data) {
                            resultContainer.html(data);
                        }
                    });
                } else {
                    resultContainer.empty();
                }
            }

            // Event handler for input on nama_item
            $(document).on("input", ".nama_item", function() {
                handleItemSearch($(this));
            });

            // Event handler for selecting an item
            $(document).on("click", ".result li", function() {
                var selectedItem = $(this).text();
                var itemContainer = $(this).closest(".item-container");

                // Update the input field with the selected item
                itemContainer.find(".nama_item").val(selectedItem);

                // Perform additional AJAX request for the selected item details
                $.ajax({
                    type: "POST",
                    url: "get_quantity.php",
                    data: {
                        selectedItem: selectedItem
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        itemContainer.find(".id_item").val(data.id_item);
                        itemContainer.find(".jenis_satuan_besar").val(data.jenis_satuan_besar);
                        itemContainer.find(".jenis_satuan_kecil").val(data.jenis_satuan_kecil);


                    }
                });
                itemContainer.find(".result").empty();
            });
        });
    </script>