<?php

namespace App\Controllers;

use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;
use App\Models\ModelDetailBuyback;
use App\Models\ModelPenjualan;
use App\Models\ModelDetailPenjualan;
use App\Models\ModelKadar;
use App\Models\ModelMerek;
use App\Models\ModelSupplier;
use App\Models\ModelHome;
// use App\Models\ModelBuyback;
use App\Models\ModelKartuStock;
use App\Models\ModelDetailKartuStock;
use App\Models\ModelCuci;
use App\Models\ModelDetailCuci;


use CodeIgniter\Model;
use CodeIgniter\Validation\Rules;
use Faker\Provider\ar_EG\Person;
use PhpParser\Node\Expr\Isset_;

class BarangCuci extends BaseController
{


    public function __construct()
    {
        $this->modeldetailpenjualan =  new ModelDetailPenjualan();
        $this->barcodeG =  new BarcodeGenerator();
        $this->barcodeG =  new BarcodeGenerator();
        $this->penjualan =  new ModelPenjualan();
        $this->modeldetailbuyback = new ModelDetailBuyback();
        $this->datasupplier = new ModelSupplier();
        $this->datakadar = new ModelKadar();
        $this->datamerek = new ModelMerek();
        $this->datastock = new ModelHome();
        // $this->modelbuyback = new ModelBuyback();
        $this->modelkartustock = new ModelKartuStock();
        $this->modeldetailkartustock = new ModelDetailKartuStock();
        $this->modelcuci = new ModelCuci();
        $this->modeldetailcuci = new ModelDetailCuci();
    }

    public function HomeCuci()
    {
        // $asd = $this->modeldetailbuyback->JumlahDataBuyback(220311121607);
        // dd($asd['total']);
        $data = [
            'datacuci' => $this->modelcuci->getDataCuciAll(),
        ];
        return view('cucibarang/data_cuci', $data);
    }
    public function BarcodeGenerate($id)
    {
        $data1 = [
            'databarcode' => $this->modeldetailcuci->getDetailAllCuci($id),
            'datacuci' => $this->modelcuci->getDataCuciAll($id),
            // 'img' => $this->barangmodel->getImg($id)
        ];

        return view('cucibarang/print_barcode', $data1);
    }
    public function CuciBarang()
    {
        $dateid = date('ymdhis');
        $this->modelcuci->save([
            // 'created_at' => date("y-m-d"),
            'id_date_cuci' => $dateid,
            'no_cuci' => $this->NoTransaksiGenerateCuci(),
            'id_karyawan' => '1',
            'supplier_cuci' => '-',
            'keterangan' => '-',
            'total_berat' => '-',
            'keterangan' => '-',
            'jumlah_barang' => '0',
            'tanggal_cuci' => date('y-m-d H:i:s'),
            'harga_cuci' => '0',
            'status_dokumen' => 'Draft'
        ]);
        //---------------------------------------------------
        return redirect()->to('/draftcuci/' . $dateid);
    }
    public function ModalCuci()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            if ($id == 1) {
                $data = [
                    'datacuci' => $this->modeldetailbuyback->getDataCuciAll(),
                    'pesan' => 'Data Cuci Sebelum Pilih'
                ];
            } else {
                $data = [
                    'datacuci' => $this->modeldetailcuci->getDetailCuci($this->request->getVar('dateid')),
                    'pesan' => 'Data Cuci Sesudah Pilih'
                ];
            }
            $msg = [
                'tampilmodal' => view('cucibarang/modalcuci', $data)
            ];
            // $msg = 'sukses';
            echo json_encode($msg);
        }
    }
    public function DraftCuciBarang($id)
    {
        $data = [
            'datamastercuci' => $this->modelcuci->getDataCuciAll($id),
            'datacuci' => $this->modeldetailbuyback->getDataCuciAll(),
            'dataakancuci' => $this->modeldetailcuci->getDetailCuci($id)
        ];
        return view('cucibarang/cuci_barang', $data);
    }

    public function TambahCuci()
    {
        if ($this->request->isAJAX()) {
            $kode = $this->request->getVar('kode');
            $iddate =  $this->request->getVar('iddate');
            $databuyback = $this->modeldetailbuyback->getDataDetailKode($kode);
            $datadetailcuci = $this->modeldetailcuci->CheckDatacuci($databuyback['kode']);
            $datacuci = $this->modelcuci->getDataCuciAll($iddate);
            if (!$datadetailcuci) {
                $this->modeldetailcuci->save([
                    'id_date_cuci' => $iddate,
                    'id_detail_buyback' => $databuyback['id_detail_buyback'],
                    'nama_img' => $databuyback['nama_img'],
                    'kode' =>  $databuyback['kode'],
                    'qty' => $databuyback['qty'],
                    'jenis' =>  $databuyback['jenis'],
                    'model' =>  $databuyback['model'],
                    'keterangan' =>  $databuyback['keterangan'],
                    'berat' =>  $databuyback['berat'],
                    'berat_murni' =>  $databuyback['berat_murni'],
                    'harga_beli' =>  $databuyback['harga_beli'],
                    'ongkos' => $databuyback['ongkos'],
                    'kadar' =>   $databuyback['kadar'],
                    'nilai_tukar' =>   $databuyback['nilai_tukar'],
                    'merek' =>  $databuyback['merek'],
                    'total_harga' => $databuyback['total_harga'],
                    'status_proses' => $databuyback['status_proses'],
                ]);

                $this->modeldetailbuyback->save([
                    'id_detail_buyback' => $databuyback['id_detail_buyback'],
                    'status_proses' => 'SudahCuci' . date('y-m-d')
                ]);
                $this->modelcuci->save([
                    'id_cuci' => $datacuci['id_cuci'],
                    'jumlah_barang' => $this->modeldetailcuci->CountJumlahCuci($iddate)['berat'],
                    'total_berat' => $this->modeldetailcuci->SumBeratDetailCuci($iddate)['berat'],
                ]);
                $msg = 'sukses';
            } else {
                $msg = 'gagal';
            }
            echo json_encode($msg);
        }
    }

    public function BatalCuci($id)
    {
        $datadetailcuci =  $this->modeldetailcuci->getDetailAllCuci($id);
        foreach ($datadetailcuci as $row) {
            $databuyback = $this->modeldetailbuyback->getDataDetailRetur($row['kode']);
            $this->modeldetailbuyback->save([
                'id_detail_buyback' => $databuyback['id_detail_buyback'],
                'status_proses' => 'Cuci'
            ]);
        }
        $this->modeldetailcuci->query('DELETE FROM tbl_detail_cuci WHERE id_date_cuci =' . $id . ';');
        $this->modelcuci->query('DELETE FROM tbl_cuci WHERE id_date_cuci =' . $id . ';');
        return redirect()->to('/datacuci');
    }
    public function DeleteCuci()
    {
        if ($this->request->isAJAX()) {
            $kode =  $this->modeldetailcuci->getDataDetailCuci($this->request->getVar('id'));
            $databuyback = $this->modeldetailbuyback->getDataDetailRetur($kode['kode']);
            $datacuci = $this->modelcuci->getDataCuciAll($kode['id_date_cuci']);

            $this->modeldetailbuyback->save([
                'id_detail_buyback' => $databuyback['id_detail_buyback'],
                'status_proses' => 'Cuci'
            ]);
            $this->modeldetailcuci->delete($this->request->getVar('id'));
            $this->modelcuci->save([
                'id_cuci' => $datacuci['id_cuci'],
                'jumlah_barang' => $this->modeldetailcuci->CountJumlahCuci($kode['id_date_cuci'])['berat'],
                'total_berat' => $this->modeldetailcuci->SumBeratDetailCuci($kode['id_date_cuci'])['berat'],
            ]);
            $msg = 'sukses';
            echo json_encode($msg);
        }
    }
    public function UpdateCuci()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                // 'nilai_tukar' => [
                //     'rules' => 'required',
                //     'errors' => [
                //         'required' => 'Nilai Tukar Harus di isi',
                //     ]
                // ],

                'berat' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Berat Harus di isi',
                    ]
                ],
            ]);
        }
        if (!$valid) {
            $msg = [
                'error' => [
                    // 'nilai_tukar' => $validation->getError('nilai_tukar'),
                    'berat' => $validation->getError('berat'),
                ]
            ];
            echo json_encode($msg);
        } else {
            $id = $this->request->getVar('id');
            $datadetailcuci = $this->modeldetailcuci->getDataDetailCuci($id);
            $datastock = $this->datastock->CheckData($datadetailcuci['kode']);
            $harga_beli = round($datadetailcuci['total_harga'] / $this->request->getVar('berat'));
            $this->datastock->save([
                'id_stock' => $datastock['id_stock'],
                'qty' => $datadetailcuci['qty'],
                'berat' => $this->request->getVar('berat'),
                'harga_beli' => $harga_beli,
                'total_harga' => $datadetailcuci['total_harga'],
                'status' => 'C'
            ]);
            $this->modeldetailcuci->save([
                'id_detail_cuci' => $datadetailcuci['id_detail_cuci'],
                'berat' => $this->request->getVar('berat'),
                'harga_beli' => $harga_beli,
                'status_proses' => 'SelesaiCuci'
            ]);
            // $this->modelbuyback->save([
            //     'id_detail_buyback' => $databuyback['id_detail_buyback'],
            //     'status_proses' => 'SelesaiCuci ' . date('d-m-y')
            // ]);

            $msg = $harga_beli;
            echo json_encode($msg);
        }
    }
    public function TampilCuci()
    {
        if ($this->request->isAJAX()) {

            $id = $this->request->getVar('id');
            $data = $this->modeldetailcuci->getDataDetailCuci($id);
            $msg = [
                'data' => $data
            ];
            echo json_encode($msg);
        }
    }
    public function SelesaiCuci()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'tanggalcuci' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Jenis Harus di isi',
                    ]
                ],

            ]);
            if (!$valid) {
                $msg = [
                    'error' => [
                        'tanggalcuci' => $validation->getError('tanggalcuci'),
                        // 'berat' => $validation->getError('berat'),
                        // 'harga_beli' => $validation->getError('harga_beli'),
                        // 'gambar' => $validation->getError('gambar'),

                    ]
                ];
                echo json_encode($msg);
            } else {

                $datadetailcuci =  $this->modeldetailcuci->getDetailCuci($this->request->getVar('dateidcuci'));
                $datacuci = $this->modelcuci->getDataCuciAll($this->request->getVar('dateidcuci'));

                if ($datadetailcuci) {
                    $this->modelcuci->save([
                        'id_cuci' => $datacuci['id_cuci'],
                        'id_karyawan' => '1',
                        'harga_cuci' => $this->request->getVar('harga_cuci'),
                        'keterangan' =>  $this->request->getVar('keterangan'),
                        'tanggal_cuci' => $this->request->getVar('tanggalcuci'),
                        'status_dokumen' => 'Selesai'
                    ]);

                    $msg = 'sukses';
                } else {
                    $msg = [
                        'error' => [
                            'data' => 'Tidak ada Data',
                        ]
                    ];
                }
                echo json_encode($msg);
            }
        }
    }

    public function NoTransaksiGenerateCuci()
    {
        $data = $this->modelcuci->getNoTransCuci();
        if ($this->modelcuci->getNoTransCuci()) {
            if (substr($data['no_cuci'], 0, 2) == date('y')) {
                $valnotransaksi = substr($data['no_cuci'], 4, 10) + 1;
                $notransaksi = 'C-' . date('ym') . str_pad($valnotransaksi, 4, '0', STR_PAD_LEFT);

                return $notransaksi;
            } else {
                $notransaksi = 'C-' . date('ym') . str_pad(1, 4, '0', STR_PAD_LEFT);

                return $notransaksi;
            }
        } else {
            $notransaksi = 'C-' . date('ym') . str_pad(1, 4, '0', STR_PAD_LEFT);

            return $notransaksi;
        }
    }
}
