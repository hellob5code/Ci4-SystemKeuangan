<?php foreach ($tampildata as $row) : ?>
    <tr>
        <td><img src='/img/<?= $row['nama_img'] ?>' class='imgg'></td>
        <td><?= $row['kode'] ?></td>
        <td><?= $row['qty'] ?></td>
        <td><input style="width: 100px;" id="harganow <?= $row['id_detail_penjualan'] ?>" class="harganow" onchange="UbahHarga(<?= $row['id_detail_penjualan'] ?>,<?= $row['id_date_penjualan'] ?>)" type="number" class="form-control" value="<?= $row['harga_beli'] ?>"></td>
        <td><?= number_format($row['ongkos'], 2, ',', '.') ?></td>
        <td><?= $row['jenis'] ?></td>
        <td><?= $row['model'] ?></td>
        <td><?= $row['keterangan'] ?></td>
        <td><?= $row['berat'] ?></td>
        <td><?= $row['berat_murni'] ?></td>
        <td><?= $row['kadar'] ?></td>
        <td><?= $row['nilai_tukar'] ?></td>
        <td><?= $row['merek'] ?></td>
        <td><button type='button' class='btn btn-block bg-gradient-danger' onclick="hapus(<?= $row['id_detail_penjualan'] ?>,<?= $row['id_date_penjualan'] ?>)"><i class='fas fa-trash'></i></button></td>
    </tr>
<?php endforeach; ?>

<script>
    function hapus(id, iddate) {
        Swal.fire({
            title: 'Hapus',
            text: "Yakin ingin Hapus Data ini ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "<?php echo base_url('deletedetailjual'); ?>",
                    data: {
                        id: id,
                        iddate: iddate,
                    },
                    success: function(result) {

                        tampildata()
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Berhasil Dihapus',
                        })

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                })

            }
        })

    }

    function UbahHarga(id, iddate) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: "<?php echo base_url('ubahharga'); ?>",
            data: {
                id: id,
                iddate: iddate,
                hargabaru: document.getElementById('harganow ' + id).value

            },
            success: function(result) {
                tampildata()
                myDataBayar()
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }
</script>