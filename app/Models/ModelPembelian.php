<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPembelian extends Model
{

    protected $table = 'tbl_pembelian';
    protected $primaryKey = 'id_pembelian';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_date_pembelian', 'id_supplier', 'no_faktur_supp', 'no_transaksi', 'id_karyawan', 'tgl_faktur', 'total_berat_murni', 'tgl_jatuh_tempo', 'cara_pembayaran', 'byr_berat_murni', 'total_berat_rill', 'berat_murni_rill', 'total_qty', 'tanggal_bayar', 'harga_murni', 'total_bayar', 'status_dokumen'];

    public function getPembelianSupplier($id = false)
    {
        if ($id == false) {

            $this->findAll();
            $this->orderBy('created_at', 'DESC');
            $data = $this->get();
            return $data->getResult('array');
        }
        return $this->where(['id_date_pembelian' => $id])->first();
    }
    public function getPembelianSupplierJoin($id)
    {
        $this->select('*');
        $this->join('tbl_supplier', 'tbl_supplier.id_supplier = tbl_pembelian.id_supplier');
        $this->where(['id_date_pembelian' => $id]);
        $this->orderBy('tbl_pembelian.created_at', 'DESC');
        $data = $this->get();
        return $data->getResult('array')[0];
    }
    public function getNoTrans()
    {
        $db = db_connect();
        $data = $db->query('select max(substr(no_transaksi,3,10)) no_transaksi from tbl_pembelian limit 1');
        return $data->getResult('array')[0];
    }
}
