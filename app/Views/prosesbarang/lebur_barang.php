<?= $this->extend('layout/template'); ?>
<?= $this->section('content') ?>
<script type="text/javascript" src="/js/jquery.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<style>
    .table>tbody>tr>* {
        vertical-align: middle;
        text-align: center;
    }

    .imgg {
        width: 100px;
    }


    .autocomplete {
        position: relative;
        display: inline-block;
    }

    input {
        border: 1px solid transparent;
        background-color: #f1f1f1;
        padding: 10px;
        font-size: 16px;
    }

    input[type=text] {
        background-color: #f1f1f1;
        width: 100%;
    }

    input[type=submit] {
        background-color: DodgerBlue;
        color: #fff;
        cursor: pointer;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    /*when hovering an item:*/
    .autocomplete-items div:hover {
        background-color: #e9e9e9;
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
        background-color: DodgerBlue !important;
        color: #ffffff;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Lebur Barang</h1>
                </div><!-- /.col -->
                <!-- /.content-header -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/datalebur">Home</a></li>
                        <li class="breadcrumb-item"><a href="/datalebur">Lebur Barang</a></li>
                        <li class="breadcrumb-item active">Form Lebur</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <!-- /.card-header -->
                    <?php if (isset($datamasterlebur)) : ?>
                        <?php if ($datamasterlebur['status_dokumen'] == 'Selesai') : ?>
                            <table class="table text-nowrap">
                                <tr>
                                    <td>Tanggal Lebur :</td>
                                    <td>
                                        <?= $datamasterlebur['tanggal_lebur'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td> Total Berat Murni :</td>
                                    <td>
                                        <?= $datamasterlebur['berat_murni'] ?> g
                                    </td>
                                </tr>
                                <tr>
                                    <td>Qty :</td>
                                    <td>
                                        <?= $datamasterlebur['qty'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Gambar :</td>
                                    <td>
                                        <img class="imgg" src="/img/<?= $datamasterlebur['nama_img'] ?>">
                                    </td>
                                </tr>
                            </table>
                        <?php else : ?>
                            <form action="/leburbarang" name="formleburbarang" id="formleburbarang" class="formleburbarang" method="post">
                                <?= csrf_field(); ?>
                                <div class="form-group" style="margin: 1mm;">
                                    <label>Tanggal Lebur</label>
                                    <input type="date" class="form-control tanggallebur" id="tanggallebur" name="tanggallebur" value="<?= (isset($datamasterlebur['tanggal_lebur'])) ? date_format(date_create($datamasterlebur['tanggal_lebur']), "Y-m-d") : '' ?>" placeholder="Masukan Tanggal Lebur">
                                    <div id="validationServerUsernameFeedback" class="invalid-feedback inputcustomermsg">
                                    </div>
                                </div>
                            </form>
                        <?php endif ?>
                    <?php endif ?>

                </div>
                <!-- /.card -->
            </div>
            <div class="col-6">
                <!-- Application buttons -->
                <div class="card">
                    <div class="card-body" id="refreshtombol">
                        <a type="button" onclick="Batal()" class="btn btn-app">
                            <i class="fas fa-window-close"></i> Batal Lebur
                        </a>
                        <?php if (isset($datamasterlebur)) : ?>
                            <?php if ($datamasterlebur['status_dokumen'] == 'Selesai') : ?>
                                <a class="btn btn-app bg-primary" type="button">
                                    <i class="fas fa-check"></i> Selesai Lebur
                                </a>
                            <?php else : ?>
                                <a class="btn btn-app bg-danger" type="button" data-toggle="modal" data-target="#modal-lg">
                                    <i class="fas fa-money-bill"></i> Selesai Lebur
                                </a>
                            <?php endif ?>
                        <?php endif ?>

                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <div class="card ">
            <!-- /.card-header -->
            <div class="card-body">
                <br>
                <div class="row">
                    <?php if (isset($datamasterlebur)) : ?>
                        <?php if ($datamasterlebur['status_dokumen'] != 'Selesai') : ?>
                            <div class="col-6">
                                <label>Pilih Barang Lebur</label>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0" style="max-height: 500px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Gambar</th>
                                                    <th>Kode</th>
                                                    <th>Jenis</th>
                                                    <th>Model</th>
                                                    <th>Berat Murni</th>
                                                    <th>Pilih</th>
                                                </tr>
                                            </thead>
                                            <tbody id="refreshtbl1">
                                                <?php foreach ($datalebur as $row) : ?>
                                                    <tr id="datalebur">
                                                        <td class="imgg"><img class="imgg" src="/img/<?= $row['nama_img'] ?>"></td>
                                                        <td><?= $row['kode'] ?></td>
                                                        <td><?= $row['jenis'] ?></td>
                                                        <td><?= $row['model'] ?></td>
                                                        <td><?= $row['berat_murni'] ?></td>
                                                        <td>
                                                            <a type="button" onclick="tambahbaranglebur(<?= $row['id_detail_buyback'] ?>)" class="btn btn-block btn-outline-info btn-sm">Lebur</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <div class="col-6">
                                <label>Barang Lebur</label>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0" style="max-height: 500px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Gambar</th>
                                                    <th>Kode</th>
                                                    <th>Jenis</th>
                                                    <th>Model</th>
                                                    <th>Berat Murni</th>
                                                    <th>Hapus</th>
                                                </tr>
                                            </thead>
                                            <tbody id="refreshtbl">
                                                <?php foreach ($dataakanlebur as $row) : ?>
                                                    <tr id="akanlebur">
                                                        <td class="imgg"><img class="imgg" src="/img/<?= $row['nama_img'] ?>"></td>
                                                        <td><?= $row['kode'] ?></td>
                                                        <td><?= $row['jenis'] ?></td>
                                                        <td><?= $row['model'] ?></td>
                                                        <td><?= $row['berat_murni'] ?></td>
                                                        <td>
                                                            <button type='button' class='btn btn-block bg-gradient-danger' onclick="hapus(<?= $row['id_detail_lebur'] ?>)"><i class='fas fa-trash'></i></button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        <?php else : ?>
                            <div class="col-12">
                                <label>Barang Lebur</label>
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0" style="max-height: 500px;">
                                        <table class="table table-head-fixed text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Gambar</th>
                                                    <th style="text-align: center;">Kode</th>
                                                    <th style="text-align: center;">Jenis</th>
                                                    <th style="text-align: center;">Model</th>
                                                    <th style="text-align: center;">Berat Murni</th>
                                                </tr>
                                            </thead>
                                            <tbody id="refreshtbl">
                                                <?php foreach ($dataakanlebur as $row) : ?>
                                                    <tr id="akanlebur">
                                                        <td class="imgg"><img class="imgg" src="/img/<?= $row['nama_img'] ?>"></td>
                                                        <td><?= $row['kode'] ?></td>
                                                        <td><?= $row['jenis'] ?></td>
                                                        <td><?= $row['model'] ?></td>
                                                        <td><?= $row['berat_murni'] ?></td>

                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        <?php endif ?>
                    <?php endif ?>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Lebur</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/selesailebur" name="selesailebur" id="selesailebur" class="selesailebur" method="post">
                <?= csrf_field(); ?>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group" style="margin: 1mm;">
                            <label>Tanggal Lebur</label>
                            <input type="date" class="form-control tanggallebur" id="tanggallebur" name="tanggallebur" value="<?= (isset($datamasterlebur['tanggal_lebur'])) ? date_format(date_create($datamasterlebur['tanggal_lebur']), "Y-m-d") : '' ?>" placeholder="Masukan Tanggal Lebur">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback tanggalleburmsg">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group" style="margin: 1mm;">
                            <label>Model Lebur</label>
                            <input type="text" class="form-control modellebur" id="modellebur" name="modellebur" value="<?= (isset($datamasterlebur['model'])) ? $datamasterlebur['model'] : '' ?>" placeholder="Masukan Model">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback modelleburmsg">
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>Berat Murni</label>
                            <input type="number" id="berat_murni" name="berat_murni" class="form-control" placeholder="Masukan Nomor Nota Supplier">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback berat_murnimsg">
                            </div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" id="qty" name="qty" value="1" class="form-control" placeholder="Masukan Nomor Nota Supplier">
                            <input type="hidden" name="dateidlebur" value="<?= $datamasterlebur['id_date_lebur'] ?>">
                            <div id="validationServerUsernameFeedback" class="invalid-feedback qtymsg">
                            </div>
                        </div>
                    </div>
                    <div class="col-1">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Foto</label><br>
                            <button type="button" id="ambilgbr" class="btn btn-primary" data-toggle="modal" data-target="#modal-foto" onclick="cameranyala()">
                                <i class="fa fa-camera"></i>
                            </button>
                            <div id="validationServerUsernameFeedback" class="invalid-feedback ambilgbrmsg"></div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modal-foto">
                    <div class="modal-dialog modal-default">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Ambil Foto</h4>
                                <button type="button" class="close" onclick="$('#modal-foto').modal('toggle');" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-group"><label>Gambar</label>
                                                <div class="custom-file">
                                                    <input type="file" name="gambar" class="custom-file-input" id="gambar" accept="image/*">
                                                    <label style="text-align: left" class="custom-file-label" for="gambar">Pilih Gambar</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div id='my_camera'>
                                            </div>
                                            <button style="text-align: center;" type='button' id='ambilfoto' class='btn btn-info ambilfoto' onclick='Foto_ulang()'>
                                                <i class='fa fa-trash'></i></button>
                                            <button type='button' id='ambilfoto' class='btn btn-info ambilfoto' onclick='Ambil_foto()'>Foto <i class='fa fa-camera'></i>
                                            </button>
                                            <input type='hidden' name='gambar' id='gambar' class='image-tag'>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" onclick="$('#modal-foto').modal('toggle');">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="Webcam.reset()" data-dismiss="modal">Done</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btntambah">Selesai</button>
                </div>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<footer class="main-footer">

</footer>
<script type="text/javascript">
    function hapus(id) {
        console.log(id)
        $.ajax({
            type: "get",
            dataType: "json",
            url: "<?php echo base_url('hapuslebur'); ?>",
            data: {
                id: id,
            },
            success: function(hasil) {
                refreshtbl()


            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })

    }

    function Batal() {
        Swal.fire({
            title: 'Batal Lebur ',
            text: "Apakah Ingin Batal Lebur ?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?php echo base_url('batallebur/' . $datamasterlebur['id_date_lebur']); ?>"
            }
        })

    };



    function tambahbaranglebur(kode) {
        console.log(kode)
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "<?php echo base_url('tambahlebur'); ?>",
            data: {
                kode: kode,
                iddate: <?= $datamasterlebur['id_date_lebur'] ?>
            },
            success: function(result) {
                refreshtbl()
                if (result == 'gagal') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Sudah Di masukan',
                    })
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })

    }

    function refreshtbl() {
        $("#refreshtbl").load("/leburbarang/" + <?= $datamasterlebur['id_date_lebur'] ?> + " #akanlebur");
        $("#refreshtbl1").load("/leburbarang/" + <?= $datamasterlebur['id_date_lebur'] ?> + " #datalebur");

    }

    $(document).ready(function() {
        $('.selesailebur').submit(function(e) {
            e.preventDefault()
            Swal.fire({
                title: 'Selesai Lebur ',
                text: "Apakah Yakin Selesai Lebur ?",
                icon: 'info',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Selesai',
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('.selesailebur')[0];
                    let data = new FormData(form)
                    $.ajax({
                        type: "POST",
                        data: data,
                        url: "<?php echo base_url('selesailebur'); ?>",
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        cache: false,
                        success: function(hasil) {
                            if (hasil.error) {
                                if (hasil.error.qty) {
                                    $('#qty').addClass('is-invalid')
                                    $('.qtymsg').html(hasil.error.qty)
                                } else {
                                    $('#qty').removeClass('is-invalid')
                                    $('.qtymsg').html('')
                                }
                                if (hasil.error.berat_murni) {
                                    $('#berat_murni').addClass('is-invalid')
                                    $('.berat_murnimsg').html(hasil.error.berat_murni)
                                } else {
                                    $('#berat_murni').removeClass('is-invalid')
                                    $('.berat_murnimsg').html('')
                                }
                                if (hasil.error.modellebur) {
                                    $('#modellebur').addClass('is-invalid')
                                    $('.modelleburmsg').html(hasil.error.modellebur)
                                } else {
                                    $('#modellebur').removeClass('is-invalid')
                                    $('.modelleburmsg').html('')
                                }
                                if (hasil.error.gambar) {
                                    $('#ambilgbr').addClass('is-invalid')
                                    $('.ambilgbrmsg').html(hasil.error.gambar)
                                } else {
                                    $('#ambilgbr').removeClass('is-invalid')
                                    $('.ambilgbrmsg').html('')
                                }
                                if (hasil.error.data) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Tidak ada data',
                                    })
                                }
                            } else {
                                console.log(hasil)
                                $('#qty').removeClass('is-invalid')
                                $('.qtymsg').html('')
                                $('#berat_murni').removeClass('is-invalid')
                                $('.berat_murnimsg').html('')
                                $('#modellebur').removeClass('is-invalid')
                                $('.modelleburmsg').html('')
                                $('#ambilgbr').removeClass('is-invalid')
                                $('.ambilgbrmsg').html('')
                                window.location.href = "<?php echo base_url('leburbarang/' . $datamasterlebur['id_date_lebur']); ?>"
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    })
                }
            })

        })
        $(".modal").on("hidden.bs.modal", function() {
            Webcam.reset('#my_camera')
        });
    })

    Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 100,
        flip_horiz: true,
    });

    function cameranyala() {
        if ($(".image-tag").val()) {
            document.getElementById('my_camera').innerHTML = '<img src="' + data_uri + '">'
        } else {
            Webcam.attach('#my_camera');
        }
    }

    function Ambil_foto() {
        Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri);
            Webcam.reset()
            // Webcam.attach('#my_camera');
            // console.log($(".image-tag").val())
            document.getElementById('my_camera').innerHTML = '<img src="' + data_uri + '">'
        })
    }

    function Foto_ulang() {
        document.getElementById('my_camera').innerHTML = ''
        $(".image-tag").val('');
        Webcam.attach('#my_camera');
    }
</script>
<?= $this->endSection(); ?>