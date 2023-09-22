<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transaksi Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
</head>
<body>
    <div class="container">
        <div class="card card-body border-0">
            <div class="clearfix mb-3">
                <div class="float-start">
                    <h5>Transaksi Penjualan</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="product">Nama Barang</label>
                    <select name="product" id="product" class="form-control select2">
                        <option value="0">Pilih Barang</option>
                        @foreach ($product as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->harga }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="unit-price">Harga Satuan</label>
                    <input type="text" id="unit-price" class="form-control" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="jumlah">Kuantitas</label>
                    <input type="number" id="jumlah" class="form-control">
                </div>
                <input type="hidden" id="selected-product-id">
                <div class="col-md-12 text-end">
                    <button class="btn btn-success btn-sm text-white rounded" id="simpanBtn"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>Kuantitas</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-records">
                        <!-- Data ditambahkan di sini -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Jumlah Harga :</strong></td>
                            <td colspan="2"><span id="total-price">0</span></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Jumlah yang di Bayar :</strong></td>
                            <td colspan="2">
                                <input type="number" id="amount-paid" class="form-control" placeholder="Jumlah yang di Bayar">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Kembalian :</strong></td>
                            <td colspan="2"><span id="change">0</span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="text-end">
                <button class="btn btn-sm btn-primary rounded" id="simpanTransaksiBtn" disabled><i class="fa fa-save"></i> Simpan Transaksi</button>
            </div>
        </div>

        <!-- Modal untuk menampilkan pesan transaksi berhasil -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Transaksi Berhasil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Transaksi berhasil disimpan.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan CDN jQuery di sini -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#product').select2();
            cekDisableButtonSaveTransaction()

            $('#product').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var harga = selectedOption.data('price');
                var obatId = selectedOption.val(); 
                $('#unit-price').val(harga);

                $('#selected-product-id').val(obatId);
            });

            $('#amount-paid').on('input', function () {
                var amountPaid = parseInt($(this).val(), 10);
                var totalPrice = parseInt($('#total-price').text(), 10);

                if (!isNaN(amountPaid)) {
                    var change = amountPaid - totalPrice;
                    $('#change').text(change);

                    if (amountPaid >= totalPrice) {
                        $('#simpanTransaksiBtn').prop('disabled', false);
                    } else {
                        $('#simpanTransaksiBtn').prop('disabled', true);
                    }
                } else {
                    $('#change').text('0');
                    $('#simpanTransaksiBtn').prop('disabled', true);
                }
            });

            $('#simpanBtn').on('click', function () {
                var productName = $('#product option:selected').text();
                var harga = parseInt($('#unit-price').val(), 10);
                var jumlah = parseInt($('#jumlah').val(), 10);
                var obatId = $('#selected-product-id').val();

                if (!obatId || !productName || isNaN(harga) || isNaN(jumlah)) {
                    alert('Mohon isi semua field sebelum menyimpan data.');
                    return;
                }

                var total = harga * jumlah;
                var existingRow = findExistingRow(productName);

                if (existingRow) {
                    var existingjumlah = parseInt(existingRow.find('.jumlah').text(), 10);
                    var existingTotal = parseInt(existingRow.find('.total').text(), 10);

                    existingjumlah += jumlah;
                    existingTotal += total;

                    existingRow.find('.jumlah').text(existingjumlah);
                    existingRow.find('.total').text(existingTotal);
                } else {
                    var newRow = '<tr>' +
                        '<td data-product-id="' + obatId + '">' + productName + '</td>' +
                        '<td>' + harga + '</td>' +
                        '<td class="jumlah">' + jumlah + '</td>' +
                        '<td class="total">' + total + '</td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash"></i></button>' +
                        '</td>' +
                        '</tr>';

                    $('#transaction-records').append(newRow);

                    $('.remove-row').on('click', function () {
                        $(this).closest('tr').remove();
                        updateTotalPrice(); 
                        $('#amount-paid').val('');
                        $('#change').text('0');
                        cekDisableButtonSaveTransaction()
                    });
                }

                updateTotalPrice();

                $('#product').val('0').trigger('change');
                $('#unit-price').val('');
                $('#jumlah').val('');
                cekDisableButtonSaveTransaction()
            });

            function findExistingRow(productName) {
                var existingRow = null;
                $('#transaction-records tr').each(function () {
                    var rowProductName = $(this).find('td:first-child').text();
                    if (rowProductName === productName) {
                        existingRow = $(this);
                        return false; 
                    }
                });
                return existingRow;
            }

            function updateTotalPrice() {
                var totalPrice = 0;
                $('#transaction-records tr').each(function () {
                    var rowTotal = parseInt($(this).find('.total').text(), 10);
                    totalPrice += rowTotal;
                });
                $('#total-price').text(totalPrice);
            }

            function cekDisableButtonSaveTransaction() {
                var amountPaid = parseInt($('#amount-paid').val(), 10);
                var totalPrice = parseInt($('#total-price').text(), 10);
                var rowCount = $('#transaction-records tr').length;

                if (isNaN(amountPaid) || amountPaid < totalPrice || rowCount === 0) {
                    $('#simpanTransaksiBtn').prop('disabled', true);
                } else {
                    $('#simpanTransaksiBtn').prop('disabled', false);
                }
            }

            $('#simpanTransaksiBtn').on('click', function () {
                var transactions = [];
                var amountPaid = parseInt($('#amount-paid').val(), 10);

                $('#transaction-records tr').each(function () {
                    var obatId = $(this).find('td:first-child').data('product-id'); 
                    var harga = parseInt($(this).find('td:nth-child(2)').text(), 10);
                    var jumlah = parseInt($(this).find('.jumlah').text(), 10);

                    transactions.push({
                        "obat_id": obatId, 
                        "jumlah": jumlah,
                        "harga": harga,
                    });
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ url("/transaction-add") }}',
                    data: {
                        transactions: transactions,
                        jumlah_bayar : amountPaid,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#successModal').modal('show');
                        setTimeout(function () {
                            $('#successModal').modal('hide');
                            // printData(response);
                        }, 2000);

                        $('#transaction-records').empty();
                        $('#amount-paid').val('');
                        $('#change').text('0');
                        $('#total-price').text('0');
                        $('#simpanTransaksiBtn').prop('disabled', true);
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.error('Error:', errorThrown);
                        alert('Transaksi gagal. Silakan coba lagi.');
                    }
                });
            });

            function printData(data) {
                var printWindow = window.open('', '', 'width=600,height=600');
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Cetak Struk</title></head><body>');
                printWindow.document.write(data);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            }
            $('#printButton').on('click', function () {
                printData($('#response-data').html());
            });
        });
    </script>
</body>
</html>
