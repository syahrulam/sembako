<form id="pelangganForm" action="#" method="post">
    <div class="pelanggan-container">
        <input type="text" class="nama" placeholder="Nama Pelanggan">
        <input type="hidden" class="id_pelanggan">
        <div class="result_pelanggan"></div>
        <div class="total_hutang_container">
            Total Hutang: <span class="total_hutang_display"></span>
        </div>
    </div>
    <div class="hidden-form" style="display:none;">
        <label for="tipe_pembayaran" class="text-dark">Tipe
            Pembayaran<span class='red'> *</span></label>
        <select class="form-control" name="tipe_pembayaran" id="tipe_pembayaran" required>
            <option value="">Metode Pembayaran</option>
            <option value="Cash">Cash</option>
            <option value="Debit" id="debit-select">Kredit</option>
        </select>
    </div>
</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function handleItemSearch(inputElement) {
            var searchPelanggan = inputElement.val();
            var resultPelanggan = inputElement.parent().find(".result_pelanggan");

            if (searchPelanggan !== "") {
                $.ajax({
                    type: "POST",
                    url: "search_pelanggan.php",
                    data: {
                        searchPelanggan: searchPelanggan
                    },
                    success: function(data) {
                        resultPelanggan.html(data);
                    }
                });
            } else {
                resultPelanggan.empty();
            }
        }

        $(document).on("input", ".nama", function() {
            var inputNamaPelanggan = $(this).val();
            if (inputNamaPelanggan === '') {
                $('.result_pelanggan').empty();
                $('.hidden-form').hide();
            } else {
                handleItemSearch($(this));
            }
        });

        $(document).on("click", ".result_pelanggan li", function() {
            var selectedPelanggan = $(this).text();
            var pelangganContainer = $(this).closest(".pelanggan-container");

            $.ajax({
                type: "POST",
                url: "get_pelanggan_hutang.php",
                data: {
                    selectedPelanggan: selectedPelanggan
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    pelangganContainer.find(".nama").val(data.nama);
                    pelangganContainer.find(".id_pelanggan").val(data.id);
                    pelangganContainer.find(".total_hutang_display").text(data.total_hutang);

                    // Check if total_hutang is greater than 0
                    if (parseInt(data.total_hutang) > 0) {
                        $('#debit-select').hide(); // Hide the "Kredit" option
                    } else {
                        $('#debit-select').show(); // Show the "Kredit" option
                    }

                    $('.hidden-form').show();
                },
                error: function() {
                    $('.hidden-form').hide();
                }
            });
            pelangganContainer.find(".result_pelanggan").empty();
        });


    });
</script>