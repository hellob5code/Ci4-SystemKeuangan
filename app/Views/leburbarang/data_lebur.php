<?= $this->extend('layout/template'); ?>
<?= $this->section('content') ?>
<style>
    .table>tbody>tr>* {
        vertical-align: middle;
        text-align: center;
    }

    .imgg {
        width: 100px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Barang Lebur</h1>
                </div><!-- /.col -->
                <!-- /.content-header -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Barang Lebur</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header ">
                            <a class="btn btn-app" href="/leburbarang">
                                <i class="fas fa-plus"></i> Lebur Barang
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table">
                            <table id="example1" class="table table-bordered table-striped tableasd">
                                <thead>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Nomor Lebur</th>
                                        <th>model</th>
                                        <th>Berat Murni</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datalebur as $row) : ?>
                                        <tr>
                                            <td class="imgg"><img class="imgg" src="/img/<?= ($row['nama_img']) ? $row['nama_img'] : 'default.jpg' ?>"></td>
                                            <td><?= $row['no_lebur'] ?></td>
                                            <td><?= $row['model'] ?></td>
                                            <td><?= $row['berat_murni'] ?></td>
                                            <td>
                                                <?php if ($row['status_dokumen'] == 'Draft') : ?>
                                                    <a type="button" href="draftlebur/<?= $row['id_date_lebur'] ?>" class="btn btn-block btn-outline-danger btn-sm">Draft</a>
                                                <?php else : ?>
                                                    <a type="button" href="draftlebur/<?= $row['id_date_lebur'] ?>" class="btn btn-block btn-outline-info btn-sm">Detail</a>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Kode</th>
                                        <th>model</th>
                                        <th>Berat Murni</th>
                                        <th>Detail</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<footer class="main-footer">

</footer>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "aaSorting": []
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis", ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<?= $this->endSection(); ?>