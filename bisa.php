<!DOCTYPE html>
<html>
<head>
    <title>Form Pembelian Item</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="item-container">
    <div class="col-md-3">
        <div class="form-group">
            <label for="nama_item" class="text-dark">Nama Item<span class='red'> *</span></label>
            <input class="form-control nama_item" type="text" name="nama_item" required />
            <input class="form-control id_item" type="text" name="id_item" required />

            <label for="jenis_satuan" class="text-dark">Jenis Satuan<span class='red'> *</span></label>
            <select class="form-control jenis_satuan" name="jenis_satuan" required>
                <!-- Options will be dynamically populated here -->
            </select>

            <label for="harga_jual_satuan" class="text-dark">Harga Jual Satuan (Rp.):<span class='red'> *</span></label>
            <input class="form-control harga_satuan" type="text" name="harga_satuan" readonly />

            <label for="jumlah_item" class="text-dark">Jumlah Item<span class='red'> *</span></label>
            <input class="form-control jumlah" type="number" name="jumlah" min="1" required />

            <div class="result"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on("input", ".nama_item", function() {
            var searchTerm = $(this).val();
            var resultContainer = $(this).parent().find(".result");

            if (searchTerm !== "") {
                $.ajax({
                    type: "POST",
                    url: "search_item.php",
                    data: { searchTerm: searchTerm },
                    success: function(data) {
                        resultContainer.html(data);
                    }
                });
            } else {
                resultContainer.empty();
            }
        });

        $(document).on("click", ".result li", function() {
            var selectedItem = $(this).text();
            var itemContainer = $(this).closest(".item-container");

            itemContainer.find(".nama_item").val(selectedItem);

            $.ajax({
                type: "POST",
                url: "get_quantity.php",
                data: { selectedItem: selectedItem },
                success: function(response) {
                    var data = JSON.parse(response);
                    itemContainer.find(".id_item").val(data.id_item);
                    var jenisSatuanSelect = itemContainer.find(".jenis_satuan");
                    jenisSatuanSelect.empty();
                    jenisSatuanSelect.append(`<option value="Besar">${data.jenis_satuan_besar}</option>`);
                    jenisSatuanSelect.append(`<option value="Kecil">${data.jenis_satuan_kecil}</option>`);

                    var hargaJualSatuanInput = itemContainer.find(".harga_satuan");
                    hargaJualSatuanInput.val(data.harga_jual_satuan_besar);

                    var jumlahItemInput = itemContainer.find(".jumlah");

                    jenisSatuanSelect.on("change", function() {
                        var selectedJenisSatuan = $(this).val();
                        if (selectedJenisSatuan === "Besar") {
                            hargaJualSatuanInput.val(data.harga_jual_satuan_besar);
                        } else if (selectedJenisSatuan === "Kecil") {
                            hargaJualSatuanInput.val(data.harga_jual_satuan_kecil);
                        }
                        
                        var maxStok = selectedJenisSatuan === "Besar" ? data.jumlah_isi_satuan_besar : data.total_isi_satuan_kecil;
                        jumlahItemInput.attr("max", maxStok);
                    });

                    jumlahItemInput.on("input", function() {
                        var jumlahItem = parseInt($(this).val());
                        var maxStok = parseInt($(this).attr("max"));

                        if (jumlahItem > maxStok) {
                            alert("Jumlah melebihi stok yang tersedia!");
                            $(this).val(maxStok);
                        }

                        // Prevent input of 0 or negative numbers
                        if (jumlahItem <= 0) {
                            $(this).val(1);
                        }
                    });
                }
            });
            itemContainer.find(".result").empty();
        });

        $(document).on("click", ".remove-item", function() {
            var itemContainer = $(this).closest(".item-container");
            itemContainer.find(".jenis_satuan").empty();
            itemContainer.find(".harga_satuan").val("");
            itemContainer.find(".nama_item").val(""); 
            itemContainer.find(".id_item").val(""); 
            itemContainer.remove();
        });

        $(document).on("input", ".nama_item", function() {
            var itemContainer = $(this).closest(".item-container");
            var inputLength = $(this).val().length;
            if (inputLength === 0) {
                itemContainer.find(".jenis_satuan").empty();
                itemContainer.find(".harga_satuan").val("");
                itemContainer.find(".id_item").val("");
            }
        });
    });
</script>

</body>
</html>
