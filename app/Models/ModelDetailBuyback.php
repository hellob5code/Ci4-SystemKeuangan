<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDetailBuyback extends Model
{

    protected $table = 'tbl_detail_buyback';
    protected $primaryKey = 'id_detail_buyback';
    protected $useTimestamps = true;
    protected $allowedFields = ['id_date_buyback', 'id_karyawan', 'id_detail_penjualan', 'nama_img', 'kode', 'jenis', 'qty', 'model', 'keterangan', 'berat_murni', 'berat', 'harga_beli', 'ongkos', 'kadar', 'status', 'nilai_tukar', 'merek', 'total_harga', 'no_nota', 'status_proses', 'nama_bank', 'tunai', 'transfer', 'cara_pembayaran', 'no_nota_jual'];

    public function getDetailAllBuyback($id = false)
    {
        if ($id == false) {
            $this->findAll();
            $this->orderBy('created_at', 'DESC');
            $data = $this->get();
            return $data->getResult('array');
        } else {
            $this->where(['id_date_buyback' => $id]);
            $this->orderBy('created_at', 'DESC');
            $query = $this->get();
            return $query->getResult('array');
        }
    }
    public function getDataReturAll()
    {
        $this->where(['status_proses' => 'Retur']);
        $this->orderBy('created_at', 'DESC');
        $query = $this->get();
        return $query->getResult('array');
    }
    public function getDataLeburAll()
    {
        $this->where(['status_proses' => 'Lebur']);
        $this->orderBy('created_at', 'DESC');
        $query = $this->get();
        return $query->getResult('array');
    }
    public function getDataCuciAll()
    {
        $this->where(['status_proses' => 'Cuci']);
        $this->orderBy('created_at', 'DESC');
        $query = $this->get();
        return $query->getResult('array');
    }
    public function getDataDetailRetur($id)
    {
        return $this->where(['kode' => $id])->first();
    }
    public function getCheckReturCust($id)
    {
        return $this->where(['id_detail_penjualan' => $id])->first();
    }
    public function getDataDetailKode($id)
    {
        return $this->where(['id_detail_buyback' => $id])->first();
    }
    public function getDataRetur()
    {
        $this->getWhere(['status_proses' => 'Retur']);
        $this->orderBy('created_at', 'DESC');
        $query = $this->get();
        return $query->getResult('array');
    }
    public function getDetailBuyback($id)
    {
        $query = $this->getWhere(['id_detail_buyback' => $id]);
        return $query->getResult('array');
    }
    public function getDetailoneBuyback($id)
    {
        return $this->where(['id_detail_buyback' => $id])->first();
    }
    public function SumBeratKotorDetailBuyback($id)
    {
        $this->selectSum('berat');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function SumBeratBersihDetailBuyback($id)
    {
        $this->selectSum('berat_murni');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function SumTotalHargaBuyback($id)
    {
        $this->selectSum('total_harga');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function SumDataDetailBeliBuyback($id)
    {
        $this->selectSum('harga_beli');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function SumDataOngkosBuyback($id)
    {
        $this->selectSum('ongkos');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function SumBeratDetailBuyback($id)
    {
        $this->selectSum('berat');
        $this->where('id_date_buyback', $id);
        $query = $this->get();
        return $query->getResult('array')[0];
    }
    public function JumlahDataBuyback($id)
    {
        $db = db_connect();
        $data = $db->query('SELECT count(*) as total FROM tbl_detail_buyback where id_date_buyback = ' . $id . ';');
        return $data->getResult('array')[0];
    }
    public function getKode($id)
    {
        $db = db_connect();
        $data = $db->query('select max(substr(kode,2,7)) kode from tbl_detail_buyback where substr(kode,1,1) = ' . $id . ' limit 1');
        return $data->getResult('array')[0];
        //  $this->get();
        // return $query;
    }
}
